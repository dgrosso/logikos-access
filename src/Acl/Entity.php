<?php


namespace Logikos\Access\Acl;

use Logikos\Access\Acl\InvalidEntityException;
use Logikos\Util\Config\ImmutableConfig;
use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\StrictConfig;

abstract class Entity extends StrictConfig {



  public function validate() {
    try {
      parent::validate();
    }
    catch (InvalidConfigStateException $e) {
      throw new InvalidEntityException($e);
    }
  }

  protected function initialize() {
    $this->validate();
    $this->lock();
  }

  protected function subConfig($arrayConfig = []) {
    return new ImmutableConfig($arrayConfig);
  }
}