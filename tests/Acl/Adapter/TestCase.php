<?php


namespace LogikosTest\Access\Acl\Adapter;


use Logikos\Access\Acl;
use Logikos\Access\Acl\Adapter;
use Logikos\Access\Acl\Config as AclConfig;
use Logikos\Access\Acl\Resource;
use Logikos\Access\Acl\Role;
use Logikos\Access\Acl\Rule;
use Logikos\Access\ConfigException;
use PHPUnit\Framework\Assert;

abstract class TestCase extends \LogikosTest\Access\Acl\TestCase {
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
      Assert::assertTrue($acl->isAllowed(
          $r['role'],
          $r['resource'],
          $r['privilege']
      ));
      Assert::assertFalse($acl->isAllowed(
          'guest',
          $r['resource'],
          $r['privilege']
      ));
    }
  }


  protected function AclConfig(): AclConfig {
    $config = new AclConfig(
        [
            'roles'     => Role\Collection::fromArray(self::ROLES),
            'resources' => Resource\Collection::fromArray(self::RESOURCES),
            'rules'     => Rule\Collection::fromArray(self::RULES)
        ]
    );
    return $config;
  }
}