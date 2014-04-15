# Incomplete
* The number of hash function and the filter size need to be calculated independently.
* testOfFalsePositives needs to be revisited in a more rigorous manner.
* The hash class needs to be tested more rigorously

#### Purpose
To provide a Bloom Filter data structure where some false positives are tolerable

#### Example
    $b = new BloomFilter(new HashFactory(), 100, .001);
    $this->assertEqual($b->mayHave(1), false);
    $b->add(1);
    $this->assertEqual($b->mayHave(1), true);
    $this->assertEqual($b->mayHave(2), false); //very small probability of failure

#### Installation
The autoload.php file requires a version of PHP that supports lambda expressions.  The hash class requires the BCMath Arbitrary Precision extension (http://www.php.net/manual/en/book.bc.php).

#### License
Public domain without warranties