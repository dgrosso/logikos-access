<?php


namespace LogikosTest\Access\Acl\Entity;


use Logikos\Access\Acl\Entity\InvalidEntityException;
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