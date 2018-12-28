<?php


namespace LogikosTest\Access\Acl\Adapter;


use Logikos\Access\Acl\Adapter;
use Logikos\Access\Acl\Adapter\Phalcon as Acl;
use Logikos\Access\Acl\Config;
use Phalcon\Acl\Adapter\Memory as PhalconAcl;
use PHPUnit\Framework\Assert;

class PhalconTest extends TestCase {

  public function setUp() {
    parent::setUp();
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



  private function phalconAcl(): PhalconAcl {
    $acl = new PhalconAcl();

    foreach (self::RESOURCES as $resource)
      $acl->addResource($resource['resource'], $resource['privileges']);

    foreach (self::ROLES as $role)
      $acl->addRole($role['role']);

    return $acl;
  }

  protected function acl(Config $config=null): Adapter {
    $acl = Acl::buildFromConfig($config ?: $this->AclConfig());
    return unserialize(serialize($acl)); // ensures the unserialized object works as the original
  }
}