<?php


namespace Logikos\Access\Acl;

use Logikos\Access\Acl\Entity\Role as RoleEntity;

class RoleCollection extends Collection implements RoleIterator {

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