<?php


namespace Logikos\Access\Acl\Resource;


interface Iterator extends \Iterator {
  public function current(): Resource;
}