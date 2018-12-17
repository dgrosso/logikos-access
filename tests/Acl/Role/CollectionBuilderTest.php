<?php

namespace LogikosTest\Access\Acl\Role;

use Logikos\Access\Acl\InvalidEntityException;
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
    $this->assertRoleInherits('member', ['roleA']);
  }

  public function testAddRoleByNameWithInherits() {
    $b = $this->builder;
    $b->addRoleWithInherits('member', 'roleA,roleB');
    $this->assertRoleInherits('member', ['roleA','roleB']);
  }

  public function testAddRoleWithInheritsThenAddMoreInherits() {
    $b = $this->builder;
    $b->addRoleWithInherits('member', 'roleA,roleB');
    $b->addInherit('member', 'roleC');
    $b->addInherit('member', 'roleD');
    $this->assertRoleInherits('member', ['roleA','roleB','roleC','roleD']);
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
    $b->addRole('B');
    $b->addRolesFromTraversable(new \ArrayIterator([
        ['role'=>'C'],
        ['role'=>'D', 'inherits'=>'A,B'],
        ['role'=>'E', 'inherits'=>['A','B']]
    ]));
    $b->addInherit('E','C');

    $this->assertRoleInherits('A', []);
    $this->assertRoleInherits('B', []);
    $this->assertRoleInherits('C', []);
    $this->assertRoleInherits('D', ['A','B']);
    $this->assertRoleInherits('E', ['A','B','C']);
  }

  public function testAddFromArray() {
    $b = $this->builder;
    $b->addRolesFromArray([
        ['role'=>'A'],
        ['role'=>'B', 'inherits'=>'A']
    ]);

    $this->assertTrue($b->hasRole('A'));
    $this->assertTrue($b->hasRole('B'));
    $this->assertRoleInherits('B', ['A']);
  }

  public function testAddRoleFails() {
    $this->expectException(InvalidEntityException::class);
    $b = $this->builder;
    $b->addRole(null);
  }


  public function testBuild() {
    $b = $this->builder;

    $b->addRolesFromArray([
        ['role'=>'A'],
        ['role'=>'B', 'inherits'=>['A']]
    ]);

    $collection = $b->build();
    $this->assertInstanceOf(Role\Collection::class, $collection);

    $inCollection = [];

    foreach ($collection as $role)
      $inCollection[$role->name()] = $role->toArray();

    $expected = [
        'A' => ['name'=>'A', 'inherits' => []],
        'B' => ['name'=>'B', 'inherits' => ['A']]
    ];

    $this->assertEquals($expected, $inCollection);
  }

  protected function assertRoleInherits($roleName, $inherits) {
    $role = $this->builder->getRole($roleName);
    $this->assertArrayValuesEqual($inherits, $role->inherits());
  }


}
