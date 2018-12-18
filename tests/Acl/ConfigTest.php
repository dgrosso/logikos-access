<?php


namespace LogikosTest\Access\Acl;


use Logikos\Access\Acl;
use Logikos\Access\Acl\Adapter\Phalcon as AclAdapter;
use Logikos\Access\Acl\Config;
use Logikos\Access\Acl\Inherits;
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
    $c->withInherits($inherits=Inherits\Collection::fromArray(self::INHERITED_ROLES));
    $this->assertEquals($roles, $c->roles);
    $this->assertEquals($resources, $c->resources);
    $this->assertEquals($rules, $c->rules);
    $this->assertEquals($inherits, $c->inherits);
  }

  public function testBasicConfig() {
    $c = new Config;
    $c->withRoles($roles=Role\Collection::fromArray(self::ROLES));
    $c->withResources($resources=Resource\Collection::fromArray(self::RESOURCES));
    $c->withRules($rules=Rule\Collection::fromArray(self::RULES));
    $c->withInherits($inherits=Inherits\Collection::fromArray(self::INHERITED_ROLES));

    $acl = AclAdapter::buildFromConfig($c);
    $this->assertCanReadReports($acl, 'member', 'fred', 'bob', 'ken');
    $this->assertCanScheduleReports($acl, 'admin', 'bob', 'ken');
    $this->assertAccessDenied($acl, 'guest', 'reports', 'read');
    $this->assertAccessDenied($acl, 'fred', 'reports', 'schedule');
  }

  public function testConfigWithInheritsInRolesCollection() {
    $c = new Config;
    $c->withRoles($roles=Role\Collection::fromArray(self::ROLES_WITH_INHERITS));
    $c->withResources($resources=Resource\Collection::fromArray(self::RESOURCES));
    $c->withRules($rules=Rule\Collection::fromArray(self::RULES));

    $acl = AclAdapter::buildFromConfig($c);
    $this->assertCanReadReports($acl, 'member', 'fred', 'bob', 'ken');
    $this->assertCanScheduleReports($acl, 'admin', 'bob', 'ken');
    $this->assertAccessDenied($acl, 'guest', 'reports', 'read');
    $this->assertAccessDenied($acl, 'fred', 'reports', 'schedule');
  }

  public function testInheritsInRoleEntitiesAndInInheritsCollection() {
    $c = new Config;
    $c->withRoles($roles=Role\Collection::fromArray(self::ROLES_WITH_INHERITS));
    $c->withResources($resources=Resource\Collection::fromArray(self::RESOURCES));
    $c->withRules($rules=Rule\Collection::fromArray(self::RULES));
    $c->withInherits($inherits=Inherits\Collection::fromArray([
        ['role'=>'fred', 'inherits'=>'admin']
    ]));

    $acl = AclAdapter::buildFromConfig($c);
    $this->assertCanReadReports($acl, 'member', 'fred', 'bob', 'ken');
    $this->assertCanScheduleReports($acl, 'admin', 'bob', 'ken');
    $this->assertAccessDenied($acl, 'guest', 'reports', 'read');
    $this->assertAccessAllowed($acl, 'fred', 'reports', 'schedule');
  }

  protected function assertCanReadReports(Acl\Adapter $acl, ... $roles) {
    foreach ($roles as $role)
      $this->assertAccessAllowed($acl, $role, 'reports', 'read');
  }

  protected function assertCanScheduleReports(Acl\Adapter $acl, ... $roles) {
    foreach ($roles as $role)
      $this->assertAccessAllowed($acl, $role, 'reports', 'schedule');
  }

  protected function assertAccessAllowed(Acl\Adapter $acl, $role, $resource, $privilege) {
    $this->assertTrue(
        $acl->isAllowed($role, $resource, $privilege),
        "{$this->roleResourcePrivilege($role, $resource, $privilege)} should be Allowed"
    );
  }

  protected function assertAccessDenied(Acl\Adapter $acl, $role, $resource, $privilege) {
    $this->assertTrue(
        !$acl->isAllowed($role, $resource, $privilege),
        "{$this->roleResourcePrivilege($role, $resource, $privilege)} should be Denied"
    );
  }


  protected function roleResourcePrivilege($role, $resource, $privilege) {
    return sprintf(
        "%s@%s:%s",
        $role, $resource, $privilege
    );
  }
}