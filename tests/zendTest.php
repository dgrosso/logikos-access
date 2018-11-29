<?php


namespace LogikosTest\Access;


use PHPUnit\Framework\TestCase;
use Zend\Permissions\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

class zendTest extends TestCase {
  /** @var Acl\Acl */
  private $acl;

  protected function setUp() {
    $this->acl = new Acl\Acl();
  }

  public function testFoo() {
    $roleA    = new Role('A');
    $roleB    = new Role('B');
    $resource = new Resource('res');

    $this->acl
      ->addRole($roleA)
      ->addRole($roleB, 'A')
      ->addResource($resource);


    $this->acl->allow($roleA, $resource);

    $this->acl->inheritsRole($roleA, $roleB);
    $this->assertTrue($this->acl->isAllowed($roleA, $resource));
    $this->assertTrue($this->acl->isAllowed($roleB, $resource));
  }
}