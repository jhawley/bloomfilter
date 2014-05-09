<?php

namespace Hawley\BloomFilter;

interface IHash {
    public function hash($content);
}