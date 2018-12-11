<?php

namespace Logikos\Access\Acl\Resource;

use Logikos\Access\Acl\Collection as BaseCollection;
use Logikos\Access\Acl\Resource as ResourceInterface;
use Logikos\Access\Acl\Resource\Resource as ResourceEntity;

class Collection extends BaseCollection implements Iterator {

  public function current(): ResourceInterface {
    $row = parent::current();
    $resource = new ResourceEntity([
        'name' => $row['resource'],
        'description' => $row['description']??'',
        'privileges' => $this->getPrivileges($row)
    ]);
    return $resource;
  }

  private function getPrivileges($row) {
    $k = 'privileges';
    if (!isset($row[$k])) return [];
    if (is_array($row[$k])) return $row[$k];
    return explode(',', $row['privileges']);
  }
}