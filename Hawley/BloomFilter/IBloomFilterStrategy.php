<?php

namespace Hawley\BloomFilter;

interface IBloomFilterStrategy {
    public function filterSizeNeeded($errorChance, $setSize);
    public function hashesNeeded($setSize, $filterSize);
}