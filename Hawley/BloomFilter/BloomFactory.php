<?php

namespace Hawley\BloomFilter;

class BloomFactory implements IBloomFactory {
    public function create(IHashFactory $hf, IPRNG $prng, $setSize, 
      $errorChance) {
        return new SimpleBloomFilter($hf, $prng, $setSize, 
          $errorChance);
    }
}