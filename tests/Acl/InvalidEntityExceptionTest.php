<?php


namespace LogikosTest\Access\Acl;


use Logikos\Access\Acl\InvalidEntityException;
use Logikos\Access\Acl\Resource\Resource;
use LogikosTest\Access\Acl\TestCase;
use PHPUnit\Framework\Assert;

class InvalidEntityExceptionTest extends TestCase {
  public function testGetEntity() {
    try {
      new Resource();
    }
    catch (InvalidEntityException $e) {
      Assert::assertInstanceOf(Resource::class, $e->getEntity());
      Assert::assertNotEmpty($e->getValidationMessages());
    }
  }
}