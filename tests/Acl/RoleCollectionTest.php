<?php


namespace LogikosTest\Access\Acl;


use Logikos\Access\Acl\Role;
use Logikos\Access\Acl\RoleCollection;
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
    $collection = RoleCollection::fromPdoStatement($sth);

    $found = [];

    /** @var Role $role */
    foreach ($collection as $role) {
      Assert::assertInstanceOf(Role::class, $role);
      array_push($found, $role->name());
    }
    Assert::assertEquals($this->roles, $found);
  }

  public function testBuildFromNetteResultSet() {
    $sth = $this->db->selectWhere('roles', 'role, description', []);
    $collection = new RoleCollection($sth);

    foreach ($collection as $resource) {
      Assert::assertInstanceOf(Role::class, $resource);
    }
  }


  public function testBuildFromArray() {
    $data = [
        ['role'=>'admin'],
        ['role'=>'member'],
        ['role'=>'guest', 'description'=>'foo']
    ];
    $collection = RoleCollection::fromArray($data);

    $found = [];
    /** @var Role $role */
    foreach ($collection as $role) {
      Assert::assertInstanceOf(Role::class, $role);
      array_push($found, $role->name());
    }
    self::assertArrayValuesEqual($this->roles, $found);
  }


}