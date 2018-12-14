<?php

namespace LogikosTest\Access\Acl\Role;

use Logikos\Access\Acl\Role;
use LogikosTest\Access\Acl\TestCase;

class CollectionBuilderTest extends TestCase {


  /** @var Role\CollectionBuilder */
  private $builder;

  public function setUp() {
    parent::setUp();
    $this->builder = new Role\CollectionBuilder();
  }

  // Roles
  public function testAddIndividualRolesByName() {
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

  public function testAddRolesFromPdoStatement() {
    $this->addRoles('roleA', 'roleB');
    $sth = $this->db->pdoQuery("select * from roles");
    $this->builder->addRolesFromTraversable($sth);
    $this->assertTrue($this->builder->hasRole('roleA'));
    $this->assertTrue($this->builder->hasRole('roleB'));
  }

  public function testRolesAddedViaDifferentWaysAllEndupInOneCollectionTogether() {
    $b = $this->builder;
    $b->addRole('A');
    $b->addRolesFromTraversable(new \ArrayIterator([
        ['role'=>'B'],
        ['role'=>'C'],
        ['role'=>'D']
    ]));
    $this->assertTrue($b->hasRole('A'));
    $this->assertTrue($b->hasRole('B'));
    $this->assertTrue($b->hasRole('C'));
    $this->assertTrue($b->hasRole('D'));
  }
}
