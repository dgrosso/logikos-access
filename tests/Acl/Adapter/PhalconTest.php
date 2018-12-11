<?php


namespace LogikosTest\Access\Acl\Adapter;


use Logikos\Access\Acl\Adapter\Phalcon as Acl;
use Logikos\Access\Acl\Config as AclConfig;
use Logikos\Access\Acl\Resource;
use Logikos\Access\Acl\Role;
use Logikos\Access\Acl\Rule;
use Logikos\Util\Config\InvalidConfigStateException;
use LogikosTest\Access\Acl\TestCase;
use Phalcon\Acl\Adapter\Memory as PhalconAcl;
use PHPUnit\Framework\Assert;

class PhalconTest extends TestCase {


  /** @var Acl */
  private $acl;

  public function setUp() {
    parent::setUp();
  }

  public function testBuildFromInvalidConfig() {
    $this->expectException(InvalidConfigStateException::class);
    Acl::buildFromConfig(new AclConfig());
  }

  public function testBuildFromConfig() {

    $config = new AclConfig([
        'roles' => Role\Collection::fromArray(self::ROLES),
        'resources' => Resource\Collection::fromArray(self::RESOURCES),
        'rules' => Rule\Collection::fromArray(self::RULES)
    ]);

    $acl = Acl::buildFromConfig($config);
    Assert::assertInstanceOf(Acl::class, $acl);
    Assert::assertInstanceOf(PhalconAcl::class, $acl);

    foreach (self::ROLES as $r)
      Assert::assertTrue($acl->isRole($r['role']), "Role {$r['role']} was not loaded...");


    foreach (self::RESOURCES as $r)
      Assert::assertTrue($acl->isResource($r['resource']), "Resource {$r['resource']} was not loaded...");

    $this->markTestSkipped("rules?");

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
}