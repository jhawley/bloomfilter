<?php

namespace Hawley\BloomFilter;

class BloomFactory implements IBloomFactory {
    public function create(IHashFactory $hf, IPRNG $prng, $setSize, 
      $errorChance, IBloomFilterStrategy $bfs) {
        return new SimpleBloomFilter($hf, $prng, $setSize, 
          $errorChance, $bfs);
    }
}