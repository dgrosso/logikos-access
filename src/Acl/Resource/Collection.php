<?php

namespace Logikos\Access\Acl\Resource;

use Logikos\Access\Acl\Resource\Resource as ResourceEntity;
use Logikos\Access\Acl\Collection as BaseCollection;

class Collection extends BaseCollection implements Iterator {

  public function current(): Resource {
    $row = parent::current();
    $resource = new ResourceEntity([
        'name' => $row['resource'],
        'description' => $row['description']??'',
        'privileges' => $this->getPrivileges($row)
    ]);
    /** @var Resource $resource */
    return $resource;
  }

  private function getPrivileges($row) {
    $k = 'privileges';
    if (!isset($row[$k])) return [];
    if (is_array($row[$k])) return $row[$k];
    return explode(',', $row['privileges']);
  }
}