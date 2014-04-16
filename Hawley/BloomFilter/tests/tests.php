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
        $this->assertEqual($b->filterSize(), 1438);
    }
    
    public function testOfFilterSize2() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 200, .001);
        $this->assertEqual($b->filterSize(), 2876);
    }
    
    public function testOfFilterSize3() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 100, .0001);
        $this->assertEqual($b->filterSize(), 1918);
    }
    
    public function testOfHashSize1() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 100, .001);
        $this->assertEqual($b->hashSize(), 10);
    }
    
    public function testOfHashSize2() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 200, .001);
        $this->assertEqual($b->hashSize(), 10);
    }
    
    public function testOfHashSize3() {
        $b = new BloomFilter(new HashFactory(), $this->prng, 100, .0001);
        $this->assertEqual($b->hashSize(), 13);
    }
    
    /*public function testOfFalsePositives() {
        $_results = array();
        $falsePositives = 0;
        $hf = new HashFactory();
        for($i = 0; $i < 1000; ++$i) {
            $b = new BloomFilter($hf, $this->prng, 1000, .01);
            for($j = 0; $j < 1000; ++$j) {
                $b->add($j * 2);
                if($b->mayHave($j) && ($j % 2 == 1)) {
                    ++$falsePositives;
                }
            }
            // failure should be a 3 standard deviation event
            //   1000 * .01 + 3*(n*p*(1-p))^.5
            //echo "\n false positives:  $falsePositives";
            $_results[] = (int)($falsePositives > 20);
            $falsePositives = 0;
        }
        echo "\n failures:  ".count(array_filter($_results))."\n";
        // failure should be a 6 standard deviation event
        //   1000 * .01 + 6*(n*p*(1-p))^.5
        $this->assertEqual(count(array_filter($_results)) < 30, true);
    }*/
}

class testOfHash extends UnitTestCase {
    public function testOfHashFunction() {
        $h = new Hash('test', 5);
        $this->assertEqual((int)$h->hash(1), 4);
    }
}

class testOfHashFactory extends UnitTestCase {
    public function testOfHashFactoryCreate() {
        $hf = new HashFactory();
        $this->assertEqual($hf->create(1, 1) instanceof Hash, true);
    }
}