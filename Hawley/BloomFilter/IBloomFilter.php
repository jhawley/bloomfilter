<?php

namespace Hawley\BloomFilter;

interface IBloomFilter {
    public function filterSize();
    public function hashSize();
    public function add($item);
    public function mayHave($item);
}