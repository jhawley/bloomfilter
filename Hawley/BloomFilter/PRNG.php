<?php

namespace Hawley\BloomFilter;

class PRNG implements IPRNG {
    
    public function __construct() {
        
    }
    
    public function generate() {
        return mt_rand();
    }
}