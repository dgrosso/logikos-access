<?php


namespace Logikos\Access\Acl;


use Logikos\Access\ConfigException;

class InvalidEntityException extends ConfigException {

  public function getEntity() {
    return $this->cse->getConfig();
  }
}