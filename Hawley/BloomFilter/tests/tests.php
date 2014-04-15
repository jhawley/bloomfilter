<?php
require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/autoload.php');

use Hawley\BloomFilter\BloomFilter;
use Hawley\BloomFilter\Hash;
use Hawley\BloomFilter\HashFactory;

/*class TestOfBloomFilter extends UnitTestCase {    
    public function testOfDefault() {
        $b = new BloomFilter(new HashFactory(), 100, .001);
        $this->assertEqual($b->mayHave(1), false);
        $b->add(1);
        $this->assertEqual($b->mayHave(1), true);
        $this->assertEqual($b->mayHave(2), false);
    }
    
    public function testOfFilterSize1() {
        $b = new BloomFilter(new HashFactory(), 100, .001);
        $this->assertEqual($b->filterSize(), 701);
    }
    
    public function testOfFilterSize2() {
        $b = new BloomFilter(new HashFactory(), 200, .001);
        $this->assertEqual($b->filterSize(), 1391);
    }
    
    public function testOfFilterSize3() {
        $b = new BloomFilter(new HashFactory(), 100, .0001);
        $this->assertEqual($b->filterSize(), 934);
    }
    
    public function testOfFalsePositives() {
        $falsePositives = 0;
        $hf = new HashFactory();
        for($i = 0; $i < 100; ++$i) {
            $b = new BloomFilter($hf, 100, .001);
            for($j = 0; $j < 100; ++$j) {
                $b->add($j * 2);
                if($b->mayHave($j) && ($j % 2 == 1)) {
                    ++$falsePositives;
                }
            }
        }
        echo "\n false positives:  $falsePositives \n";
        $this->assertEqual($falsePositives < 20, true);
    }
}*/

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