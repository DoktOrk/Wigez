<?php

namespace Foo\Cryptography\Hashing;

/**
 * Tests the Default hasher
 */
class DefaultHasherTest extends \PHPUnit\Framework\TestCase
{
    /** @var DefaultHasher The hasher to use in the tests */
    private $hasher = null;

    /**
     * Sets up the tests
     */
    public function setUp()
    {
        $this->hasher = new DefaultHasher();
    }

    /**
     * Tests getting the default cost
     */
    public function testGettingDefaultCost()
    {
        $this->assertEquals(10, DefaultHasher::DEFAULT_COST);
    }

    /**
     * Tests a hash that doesn't need to be rehashed
     */
    public function testHashThatDoesNotNeedToBeRehashed()
    {
        $hashedValue = $this->hasher->hash('foo', ['cost' => 5]);
        $this->assertFalse($this->hasher->needsRehash($hashedValue, ['cost' => 5]));
    }

    /**
     * Tests a hash that needs to be rehashed
     */
    public function testHashThatNeedsToBeRehashed()
    {
        $hashedValue = $this->hasher->hash('foo', ['cost' => 5]);
        $this->assertTrue($this->hasher->needsRehash($hashedValue, ['cost' => 6]));
    }

    /**
     * Tests verifying a correct hash
     */
    public function testVerifyingCorrectHash()
    {
        $hashedValue = $this->hasher->hash('foo', ['cost' => 4]);
        $this->assertTrue(DefaultHasher::verify($hashedValue, 'foo'));
    }

    /**
     * Tests verifying a correct hash with a pepper
     */
    public function testVerifyingCorrectHashWithPepper()
    {
        $hashedValue = $this->hasher->hash('foo', ['cost' => 4], 'pepper');
        $this->assertTrue(DefaultHasher::verify($hashedValue, 'foo', 'pepper'));
    }

    /**
     * Tests verifying an incorrect hash
     */
    public function testVerifyingIncorrectHash()
    {
        $hashedValue = $this->hasher->hash('foo', ['cost' => 4]);
        $this->assertFalse(DefaultHasher::verify($hashedValue, 'bar'));
    }

    /**
     * Tests verifying an incorrect hash with a pepper
     */
    public function testVerifyingIncorrectHashWithPepper()
    {
        $hashedValue = $this->hasher->hash('foo', ['cost' => 4], 'pepper');
        $this->assertFalse(DefaultHasher::verify($hashedValue, 'bar'));
    }
}