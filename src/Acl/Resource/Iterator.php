<?php


namespace Logikos\Access\Acl\Resource;

use Logikos\Access\Acl\Resource as ResourceInterface;

interface Iterator extends \Iterator {
  public function current(): ResourceInterface;
}