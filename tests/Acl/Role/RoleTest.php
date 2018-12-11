<?php


namespace LogikosTest\Access\Acl\Role;


use Logikos\Access\Acl\InvalidEntityException;
use Logikos\Access\Acl\Role\Role;
use LogikosTest\Access\Acl\TestCase;

class RoleTest extends TestCase {
  public function testNameIsRequired() {
    try {
      new Role(['name'=>'']);
      $this->fail('Expected InvalidEntityException to be thrown');
    }
    catch (InvalidEntityException $e) {
      $msgs = $e->getEntity()->validationMessages();
      $this->assertContains(
          'name',
          array_keys($msgs)
      );
    }
  }

  public function testSetAndGetNameAndDescription() {
    $r = new Role([
        'name'=>'admin',
        'description'=>'System Administrator'
    ]);
    $this->assertSame('admin', $r->name());
    $this->assertSame('admin', (string) $r);
    $this->assertSame('System Administrator', $r->description());
  }
}