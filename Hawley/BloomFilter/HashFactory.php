<?php

namespace Hawley\BloomFilter;

class HashFactory implements IHashFactory {
    public function create($seed, $size) {
        return new Hash($seed, $size);
    }
}