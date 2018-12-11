<?php


namespace Logikos\Access\Acl\Adapter;

use Logikos\Access\Acl\Resource\Iterator as ResourceIterator;
use Logikos\Access\Acl\RoleIterator;
use Phalcon\Acl\Adapter\Memory as MemoryAdapter;

class Phalcon {
  /** @var ResourceIterator */
  private $resources;

  /** @var RoleIterator */
  private $roles;

  public function withResources(ResourceIterator $resources) {
    $this->resources = $resources;
  }

  public function withRoles(RoleIterator $roles) {
    $this->roles = $roles;
  }
}