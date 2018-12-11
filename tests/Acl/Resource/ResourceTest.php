<?php


namespace LogikosTest\Access\Acl\Entity;

use Logikos\Access\Acl\Entity\InvalidEntityException;
use Logikos\Access\Acl\Resource\Resource;
use Logikos\Access\Acl\Resource as ResourceInterface;
use LogikosTest\Access\Acl\TestCase;
use PHPUnit\Framework\Assert;

class ResourceTest extends TestCase {
  public function testConstructWithoutNameThrowsException() {
    $this->expectException(InvalidEntityException::class);
    $r = new Resource();
  }

  public function testSetAndGetName() {
    $r = new Resource([
        'name'=>'reports',
        'privileges'=>[]
    ]);
    Assert::assertEquals('reports', $r->name());
    Assert::assertInstanceOf(ResourceInterface::class, $r);
  }

  public function testSetAndGetDescription() {
    $r = new Resource([
        'name'=>'reports',
        'description'=>'foo',
        'privileges'=>[]
    ]);
    Assert::assertEquals('foo', $r->description());
  }

  public function testToString() {
    $r = new Resource([
        'name'=>'foo',
        'privileges'=>[]
    ]);
    Assert::assertSame('foo', (string) $r);
  }

  public function testSetAndGetPrivileges() {
    $r = new Resource([
        'name'=>'reports',
        'privileges'=>['read', 'schedule']
    ]);
    $this->assertEquals(
        ['read', 'schedule'],
        $r->privileges()->toArray()
    );
  }
}