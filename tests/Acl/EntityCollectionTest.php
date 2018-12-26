<?php


namespace LogikosTest\Access\Acl;

use Logikos\Access\Acl\EntityCollection;
use Logikos\Access\Acl\Role;
use PHPUnit\Framework\Assert;

class EntityCollectionTest extends TestCase {

  public function testCanSerialize() {

    /** @var Role\Role[] $data */
    $data = [
        'A' => Role\Role::build('A'),
        'B' => Role\Role::build('B'),
        'C' => Role\Role::build('E', ['A','B'])
    ];

    /** @var Role\Role[] $collection */
    $collection = Role\Collection::fromArray($data);

    Assert::assertEquals($collection, unserialize(serialize($collection)));
  }

  public function testBuildFromPdoStatement() {
    $roles = ['admin', 'member', 'guest'];
    $this->addRoles(...$roles);

    $sth = $this->db->pdoQuery("select * from roles");
    $collection = Role\Collection::fromPdoStatement($sth);

    $found = [];

    /** @var Role $role */
    foreach ($collection as $role) {
      Assert::assertInstanceOf(Role::class, $role);
      array_push($found, $role->name());
    }
    Assert::assertEquals($roles, $found);
  }

  public function testConstructFromPdoStatementCanIterateMoreThanOnce() {
    $roles = ['admin', 'member', 'guest'];
    $this->addRoles(...$roles);

    $sth = $this->db->pdoQuery("select * from roles");
    $collection = new Role\Collection($sth);
    $c1 = $c2 = 0;
    foreach($collection as $r1) $c1++;
    foreach($collection as $r2) $c2++;
    Assert::assertGreaterThanOrEqual(1, $c1);
    Assert::assertEquals($c1, $c2);
  }

  public function testBuildFromNetteResultSet() {
    $roles = ['admin', 'member', 'guest'];
    $this->addRoles(...$roles);

    $sth = $this->db->selectWhere('roles', 'role, description', []);
    $collection = new Role\Collection($sth);

    foreach ($collection as $resource) {
      Assert::assertInstanceOf(Role::class, $resource);
    }
  }

  public function testFindEntity() {
    $data = [
        ['role'=>'admin'],
        ['role'=>'member'],
        ['role'=>'guest', 'description'=>'foo'],
    ];
    $collection = Role\Collection::fromArray($data);

    $role = $collection->find(function (Role $role) {
      return $role->name() == 'member';
    });

    Assert::assertSame('member', $role->name());
  }

  public function testFindWhenNotThere() {

    $collection = Role\Collection::fromArray([
        ['role'=>'admin']
    ]);

    $result = $collection->find(function (){return false;});

    Assert::assertNull($result);
  }

  public function testFindEntityByToStringValue() {
    $data = [
        ['role'=>'admin'],
        ['role'=>'member'],
        ['role'=>'guest', 'description'=>'foo'],
    ];
    $collection = Role\Collection::fromArray($data);

    $role = $collection->findByString('guest');

    Assert::assertSame('guest', $role->name());
  }

  public function testFilterCollection() {
    /** @var Role\Collection|Role\Role[] $collection */
    $collection = Role\Collection::fromArray([
        'A' => Role\Role::build('A'),
        'B' => Role\Role::build('B', ['A']),
        'C' => Role\Role::build('C', ['A','B']),
        'D' => Role\Role::build('D', ['B','C']),
        'E' => Role\Role::build('E', ['D'])
    ]);

    $roles = $collection->filter(function (Role $role) {
      return in_array('A', $role->inherits());
    });
    $this->assertInstanceOf(Role\Collection::class, $roles);
    $this->assertCollectionContains($roles, ['B','C']);
  }

  protected function assertCollectionContains(EntityCollection $collection, array $names) {
    $namesInCollection = [];
    foreach ($collection as $role) array_push($namesInCollection, $role->__toString());

    $this->assertCount(count($names), $namesInCollection);
    foreach ($names as $name)
      $this->assertContains($name, $namesInCollection);
  }
}