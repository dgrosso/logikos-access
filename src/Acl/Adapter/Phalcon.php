<?php


namespace Logikos\Access\Acl\Adapter;

use Logikos\Access\Acl\Config;
use Logikos\Access\Acl\Resource\Iterator as ResourceIterator;
use Logikos\Access\Acl\Role\Iterator as RoleIterator;
use Phalcon\Acl\Adapter\Memory as PhalconAcl;

class Phalcon extends PhalconAcl {
  /** @var ResourceIterator */
  private $resources;

  /** @var RoleIterator */
  private $roles;

  public static function buildFromConfig(Config $config) {
    $config->validate();
    $self = new static();
    foreach ($config->roles as $r)
      $self->addRole($r->name());

    foreach ($config->resources as $r) {
      $self->addResource($r->name(), $r->privileges());
    }

    return $self;
  }

  public function withResources(ResourceIterator $resources) {
    $this->resources = $resources;
  }

  public function withRoles(RoleIterator $roles) {
    $this->roles = $roles;
  }
}