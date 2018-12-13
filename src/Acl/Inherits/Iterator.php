<?php


namespace Logikos\Access\Acl\Inherits;

use Logikos\Access\Acl\Inherits as InheritsInterface;

interface Iterator extends \Iterator {
  public function current(): InheritsInterface;
}