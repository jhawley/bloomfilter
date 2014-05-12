<?php

namespace Hawley\BloomFilter;

abstract class BloomFilter implements IBloomFilter {
    protected $_hashes = array();
    protected $_filter = array();
    
    public function __construct(IHashFactory $hf, IPRNG $prng, $setSize, 
      $errorChance) {
        $p = 1;
        $newErrorChance = $errorChance;
        $_seeds = array();
        // unchecked rounding can cause the expected error rate to exceed the 
        //   specified expected error rate
        while($p > $errorChance) {
            $filterSizeNeeded = $this->filterSizeNeeded($newErrorChance, $setSize);
            $hashesFunctionCount = $this->hashesNeeded($newErrorChance, $setSize, 
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
    
    private function hashesNeeded($errorChance, $setSize, $filterSize) {
        return round($filterSize * log(2) / $setSize);
    }
    
    private function filterSizeNeeded($errorChance, $setSize) {
        return ceil(-$setSize * (log($errorChance)) / pow(log(2),2));
    }
    
    public function filterSize() {
        return count($this->_filter);
    }
    
    public function hashSize() {
        return count($this->_hashes);
    }
    
    abstract public function add($item);
    
    abstract public function mayHave($item);
}