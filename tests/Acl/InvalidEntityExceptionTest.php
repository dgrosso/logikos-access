<?php


namespace LogikosTest\Access\Acl;


use Logikos\Access\Acl\InvalidEntityException;
use Logikos\Access\Acl\Resource\Resource;
use LogikosTest\Access\Acl\TestCase;

class InvalidEntityExceptionTest extends TestCase {
  public function testGetEntity() {
    try {
      new Resource();
    }
    catch (InvalidEntityException $e) {
      $this->assertInstanceOf(Resource::class, $e->getEntity());
    }
  }
}