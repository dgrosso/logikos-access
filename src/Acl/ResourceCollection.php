<?php

namespace Logikos\Access\Acl;

use Logikos\Access\Acl\Entity\Resource as ResourceEntity;

class ResourceCollection extends \IteratorIterator implements ResourceIterator {
  public static function buildFromPdoStatement(\PDOStatement $sth) {
    return new static($sth);
  }

  /**
   * @return Resource
   * @throws \Logikos\Util\CanNotMutateException
   */
  public function current(): Resource {
    $row = parent::current();
    $resource = new ResourceEntity([
        'name' => $row['resource'],
        'description' => $row['description']
    ]);
    /** @var Resource $resource */
    return $resource;
  }
}