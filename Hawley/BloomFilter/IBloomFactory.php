<?php

namespace Hawley\BloomFilter;

interface IBloomFactory {
    public function create(IHashFactory $hf, IPRNG $prng, $setSize, 
      $errorChance);
}