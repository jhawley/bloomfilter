<?php

namespace Hawley\BloomFilter;

class BloomFilter implements IBloomFilter {
    private $_hashes = array();
    private $_filter = array();
    
    public function __construct(IHashFactory $hf, IPRNG $prng, $setSize, 
      $errorChance) {
        $filterSizeNeeded = $this->filterSizeNeeded($errorChance, $setSize);
        $hashesFunctionCount = $this->hashesNeeded($errorChance, $setSize, 
          $filterSizeNeeded);
        for($i = 0; $i < $hashesFunctionCount; ++$i) {
            $seed = $prng->generate();
            $this->_hashes[] = $hf->create($seed, $filterSizeNeeded);
        }
        
        for($i = 0; $i < $filterSizeNeeded; ++$i) {
            $this->_filter[] = 0;
        }
    }
    
    private function hashesNeeded($errorChance, $setSize, $filterSize) {
        return (int)round($filterSize * log(2) / $setSize);
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
    
    public function add($item) {
        foreach($this->_hashes as $hash) {
            $this->_filter[$hash->hash($item)] = 1;
        }
    }
    
    public function mayHave($item) {
        foreach($this->_hashes as $hash) {
            if(!$this->_filter[$hash->hash($item)]) {
                return false;
            }
        }
        return true;
    }
}