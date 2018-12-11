<?php


namespace Logikos\Access\Acl;

use Iterator;

abstract class Collection extends \IteratorIterator implements Iterator {

  public static function fromPdoStatement(\PDOStatement $sth) {
    return new static($sth);
  }

  public static function fromArray(array $array) {
    return new static(new \ArrayIterator($array));
  }
}