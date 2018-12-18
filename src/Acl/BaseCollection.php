<?php


namespace Logikos\Access\Acl;

use Iterator;
use Traversable;

abstract class BaseCollection extends \IteratorIterator implements Iterator {

  public function __construct(Traversable $iterator) {
    if (is_object($iterator) && $iterator instanceof \PDOStatement)
      parent::__construct(self::fromPdoStatement($iterator)->getInnerIterator());

    else
      parent::__construct($iterator);
  }

  public function toArray() {
    $rows = [];
    foreach ($this as $entity) {
      array_push($rows, $entity->toArray());
    }
    return $rows;
  }

  public static function fromPdoStatement(\PDOStatement $sth) {
    return self::fromArray(self::PdoSthToArray($sth));
  }

  public static function fromArray(array $array) {
    return new static(new \ArrayIterator($array));
  }

  protected static function PdoSthToArray(\PDOStatement $sth) {
    $rows = [];

    while ($row = $sth->fetch(\PDO::FETCH_ASSOC))
      array_push($rows, $row);

    return $rows;
  }
}