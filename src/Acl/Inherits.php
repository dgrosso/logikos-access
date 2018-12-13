<?php


namespace Logikos\Access\Acl;


interface Inherits {
  public function role();
  public function inherits():array;
}