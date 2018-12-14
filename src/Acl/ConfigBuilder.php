<?php


namespace Logikos\Access\Acl;


class ConfigBuilder {

  /** @var Role\CollectionBuilder */
  public $role;

  public function __construct() {
    $this->role = new Role\CollectionBuilder();
  }
}