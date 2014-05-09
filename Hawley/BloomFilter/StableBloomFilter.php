<?php

namespace Hawley\BloomFilter;

class StableBloomFilter extends BloomFilter {
    private $counter = 0;
    
    public function add($item) {
        $this->counter++;
        foreach($this->_hashes as $hash) {
            $this->_filter[$hash->hash($item)] = $this->counter;
        }
    }
    
    public function mayHave($item) {
        foreach($this->_hashes as $hash) {
            if(($this->_filter[$hash->hash($item)] == 0) || 
              ($this->counter - $this->_filter[$hash->hash($item)]) >= $this->setSize) {
                return false;
            }
        }
        return true;
    }
}