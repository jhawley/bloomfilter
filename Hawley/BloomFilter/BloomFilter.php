<?php

namespace Hawley\BloomFilter;

class BloomFilter {
    private $_hashes = array();
    private $_filter = array();
    
    public function __construct(IHashFactory $hf, $setSize, $errorChance) {
        $hashesFunctionCount = $this->hashesNeeded($errorChance, $setSize);
        $filterSizeNeeded = $this->filterSizeNeeded($errorChance, $setSize);
        for($i = 0; $i < $hashesFunctionCount; ++$i) {
            $seed = mt_rand();
            $this->_hashes[] = $hf->create($seed, $filterSizeNeeded);
        }
        
        for($i = 0; $i < $filterSizeNeeded; ++$i) {
            $this->_filter[] = 0;
        }
    }
    
    private function hashesNeeded($errorChance, $setSize) {
        throw new Exception("Not implemented");
    }
    
    private function filterSizeNeeded($errorChance, $setSize) {
        throw new Exception("Not implemented");
    }
    
    public function filterSize() {
        return count($this->_filter);
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