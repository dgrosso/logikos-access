<?php


namespace Logikos\Access\Acl;


interface Resource {
  public function name();
  public function description();
  public function privileges();
  public function __toString();
}