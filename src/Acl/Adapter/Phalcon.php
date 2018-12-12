<?php


namespace Logikos\Access\Acl\Adapter;

use Logikos\Access\Acl;
use Logikos\Access\Acl\Config;
use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\MutableConfig;
use Phalcon\Acl\Adapter\Memory as PhalconAcl;

class Phalcon extends PhalconAcl {

  /** @var MutableConfig */
  private $ltAclConf;

  public function __construct() {
    parent::__construct();
    $this->setDefaultAction(Acl::DENY);
  }

  protected function _config(): MutableConfig {
    return $this->ltAclConf ?: $this->ltAclConf = new MutableConfig();
  }

  public static function buildFromConfig(Config $config) {
    self::validateConfig($config);

    $self = new static();
    $self->setDefaultAction($config->defaultAction);

    self::loadRoles($self, $config);
    self::loadResources($self, $config);
    self::loadRules($self, $config);

    return $self;
  }

  private static function loadRoles(Phalcon $self, Config $config) {
    $roles = [];
    foreach ($config->roles as $r) {
      $roles[$r->name()] = $r;
      $self->addRole($r->name());
    }
    $self->_config()->set('roles', $roles);
  }

  protected static function loadResources(Phalcon $self, Config $config) {
    $resources = [];
    foreach ($config->resources as $r) {
      $resources[$r->name()] = $r;
      $self->addResource($r->name(), $r->privileges());
    }
    $self->_config()->set('resources', $resources);
  }

  protected static function loadRules(Phalcon $self, Config $config) {
    $rules = [];
    foreach ($config->rules as $r) {
      $rules[$r->__toString()] = $r;
      $self->allow($r->role(), $r->resource(), $r->privilege());
    }
    $self->_config()->set('rules', $rules);
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