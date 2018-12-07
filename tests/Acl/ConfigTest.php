<?php


namespace LogikosTest\Access\Acl;


use Logikos\Access\Acl;
use Logikos\Access\Acl\Config;
use Logikos\Util\Config\InvalidConfigStateException;
use PHPUnit\Framework\Assert;

class ConfigTest extends TestCase {
  public function testRequiresRoles() {
    $this->assertFieldValidationFailed(new Config(), 'roles');
  }

  public function testRequiresResources() {
    $this->assertFieldValidationFailed(new Config(), 'resources');
  }

  public function testDefaultActionDefaultsToDeny() {
    $this->assertClassHasConstant(Acl::class, 'ALLOW');
    $this->assertClassHasConstant(Acl::class, 'DENY');
    $c = new Config;
    Assert::assertEquals(Acl::DENY, $c->defaultAction);
  }

  public function testGrants() {
    // or rules
    $this->markTestSkipped('need to finish Rules first');
  }

  protected function assertFieldValidationFailed(Config $c, $field) {
    try {
      $c->validate(); // this should throw so the next line should never execute
      $this->expectException(InvalidConfigStateException::class);
    } catch (InvalidConfigStateException $e) {
      Assert::assertContains($field, array_keys($e->getValidationMessages()));
    }
  }

  private function assertClassHasConstant($class, $constant) {
    $classConstant = "{$class}::{$constant}";
    Assert::assertTrue(
        defined($classConstant),
        "Failed to assert Constant exists: {$classConstant}"
    );
  }
}