<?php

namespace Hawley\BloomFilter;

class BloomFilter {
    private $_hashes = array();
    private $_filter = array();
    
    public function __construct($setSize, $errorChance) {
        $mk = (int)$this->hashesNeeded($errorChance, $setSize);
        for($i = 0; $i < $mk; ++$i) {
            $seed = mt_rand();
            $this->_hashes[] = (function($item) use ($seed, $mk){
                /*echo "\nmk is ".$mk;
                echo "\n".md5($seed.$item);
                echo "\n".(substr(md5($seed.$item), -4));
                echo "\n".$this->ordWord(substr(md5($seed.$item), -4));
                echo "\n".($this->ordWord(substr(md5($seed.$item), -4)) % $mk);
                
                echo "\n";*/
                $string = substr(md5($seed.$item), -4);
                $ordWord = 0;
                for ($i=0; $i<strlen($string); $i++) {
                    $ordWord += ord($string[$i]);
                }
                return ($ordWord % $mk);
            });
            $this->_filter[] = 0;
        }
    }
    
    private function hashesNeeded($errorChance, $setSize) {
        return log($errorChance)/log(1-(1/(M_E^($setSize))));
    }
    
    public function filterSize() {
        return count($this->_hashes);
    }
    
    public function add($item) {
        foreach($this->_hashes as $hashFunction) {
            $this->_filter[$hashFunction($item)] = 1;
        }
        //echo "The filter is has ".count(array_filter($this->_filter))." 1's";
    }
    
    public function mayHave($item) {
        foreach($this->_hashes as $hashFunction) {
            if($hashFunction($item) && !$this->_filter[$hashFunction($item)]) {
                return false;
            }
        }
        return true;
    }
}