<?php


namespace Logikos\Access\Acl\Role;

use Logikos\Access\Acl\Role as RoleInterface;

interface Iterator extends \Iterator {
  public function current(): RoleInterface;
}