<?php
require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/autoload.php');

use Hawley\BloomFilter\BloomFilter;
use Hawley\BloomFilter\Hash;
use Hawley\BloomFilter\HashFactory;
use Hawley\BloomFilter\PRNG;

class TestOfBloomFilter extends UnitTestCase {  
    private $prng;
    
    public function setUp() {
        $this->prng = new PRNG();
    }
    
    public function testOfDefault() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 100, .001);
        $this->assertEqual($b->mayHave(1), false);
        $b->add(1);
        $this->assertEqual($b->mayHave(1), true);
        $this->assertEqual($b->mayHave(2), false);
    }
    
    public function testOfFilterSize1() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 100, .001);
        $this->assertEqual($b->filterSize(), 1583);
    }
    
    public function testOfFilterSize2() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 200, .001);
        $this->assertEqual($b->filterSize(), 3165);
    }
    
    public function testOfFilterSize3() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 100, .0001);
        $this->assertEqual($b->filterSize(), 1918);
    }
    
    public function testOfHashSize1() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 100, .001);
        $this->assertEqual($b->hashSize(), 11);
    }
    
    public function testOfHashSize2() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 200, .001);
        $this->assertEqual($b->hashSize(), 11);
    }
    
    public function testOfHashSize3() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 100, .0001);
        $this->assertEqual($b->hashSize(), 13);
    }
    
    public function testOfFalsePositives1() {
        $_results = array();
        $falsePositives = 0;
        $n = 10000;
        $p = .01;
        $hf = new HashFactory();
        for($i = 0; $i < 10; ++$i) {
            $b = new BloomFilter($hf, $this->prng, $n, $p);
            for($j = 0; $j < $n; ++$j) {
                $b->add($j * 2);
                if($b->mayHave($j) && ($j % 2 == 1)) {
                    ++$falsePositives;
                }
            }
            // failure should be a 6 standard deviation event
            $_results[] = (int)($falsePositives > 
              $n*$p + 6*pow($n*$p*(1-$p), .5));
            $falsePositives = 0;
        }
        // no failure rates in the 6 standard deviation range
        $this->assertEqual(count(array_filter($_results)) < 1, true);
    }
    
    public function testOfFalsePositives2() {
        $_results = array();
        $falsePositives = 0;
        $n = 1000;
        $p = .01;
        $hf = new HashFactory();
        for($i = 0; $i < 10; ++$i) {
            $b = new BloomFilter($hf, $this->prng, $n, $p);
            for($j = 0; $j < $n; ++$j) {
                $b->add($j * 2);
                if($b->mayHave($j) && ($j % 2 == 1)) {
                    ++$falsePositives;
                }
            }
            // failure should be a 6 standard deviation event
            $_results[] = (int)($falsePositives > 
              $n*$p + 6*pow($n*$p*(1-$p), .5));
            $falsePositives = 0;
        }
        // no failure rates in the 6 standard deviation range
        $this->assertEqual(count(array_filter($_results)) < 1, true);
    }
}

class testOfHash extends UnitTestCase {
    public function testOfHashFunction() {
        $h = new Hash('test', 5);
        $this->assertEqual((int)$h->hash(1), 2);
    }
}

class testOfHashFactory extends UnitTestCase {
    public function testOfHashFactoryCreate() {
        $hf = new HashFactory();
        $this->assertEqual($hf->create(1, 1) instanceof Hash, true);
    }
}