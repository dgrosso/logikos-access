<?php


namespace LogikosTest\Access\Acl\Adapter;


use Logikos\Access\Acl;
use Logikos\Access\Acl\Adapter;
use Logikos\Access\Acl\Config as AclConfig;
use Logikos\Access\Acl\Inherits;
use Logikos\Access\Acl\Resource;
use Logikos\Access\Acl\Role;
use Logikos\Access\Acl\Rule;
use Logikos\Access\ConfigException;
use LogikosTest\Access\Acl\TestCase as AclTestCase;
use PHPUnit\Framework\Assert;

abstract class TestCase extends AclTestCase {
  /** @return Adapter */
  abstract protected function acl();

  public function test_BuildFromConfig_ValidatesConfig() {
    $this->expectException(ConfigException::class);
    $this->acl()::buildFromConfig(new AclConfig());
  }

  public function test_DefaultActionIsDeny() {
    $acl = $this->acl();
    Assert::assertInstanceOf(Adapter::class, $acl);
    Assert::assertSame(Acl::DENY, $acl->getDefaultAction());
  }

  public function testAclLoadsRolesFromConfig() {
    $acl = $this->acl();

    foreach (self::ROLES as $r)
      Assert::assertTrue($acl->isRole($r['role']), "Role {$r['role']} was not loaded...");
  }

  public function testAclLoadsResourcesFromConfig() {
    $acl = $this->acl();

    foreach (self::RESOURCES as $r)
      Assert::assertTrue($acl->isResource($r['resource']), "Resource {$r['resource']} was not loaded...");
  }

  public function testAclLoadsRulesFromConfig() {
    $acl = $this->acl();

    foreach (self::RULES as $r) {
      $this->assertIsAllowed($acl, $r['role'], $r['resource'], $r['privilege']);
      $this->assertIsNotAllowed($acl, 'guest', $r['resource'], $r['privilege']);
    }
  }

  public function testAclLoadsInheritedRoles() {
    $acl = $this->acl();

    foreach (self::INHERITED_ROLES as $r) {
      $this->assertIsAllowed($acl, 'fred', 'reports', 'read');
      $this->assertIsNotAllowed($acl, 'fred', 'reports', 'schedule');

      $this->assertIsAllowed($acl, 'bob', 'reports', 'read');
      $this->assertIsAllowed($acl, 'bob', 'reports', 'schedule');
      $this->assertIsAllowed($acl, 'ken', 'reports', 'read');
      $this->assertIsAllowed($acl, 'ken', 'reports', 'schedule');
    }
  }

  public function testGetRoles() {
    $acl = $this->acl();
    $roles = $acl->getRoles();
    Assert::assertInstanceOf(Role\Collection::class, $roles);
    Assert::assertCount(count(self::ROLES), $roles);
  }

  public function testGetResources() {
    $acl = $this->acl();
    $resources = $acl->getResources();
    Assert::assertInstanceOf(Resource\Collection::class, $resources);
    Assert::assertCount(count(self::RESOURCES), $resources);
  }

  public function testCanSerialize() {
    $acl = $this->acl();
    $this->assertCanSerialize($acl);
  }

  protected function assertIsAllowed(Adapter $acl, $role, $resource, $privilege) {
    Assert::assertTrue(
        $acl->isAllowed($role, $resource, $privilege),
        "{$role} should have access to {$resource}!{$privilege} but doesn't"
    );
  }

  protected function assertIsNotAllowed(Adapter $acl, $role, $resource, $privilege) {
    Assert::assertFalse(
        $acl->isAllowed($role, $resource, $privilege),
        "{$role} should not have access to {$resource}!{$privilege} but does"
    );
  }


  protected function AclConfig(): AclConfig {
    $config = new AclConfig(
        [
            'roles'     => Role\Collection::fromArray(self::ROLES),
            'resources' => Resource\Collection::fromArray(self::RESOURCES),
            'rules'     => Rule\Collection::fromArray(self::RULES),
            'inherits'  => Inherits\Collection::fromArray(self::INHERITED_ROLES)
        ]
    );
    return $config;
  }
}