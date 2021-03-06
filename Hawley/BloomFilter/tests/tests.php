<?php
require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/autoload.php');

use Hawley\BloomFilter\SimpleBloomFilter as BloomFilter;
use Hawley\BloomFilter\Hash;
use Hawley\BloomFilter\HashFactory;
use Hawley\BloomFilter\PRNG;
use Hawley\BloomFilter\StableBloomFilter;
use Hawley\BloomFilter\RemovalBloomFilter;
use Hawley\BloomFilter\BloomFactory;
use Hawley\BloomFilter\MemoryStrategy;
use Hawley\BloomFilter\LoadStrategy;

class TestOfStrategies extends UnitTestCase {
    public function testOfMemory() {
        $m = new MemoryStrategy();
        $this->assertEqual($m->filterSizeNeeded(.001, 100), 1438);
        $this->assertEqual($m->hashesNeeded(100, 1438), 10);
    }
    
    public function testOfLoad() {
        $m = new LoadStrategy();
        $this->assertEqual($m->filterSizeNeeded(.001, 100), 100000);
        $this->assertEqual($m->hashesNeeded(100, 100000), 1);
    }
}

class TestOfBloomFilter extends UnitTestCase {  
    protected $prng;
    protected $strategy;
    
    public function setUp() {
        $this->prng = new PRNG();
        $this->strategy = new MemoryStrategy();
    }
    
    public function testOfDefault() {
        $b = $this->makeFilter(100, .001);
        $this->assertEqual($b->mayHave(1), false);
        $b->add(1);
        $this->assertEqual($b->mayHave(1), true);
        $this->assertEqual($b->mayHave(2), false);
    }
    
    public function testOfFilterSize() {
        $this->assertEqual($this->makeFilter(100, .001)->filterSize(), 1583);
        $this->assertEqual($this->makeFilter(200, .001)->filterSize(), 3165);
        $this->assertEqual($this->makeFilter(100, .0001)->filterSize(), 1918);
    }
    
    public function testOfHashSize() {
        $this->assertEqual($this->makeFilter(100, .001)->hashSize(), 11);
        $this->assertEqual($this->makeFilter(200, .001)->hashSize(), 11);
        $this->assertEqual($this->makeFilter(100, .0001)->hashSize(), 13);
    }
    
    /*public function testOfFalsePositives() {
        $this->assertEqual($this->falsePositivesTest(10000, .01), true);
        //$this->assertEqual($this->falsePositivesTest(1000, .01), true);
    }*/
    
    protected function makeFilter($setSize, $errorProbability) {
        return new BloomFilter(new HashFactory(), $this->prng, $setSize, 
          $errorProbability, $this->strategy);
    }
    
    protected function falsePositivesTest($n, $p) {
        $_results = array();
        $falsePositives = 0;
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
        return (count(array_filter($_results)) < 1);
    }
}

class TestOfSimpleBloomFilter extends TestOfBloomFilter {
    public function testOfSetSizeLimit() {
        $limit = 50;
        $b = $this->makeFilter($limit, .001);
        for($i = 0; $i < $limit; ++$i) {
            $b->add($i);
        }
        $this->expectException();
        $b->add($limit+1);
    }
}

class testOfStableBloomFilter extends TestOfBloomFilter {    
    public function testOfStability1() {
        $b = $this->makeFilter(1, .001);
        $this->assertEqual($b->mayHave(1), false);
        $b->add(1);
        $this->assertEqual($b->mayHave(1), true);
        $b->add(2);
        $this->assertEqual($b->mayHave(2), true);
        $this->assertEqual($b->mayHave(1), false);
    }
    
    public function testOfStability2() {
        $b = $this->makeFilter(999, .001);
        for($counter = 1; $counter < 1001; $counter++) {
            $b->add($counter);
        }
        $this->assertEqual($b->mayHave(1), false);
        $this->assertEqual($b->mayHave(1000), true);
    }
    
    protected function makeFilter($setSize, $errorProbability) {
        return new StableBloomFilter(new HashFactory(), $this->prng, $setSize, 
          $errorProbability, $this->strategy);
    }
}

class testOfRemovalBloomFilter extends TestOfSimpleBloomFilter {    
    protected function makeFilter($setSize, $errorProbability) {
        return new RemovalBloomFilter(new HashFactory(), $this->prng, $setSize, 
          $errorProbability, $this->strategy, new BloomFactory());
    }
    
    public function testOfRemoval() {
        $b = $this->makeFilter(100, .001);
        $b->add(1);
        $this->assertEqual($b->mayHave(1), true);
        $b->remove(1);
        $this->assertEqual($b->mayHave(1), false);
    }
    
    public function testOfBadRemoval() {
        $b = $this->makeFilter(100, .001);
        $this->expectException();
        $b->remove(1);
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