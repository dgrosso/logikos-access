<?php

namespace LogikosTest\Access\Acl;

use Logikos\Access\Acl\ConfigBuilder;
use Logikos\Access\Acl\Role;
use Qaribou\Collection\ImmArray;

class ConfigBuilderTest extends TestCase {

  /** @var ConfigBuilder */
  private $builder;

  public function setUp() {
    $this->builder = new ConfigBuilder();
  }

  /** @test */
  public function AddIndividualRolesByName() {
    $b = $this->builder;
    $roles = ['a','b','c'];
    foreach ($roles as $role)
      $b->addRole($role);

    $loadedRoles = [];
    /** @var Role $r */
    foreach ($b->roles() as $r)
      array_push($loadedRoles, $r->name());

    $this->assertArrayValuesEqual($roles, $loadedRoles);
  }

  public function testAddRolesAsRoleObjects() {
    $role = Role\Role::build('member');
    $this->builder->addRole($role);
    $this->assertTrue($this->builder->hasRole('member'));
  }

  public function testAddRoleInherits() {
    $this->builder->addRole('member');
    $this->builder->addInherit('member', 'roleA');
    $inherits = $this->builder->getRole('member')->inherits();
    $this->assertArrayValuesEqual(['roleA'], $inherits);
  }

  public function testAddRoleByNameWithInherits() {
    $b = $this->builder;
    $b->addRoleWithInherits('member', 'roleA,roleB');
    $role = $b->getRole('member');
    $this->assertArrayValuesEqual(['roleA','roleB'], $role->inherits());
  }
}
