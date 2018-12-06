<?php

namespace Logikos\Access\Acl;

use Logikos\Access\Acl\Entity\Resource as ResourceEntity;

class ResourceCollection extends \IteratorIterator implements ResourceIterator {
  public static function fromPdoStatement(\PDOStatement $sth) {
    return new static($sth);
  }

  public static function fromArray(array $array) {
    return new static(new \ArrayIterator($array));
  }

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