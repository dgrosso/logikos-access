<?php


namespace LogikosTest\Access\Acl;


use Logikos\Access\Acl;
use Logikos\Access\Acl\Config;
use Logikos\Access\Acl\Resource;
use Logikos\Access\Acl\Role;
use Logikos\Access\Acl\Rule;
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

  public function testWithRoles() {
    $c = new Config;
    $c->withRoles($roles=Role\Collection::fromArray(self::ROLES));
    $c->withResources($resources=Resource\Collection::fromArray(self::RESOURCES));
    $c->withRules($rules=Rule\Collection::fromArray(self::RULES));
    $this->assertEquals($roles, $c->roles);
    $this->assertEquals($resources, $c->resources);
    $this->assertEquals($rules, $c->rules);
  }
}