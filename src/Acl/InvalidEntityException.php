<?php


namespace Logikos\Access\Acl;


use Logikos\Access\RuntimeException;
use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\StrictConfig;

class InvalidEntityException extends RuntimeException {

  /** @var InvalidConfigStateException */
  private $cse;

  public function __construct(StrictConfig $config, InvalidConfigStateException $cse) {
    $this->cse = $cse;

    $msg = sprintf(
        "Failed to build %s\n%s",
        get_class($config),
        $cse->getMessagesAsYmlString()
    );

    parent::__construct($msg, 100, $this->cse);
  }

  public function getEntity() {
    return $this->cse->getConfig();
  }
}