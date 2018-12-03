<?php


namespace Logikos\Access\Acl;


interface ResourceIterator extends \Iterator {
  public function current(): Resource;
}