<?php

namespace Hawley\BloomFilter;

class SimpleBloomFilter extends BloomFilter {
    public function add($item) {
        if($this->itemsHeld >= $this->setSize) {
            throw new \Exception("Set size exceeded!");
        }
        $this->itemsHeld++;
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