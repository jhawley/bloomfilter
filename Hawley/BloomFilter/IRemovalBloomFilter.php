<?php

namespace Hawley\BloomFilter;

interface IRemovalBloomFilter {
    public function filterSize();
    public function hashSize();
    public function add($item);
    public function remove($item);
    public function mayHave($item);
}