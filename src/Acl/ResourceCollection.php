<?php

namespace Logikos\Access\Acl;

use Logikos\Access\Acl\Entity\Resource as ResourceEntity;

class ResourceCollection extends \IteratorIterator implements ResourceIterator {
  public static function buildFromPdoStatement(\PDOStatement $sth) {
    return new static($sth);
  }

  public function current(): Resource {
    $row = parent::current();
    $resource = new ResourceEntity([
        'name' => $row['resource'],
        'description' => $row['description'],
        'privileges' => explode(',',$row['privileges'])
    ]);
    /** @var Resource $resource */
    return $resource;
  }
}