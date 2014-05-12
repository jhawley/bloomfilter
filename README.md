# Incomplete
* Pass testOfFalsePositives2
* Note limitations on set size and number of hash functions
* Throw an exception upon adding an excessive number of elements
* Move some bloom filter functions into traits (to reduce code duplication)
* Reduce code duplication in tests

#### Purpose
To provide a Bloom Filter data structure where some false positives are tolerable

#### Example
    $b = new SimpleBloomFilter(new HashFactory(), new PRNG(), 100, .001);
    $this->assertEqual($b->mayHave(1), false);
    $b->add(1);
    $this->assertEqual($b->mayHave(1), true);
    $this->assertEqual($b->mayHave(2), false); //very small probability of failure

#### Installation
Requires PHP 5.3.0 (for anonymous functions).  The hash class requires the BCMath Arbitrary Precision extension (http://www.php.net/manual/en/book.bc.php).

#### License
Public domain without warranties