<?php


namespace Logikos\Access\Acl\Inherits;


use Logikos\Access\Acl\EntityCollection;
use Logikos\Access\Acl\Inherits as InheritsInterface;

class Collection extends EntityCollection implements Iterator {
  public function current(): InheritsInterface {
    $row = parent::current();
    return $this->buildEntity($row);
  }

  protected function buildEntity($row) {
    if (is_a($row, InheritsInterface::class))
      return $row;

    return new Inherits([
        'role' => $row['role'],
        'inherits' => $row['inherits']
    ]);
  }
}