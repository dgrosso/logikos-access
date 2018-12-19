<?php

namespace Logikos\Access\Acl\Resource;

use Logikos\Access\Acl\EntityCollection;
use Logikos\Access\Acl\Resource as ResourceInterface;
use Logikos\Access\Acl\Resource\Resource as ResourceEntity;

class Collection extends EntityCollection implements Iterator {

  public function current(): ResourceInterface {
    $row = parent::current();
    return $this->buildEntity($row);
  }

  protected function buildEntity($row) {
    if ($row instanceof ResourceEntity)
      return $row;

    return new ResourceEntity([
        'name' => $row['resource'] ?? $row['name'],
        'description' => $row['description']??'',
        'privileges' => $this->getPrivileges($row)
    ]);
  }

  private function getPrivileges($row) {
    $k = 'privileges';
    if (!isset($row[$k])) return [];
    if (is_array($row[$k])) return $row[$k];
    return explode(',', $row['privileges']);
  }
}