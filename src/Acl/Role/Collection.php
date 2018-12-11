<?php


namespace Logikos\Access\Acl\Role;

use Logikos\Access\Acl\Collection as BaseCollection;
use Logikos\Access\Acl\Role\Role as RoleEntity;
use Logikos\Access\Acl\Role;
use Logikos\Access\Acl\Role\Iterator as RoleIterator;

class Collection extends BaseCollection implements RoleIterator {

  public function current(): Role {
    $row = parent::current();
    $r = new RoleEntity([
        'name' => $row['role'],
        'description' => $row['description']??''
    ]);
    /** @var Role $r */
    return $r;
  }
}