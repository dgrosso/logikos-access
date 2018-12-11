<?php


namespace LogikosTest\Access;


use Logikos\Access\Acl\InvalidEntityException;
use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\StrictConfig as Config;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase {
  const DIR_TESTS = __DIR__;

  public static function assertArrayValuesEqual(array $expected, array $actual) {
    sort($expected);
    sort($actual);
    Assert::AssertEquals($expected, $actual);
  }

  protected function assertFieldValidationFailed(Config $c, $field) {
    try {
      $c->validate(); // this should throw so the next line should never execute
      $this->expectException(InvalidConfigStateException::class);
    }
    catch (InvalidConfigStateException $e) {
      Assert::assertContains($field, array_keys($e->getValidationMessages()));
    }
    catch (InvalidEntityException $e) {
      Assert::assertContains($field, array_keys($e->getEntity()->validationMessages()));
    }
  }
}