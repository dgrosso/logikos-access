<?php


namespace LogikosTest\Access\Acl\Role;


use Logikos\Access\Acl\InvalidEntityException;
use Logikos\Access\Acl\Role\Role;
use LogikosTest\Access\Acl\TestCase;
use PHPUnit\Framework\Assert;

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

  public function testBuildWithName() {
    $r = Role::build('member');
    $this->assertInstanceOf(Role::class, $r);
    $this->assertSame('member', $r->name());
  }

  public function test_WhenNoInheritsSet_GetEmptyArray() {
    $r = Role::build('member');
    Assert::assertSame([], $r->inherits());
  }

  public function testBuildWithInherits() {
    $r = Role::build('member', 'roleA');
    $this->assertArrayValuesEqual(['roleA'], $r->inherits());
  }

  public function testBuildWithInheritsAsArray() {
    $r = Role::build('member', ['roleA', 'roleB']);
    $this->assertArrayValuesEqual(['roleA', 'roleB'], $r->inherits());
  }

  public function testBuildWithInheritsAsCommaSeparatedList() {
    $r = Role::build('member', 'roleA,roleB');
    $this->assertArrayValuesEqual(['roleA', 'roleB'], $r->inherits());
  }
}