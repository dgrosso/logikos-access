<?php


namespace Logikos\Access\Acl\Role;

use Logikos\Access\Acl\EntityCollection;
use Logikos\Access\Acl\Role;

class Collection extends EntityCollection implements Role\Iterator {

  public function current(): Role {
    $row = parent::current();
    return $this->buildEntity($row);
  }

  protected function buildEntity($row) {
    if (is_a($row, Role::class))
      return $row;

    return new Role\Role([
        'name'        => $row['name'] ?? $row['role'],
        'description' => $row['description'] ?? '',
        'inherits'    => Role\Role::makeInherits($row['inherits']    ?? [])
    ]);
  }
}