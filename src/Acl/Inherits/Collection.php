<?php


namespace Logikos\Access\Acl\Inherits;


use Logikos\Access\Acl\BaseCollection as BaseCollection;
use Logikos\Access\Acl\Inherits as InheritsInterface;

class Collection extends BaseCollection implements Iterator {
  public function current(): InheritsInterface {
    $row = parent::current();
    $r = new Inherits([
        'role' => $row['role'],
        'inherits' => $row['inherits']
    ]);
    return $r;
  }
}