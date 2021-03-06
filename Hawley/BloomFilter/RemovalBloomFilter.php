<?php

namespace Hawley\BloomFilter;

class RemovalBloomFilter implements IRemovalBloomFilter {
    protected $_hashes = array();
    protected $_filter = array();
    protected $removalFilter, $setSize;
    protected $itemsHeld = 0;
    
    public function __construct(IHashFactory $hf, IPRNG $prng, $setSize, 
      $errorChance, IBloomFilterStrategy $bfs, IBloomFactory $bf) {
        $p = 1;
        $newErrorChance = $errorChance;
        $_seeds = array();
        $this->removalFilter = $bf->create($hf, $prng, $setSize, $errorChance,
          $bfs);
        // unchecked rounding can cause the expected error rate to exceed the 
        //   specified expected error rate
        while($p > $errorChance) {
            $filterSizeNeeded = $bfs->filterSizeNeeded($newErrorChance, 
              $setSize);
            $hashesFunctionCount = $bfs->hashesNeeded($setSize, 
              $filterSizeNeeded);
            $p = $this->testProbability($setSize, $filterSizeNeeded, 
              $hashesFunctionCount);
            $newErrorChance /= 2;
        }
        for($i = 0; $i < $hashesFunctionCount; ++$i) {
            while($seed = $prng->generate())
            {
                if(!in_array($seed, $_seeds))
                {
                    $_seeds[] = $seed;
                    break;
                }
            }
            $this->_hashes[] = $hf->create($seed, $filterSizeNeeded);
        }
        
        for($i = 0; $i < $filterSizeNeeded; ++$i) {
            $this->_filter[] = 0;
        }
        $this->setSize = $setSize;
    }
    
    private function testProbability($setSize, $filterSize, $hashSize) {
        return pow(1 - pow(1 - (1/$filterSize), $setSize * $hashSize), $hashSize);
    }
    
    public function filterSize() {
        return count($this->_filter);
    }
    
    public function hashSize() {
        return count($this->_hashes);
    }
    
    public function add($item) {
        if($this->itemsHeld >= $this->setSize) {
            throw new \Exception("Set size exceeded!");
        }
        $this->itemsHeld++;
        foreach($this->_hashes as $hash) {
            $this->_filter[$hash->hash($item)] = 1;
        }
    }
    
    public function remove($item) {
        if($this->mayHave($item)) {
            if(!$this->removalFilter->mayHave($item)) {
                $this->removalFilter->add($item);
                return true;
            }
            return false;
        } else {
            throw new \Exception("attempted to remove an item before added");
        }
    }
    
    public function mayHave($item) {
        foreach($this->_hashes as $hash) {
            if(!$this->_filter[$hash->hash($item)]) {
                return false;
            }
        }
        return !$this->removalFilter->mayHave($item);
    }
}