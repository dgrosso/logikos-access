<?php

namespace LogikosTest\Access\Acl\Resource;

use Logikos\Access\Acl\Resource\Resource as ResourceEntity;
use Logikos\Access\Acl\Resource;
use Logikos\Access\Acl\Resource\Collection as ResourceCollection;
use LogikosTest\Access\Acl\TestCase;
use PHPUnit\Framework\Assert;

class ResourceCollectionTest extends TestCase {

  private $resources = ['dashboard', 'reports', 'members'];

  public function setUp() {
    parent::setUp();
    $this->loadFixtures();
  }

  protected function loadFixtures() {
    $this->addResources(...$this->resources);
    $this->addPrivileges('dashboard', 'view', 'addwidget');
    $this->addPrivileges('reports', 'read', 'schedule');
  }

  public function testBuildFromPdoStatement() {
    $sth = $this->db->pdoQuery("select * from resource_privileges");

    $collection = ResourceCollection::fromPdoStatement($sth);

    $found = [];

    /** @var ResourceEntity $resource */
    foreach ($collection as $resource) {
      Assert::assertInstanceOf(Resource::class, $resource);
      Assert::assertEmpty($resource->description());
      array_push($found, $resource->name());
    }
    self::assertArrayValuesEqual($this->resources, $found);
  }

  public function testBuildFromNetteResultSet() {
    $sth = $this->db->selectWhere('resource_privileges', '*', []);
    $collection = new ResourceCollection($sth);

    foreach ($collection as $resource) {
      Assert::assertInstanceOf(Resource::class, $resource);
    }
  }

  public function testBuildFromArray() {
    $data = [
        ['resource'=>'dashboard', 'privileges'=>'login,add-widget'],
        ['resource'=>'reports',   'privileges'=>'view,schedule'],
        ['resource'=>'members']
    ];
    $collection = ResourceCollection::fromArray($data);

    $found = [];
    /** @var ResourceEntity $resource */
    foreach ($collection as $resource) {
      Assert::assertInstanceOf(Resource::class, $resource);
      array_push($found, $resource->name());
    }
    self::assertArrayValuesEqual($this->resources, $found);
  }

  public function testWithArrayPrivileges() {
    $data = [
        ['resource'=>'reports',   'privileges'=>['view','schedule']]
    ];
    $collection = ResourceCollection::fromArray($data);

    /** @var ResourceEntity $resource */
    foreach ($collection as $resource) {
      Assert::assertEquals('reports', $resource->name());
      Assert::assertEquals(['view','schedule'], $resource->privileges()->toArray());
    }
  }
}