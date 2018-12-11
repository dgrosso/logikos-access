<?php


namespace Logikos\Access\Acl;


interface Rule {
  public function role();
  public function resource();
  public function privilege();
  public function access();
  public function __toString();
}