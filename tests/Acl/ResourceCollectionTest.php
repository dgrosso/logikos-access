<?php

namespace LogikosTest\Access\Acl;

use Logikos\Access\Acl\Resource;
use Logikos\Access\Acl\ResourceCollection;

class ResourceCollectionTest extends TestCase {

  private $resources = ['dashboard', 'reports', 'members'];

  public function setUp() {
    parent::setUp();
    $this->loadFixtures();
  }

  protected function loadFixtures() {
    $this->addResources(...$this->resources);
  }

  public function testBuildFromPdoStatement() {
    $sth = $this->db->pdoQuery("select * from resources");
    $collection = ResourceCollection::buildFromPdoStatement($sth);

    $found = [];
    foreach ($collection as $resource) {
      /** @var Resource $resource */
      $this->assertInstanceOf(Resource::class, $resource);
      $this->assertEmpty($resource->description());
      array_push($found, $resource->name());
    }
    $this->assertEquals($this->resources, $found);
  }

  public function testBuildFromNetteResultSet() {
    $sth = $this->db->selectWhere('resources', 'resource, description', []);
    $collection = new ResourceCollection($sth);

    foreach ($collection as $resource) {
      $this->assertInstanceOf(Resource::class, $resource);
    }
  }
}