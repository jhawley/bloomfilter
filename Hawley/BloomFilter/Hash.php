<?php

namespace Hawley\BloomFilter;

class Hash implements IHash {
    private $seed;
    private $size;
    
    public function __construct($seed, $size) {
        $this->seed = $seed;
        $this->size = $size;
    }
    
    public function hash($content) {
        return bcmod(
          bcadd(
            number_format(hexdec(md5($this->seed+$content)), 0, '', ''), 0
          ), $this->size);
    }
}