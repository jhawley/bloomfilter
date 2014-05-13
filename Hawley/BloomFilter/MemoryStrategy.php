<?php

namespace Hawley\BloomFilter;

class MemoryStrategy implements IBloomFilterStrategy {
    public function hashesNeeded($setSize, $filterSize) {
        return round($filterSize * log(2) / $setSize);
    }
    
    public function filterSizeNeeded($errorChance, $setSize) {
        return ceil(-$setSize * (log($errorChance)) / pow(log(2),2));
    }
}