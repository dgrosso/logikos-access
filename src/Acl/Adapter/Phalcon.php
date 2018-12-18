<?php


namespace Logikos\Access\Acl\Adapter;

use Logikos\Access\Acl;
use Logikos\Access\Acl\Config;
use Logikos\Access\Acl\Role;
use Logikos\Access\Acl\Resource;
use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\MutableConfig;
use Phalcon\Acl\Adapter\Memory as PhalconAcl;

class Phalcon Implements Acl\Adapter {

  /** @var PhalconAcl */
  private $phalconAcl;

  /** @var MutableConfig */
  private $ltAclConf;

  public function __construct() {
    $this->phalconAcl = new PhalconAcl();
    $this->setDefaultAction(Acl::DENY);
  }

  public function setDefaultAction($action) {
    $this->phalconAcl->setDefaultAction($action);
  }

  public function getDefaultAction() {
    return $this->phalconAcl->getDefaultAction();
  }

  public function isAllowed($role, $resource, $privilege) {
    return $this->phalconAcl->isAllowed($role, $resource, $privilege);
  }

  public function isRole($role) {
    return $this->phalconAcl->isRole($role);
  }

  public function isResource($resource) {
    return $this->phalconAcl->isResource($resource);
  }

  public function getResources(): Resource\Collection {
    $resources = $this->_config()->get('resources',[]);
    return Resource\Collection::fromArray(
        $resources instanceof \Logikos\Util\Config
            ? $resources->toArray()
            : $resources
    );
  }

  public static function buildFromConfig(Config $config): Acl\Adapter {
    self::validateConfig($config);

    $self = new static();
    $self->setDefaultAction($config->defaultAction);

    self::loadRoles($self, $config);
    self::loadResources($self, $config);
    self::loadRules($self, $config);
    self::loadInherits($self, $config);

    return $self;
  }

  protected function _config(): MutableConfig {
    return $this->ltAclConf ?: $this->ltAclConf = new MutableConfig();
  }

  protected static function loadRoles(Phalcon $self, Config $config) {
    $roles = [];
    $inherits = [];
    foreach ($config->roles as $r) {
      $roles[$r->name()] = $r;
      $self->phalconAcl->addRole($r->name());
    }
    $self->_config()->set('roles', $roles);
  }

  protected static function loadResources(Phalcon $self, Config $config) {
    $resources = [];
    foreach ($config->resources as $r) {
      $resources[$r->name()] = $r;
      $self->phalconAcl->addResource($r->name(), $r->privileges());
    }
    $self->_config()->set('resources', $resources);
  }

  protected static function loadRules(Phalcon $self, Config $config) {
    $rules = [];
    foreach ($config->rules as $r) {
      $rules[$r->__toString()] = $r;
      $self->phalconAcl->allow($r->role(), $r->resource(), $r->privilege());
    }
    $self->_config()->set('rules', $rules);
  }

  protected static function loadInherits(Phalcon $self, Config $config) {
    $inherits = [];
    if ($config->has('inherits')) {
      /** @var Acl\Inherits $i */
      foreach ($config->inherits as $i) {
        $iRoles = array_merge($inherits[$i->role()] ?? [], $i->inherits());
        $inherits[$i->role()] = $iRoles;
        foreach ($i->inherits() as $iRole)
          $self->phalconAcl->addInherit($i->role(), $iRole);
      }
    }

    /** @var Acl\Role $r */
    foreach ($self->_config()->roles as $r) {
      foreach ($r->inherits() as $iRole)
        $self->phalconAcl->addInherit($r->name(), $iRole);
    }
  }

  protected static function validateConfig(Config $config) {
    try {
      $config->validate();
      $config->lock();
    }
    catch (InvalidConfigStateException $e) {
      throw new Acl\InvalidAclConfig($e);
    }
  }
}