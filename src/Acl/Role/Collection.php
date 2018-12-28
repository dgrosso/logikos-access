<?php


namespace Logikos\Access\Acl\Role;

use Logikos\Access\Acl\EntityCollection;
use Logikos\Access\Acl\Role as RoleInterface;

class Collection extends EntityCollection implements Iterator {

  public function current(): RoleInterface {
    $row = parent::current();
    return $this->buildEntity($row);
  }

  protected function buildEntity($row) {
    if (is_a($row, RoleInterface::class))
      return $row;

    return new Role([
        'name'        => $row['name'] ?? $row['role'],
        'description' => $row['description'] ?? '',
        'inherits'    => Role::makeInherits($row['inherits']    ?? [])
    ]);
  }
}