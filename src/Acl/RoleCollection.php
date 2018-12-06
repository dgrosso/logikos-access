<?php


namespace Logikos\Access\Acl;

use Logikos\Access\Acl\Entity\Role as RoleEntity;

class RoleCollection extends \IteratorIterator implements RoleIterator {

  public static function fromPdoStatement(\PDOStatement $sth) {
    return new static($sth);
  }

  public static function fromArray(array $array) {
    return new static(new \ArrayIterator($array));
  }

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