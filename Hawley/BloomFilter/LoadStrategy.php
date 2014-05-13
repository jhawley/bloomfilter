<?php

namespace Hawley\BloomFilter;

class LoadStrategy implements IBloomFilterStrategy {
    public function hashesNeeded($setSize, $filterSize) {
        return 1;
    }
    
    public function filterSizeNeeded($errorChance, $setSize) {
        return ceil($setSize / $errorChance);
    }
}