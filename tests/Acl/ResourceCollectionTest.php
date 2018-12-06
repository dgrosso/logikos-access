<?php

namespace LogikosTest\Access\Acl;

use Logikos\Access\Acl\Entity\Resource as ResourceEntity;
use Logikos\Access\Acl\Resource;
use Logikos\Access\Acl\ResourceCollection;
use Logikos\Util\Config\MutableConfig;
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

    $collection = ResourceCollection::buildFromPdoStatement($sth);

    $found = [];

    /** @var Resource $resource */
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
}