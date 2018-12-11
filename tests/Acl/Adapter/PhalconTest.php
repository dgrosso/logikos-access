<?php


namespace LogikosTest\Access\Acl\Adapter;


use Logikos\Access\Acl\Adapter\Phalcon as PhalconAcl;
use Logikos\Access\Acl\Resource\Collection as ResourceCollection;
use LogikosTest\Access\Acl\TestCase;
use Phalcon\Acl\Adapter\Memory;
use PHPUnit\Framework\Assert;

class PhalconTest extends TestCase {

  const ROLES = ['admin', 'member', 'guest'];

  const PRIVILEGES = [
      'dashboard' => ['login', 'add-widget'],
      'reports'   => ['read', 'schedule']
  ];


  /** @var PhalconAcl */
  private $acl;

  public function setUp() {
    parent::setUp();
  }

  protected function resources() {
    $resources = [];
    foreach (self::PRIVILEGES as $resource=>$privileges) {
      array_push($resources, [
          'resource'   => $resource,
          'privileges' => $privileges
      ]);
    }
    return $resources;
  }

  public function testFoo() {
    $acl = new PhalconAcl();
    $acl->withResources(ResourceCollection::fromArray($this->resources()));
    $this->markTestSkipped('not done...');
  }

  public function testSimpleInheritedAllow() {
    $acl = $this->phalconAcl();
    $acl->allow('guest', 'dashboard', 'login');
    $acl->allow('member', 'dashboard', 'add-widget');
    $acl->addInherit('member', 'guest');
    Assert::assertTrue($acl->isAllowed('member', 'dashboard', 'login'));
  }

  public function testDeepInheritedAllow() {
    $acl = $this->phalconAcl();
    $acl->allow('guest', 'dashboard', 'login');
    $acl->allow('member', 'dashboard', 'add-widget');
    $acl->allow('admin', 'reports', ['read','schedule']);
    $acl->addInherit('member', 'guest');
    $acl->addInherit('admin', 'member');
    Assert::assertTrue($acl->isAllowed('admin', 'dashboard', 'login'));
  }

  public function testDeepInheritedAllow_reversedOrder() {
    $acl = $this->phalconAcl();
    $acl->addInherit('admin', 'member');
    $acl->addInherit('member', 'guest');
    $acl->allow('guest', 'dashboard', 'login');
    $acl->allow('member', 'dashboard', 'add-widget');
    $acl->allow('admin', 'reports', ['read','schedule']);
    Assert::assertTrue($acl->isAllowed('admin', 'dashboard', 'login'));
  }

  private function phalconAcl(): Memory {
    $acl = new Memory();

    foreach (self::PRIVILEGES as $resource => $privileges)
      $acl->addResource($resource, $privileges);

    foreach (self::ROLES as $role)
      $acl->addRole($role);

    return $acl;
  }
}