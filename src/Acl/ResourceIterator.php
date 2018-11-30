<?php


namespace Logikos\Access\Acl;


interface ResourceIterator extends \Iterator {
  public function rewind();
  public function valid();
  public function current(): Resource;
  public function key();
  public function next();
}