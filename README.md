# Incomplete
* Pass testOfFalsePositives2
* Note limitations on set size and number of hash functions

#### Purpose
To provide a Bloom Filter data structure where some false positives are tolerable

#### Example
    $b = new BloomFilter(new HashFactory(), new PRNG(), 100, .001);
    $this->assertEqual($b->mayHave(1), false);
    $b->add(1);
    $this->assertEqual($b->mayHave(1), true);
    $this->assertEqual($b->mayHave(2), false); //very small probability of failure

#### Installation
Requires PHP 5.3.0 (for anonymous functions).  The hash class requires the BCMath Arbitrary Precision extension (http://www.php.net/manual/en/book.bc.php).

#### License
Public domain without warranties

#### Misc Notes
Rounding appears to have a significant impact on the number of hash functions used (e.g. using 3 vs 4 on a set size of 1000 and a filter size of 2000).   