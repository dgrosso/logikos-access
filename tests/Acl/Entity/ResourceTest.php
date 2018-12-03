<?php


namespace LogikosTest\Access\Acl\Entity;

use Logikos\Access\Acl\Entity\InvalidEntityException;
use Logikos\Access\Acl\Entity\Resource;
use Logikos\Access\Acl\Resource as ResourceInterface;
use LogikosTest\Access\Acl\TestCase;
use PHPUnit\Framework\Assert;

class ResourceTest extends TestCase {
  public function testConstructWithoutNameThrowsException() {
    $this->expectException(InvalidEntityException::class);
    $r = new Resource();
  }

  public function testSetAndGetName() {
    $r = new Resource(['name'=>'reports']);
    Assert::assertEquals('reports', $r->name());
    Assert::assertInstanceOf(ResourceInterface::class, $r);
  }

  public function testSetAndGetDescription() {
    $r = new Resource(['name'=>'reports', 'description'=>'foo']);
    Assert::assertEquals('foo', $r->description());
  }

  public function testToString() {
    $r = new Resource(['name'=>'foo']);
    Assert::assertSame('foo', (string) $r);
  }
}