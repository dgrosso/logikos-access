<?php


namespace Logikos\Access\Acl\Role;

use Logikos\Access\Acl\BaseCollection;
use Logikos\Access\Acl\Role;

class Collection extends BaseCollection implements Role\Iterator {

  public function current(): Role {
    $row = parent::current();
    $r = new Role\Role([
        'name'        => $row['role'],
        'description' => $row['description'] ?? '',
        'inherits'    => Role\Role::makeInherits($row['inherits']    ?? [])
    ]);
    /** @var Role $r */
    return $r;
  }
}