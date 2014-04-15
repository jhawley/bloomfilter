<?php

namespace Hawley\BloomFilter;

interface IHashFactory {
    public function create($seed, $size);
}