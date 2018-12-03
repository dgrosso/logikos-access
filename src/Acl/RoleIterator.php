<?php


namespace Logikos\Access\Acl;


use Iterator;

interface RoleIterator extends Iterator {
  public function current(): Role;
}