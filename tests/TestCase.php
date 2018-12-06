<?php


namespace LogikosTest\Access;


use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase {
  const DIR_TESTS = __DIR__;

  public static function assertArrayValuesEqual(array $expected, array $actual) {
    sort($expected);
    sort($actual);
    Assert::AssertEquals($expected, $actual);
  }
}