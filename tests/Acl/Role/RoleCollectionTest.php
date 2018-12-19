<?php


namespace LogikosTest\Access\Acl\Role;


use Logikos\Access\Acl\Role;
use Logikos\Access\Acl\Role\Collection;
use LogikosTest\Access\Acl\TestCase;
use PHPUnit\Framework\Assert;

class RoleCollectionTest extends TestCase {
  private $roles = ['admin', 'member', 'guest'];

  public function setUp() {
    parent::setUp();
    $this->loadFixtures();
  }

  protected function loadFixtures() {
    $this->addRoles(...$this->roles);
  }

  public function testBuildFromPdoStatement() {
    $sth = $this->db->pdoQuery("select * from roles");
    $collection = Collection::fromPdoStatement($sth);

    $found = [];

    /** @var Role $role */
    foreach ($collection as $role) {
      Assert::assertInstanceOf(Role::class, $role);
      array_push($found, $role->name());
    }
    Assert::assertEquals($this->roles, $found);
  }

  public function testConstructFromPdoStatementCanIterateMoreThanOnce() {
    $sth = $this->db->pdoQuery("select * from roles");
    $collection = new Collection($sth);
    $c1 = $c2 = 0;
    foreach($collection as $r1) $c1++;
    foreach($collection as $r2) $c2++;
    Assert::assertGreaterThanOrEqual(1, $c1);
    Assert::assertEquals($c1, $c2);
  }

  public function testBuildFromNetteResultSet() {
    $sth = $this->db->selectWhere('roles', 'role, description', []);
    $collection = new Collection($sth);

    foreach ($collection as $resource) {
      Assert::assertInstanceOf(Role::class, $resource);
    }
  }


  public function testBuildFromArray() {
    $data = [
        ['role'=>'admin'],
        ['role'=>'member'],
        ['role'=>'guest', 'description'=>'foo'],
    ];
    $collection = Collection::fromArray($data);

    $found = [];
    /** @var Role $role */
    foreach ($collection as $role) {
      Assert::assertInstanceOf(Role::class, $role);
      array_push($found, $role->name());
    }
    self::assertArrayValuesEqual($this->roles, $found);
  }

  public function testWithInherits() {
    $data = [
        ['role'=>'admin'],
        ['role'=>'member'],
        ['role'=>'bob', 'inherits'=>'admin,member'],
        ['role'=>'ben', 'inherits'=>['admin','member']]
    ];

    $collection = Collection::fromArray($data);
    /** @var Role $role */
    foreach ($collection as $role) {
      if (in_array($role->name(), ['admin', 'member']))
        Assert::assertEmpty($role->inherits());
      else
        $this->assertArrayValuesEqual(['admin', 'member'], $role->inherits());
    }

  }

  public function testFromArrayOfRoles() {
    /** @var Role\Role[] $data */
    $data = [
        'A' => Role\Role::build('A'),
        'B' => Role\Role::build('B'),
        'C' => Role\Role::build('C', 'A'),
        'D' => Role\Role::build('D', 'A,B'),
        'E' => Role\Role::build('E', ['A','B'])
    ];

    /** @var Role\Role[] $collection */
    $collection = Collection::fromArray($data);
    foreach ($collection as $role) {
      $r = $data[$role->name()];
      $expected = [
          'name' => $r->name(),
          'description' => $r->description(),
          'inherits' => $r->inherits()
      ];
      $actual = [
          'name' => $role->name(),
          'description' => $role->description(),
          'inherits' => $role->inherits()
      ];
      $this->assertEquals($expected, $actual);
    }
  }

  public function testCanSerialize() {

    /** @var Role\Role[] $data */
    $data = [
        'A' => Role\Role::build('A'),
        'B' => Role\Role::build('B'),
        'C' => Role\Role::build('E', ['A','B'])
    ];

    /** @var Role\Role[] $collection */
    $collection = Collection::fromArray($data);

    $this->assertCanS
    Assert::assertEquals($collection, unserialize(serialize($collection)));
  }

}