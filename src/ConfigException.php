<?php


namespace Logikos\Access;

use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\StrictConfig;

class ConfigException extends RuntimeException {

  /** @var InvalidConfigStateException */
  protected $cse;

  public function __construct(InvalidConfigStateException $cse) {
    $this->cse = $cse;

    $msg = sprintf(
        "Failed to build %s\n%s",
        get_class($this->getConfig()),
        $cse->getMessagesAsYmlString()
    );

    parent::__construct($msg, 100, $this->cse);
  }

  public function getConfig(): StrictConfig {
    return $this->cse->getConfig();
  }

  public function getValidationMessages() {
    return $this->cse->getValidationMessages();
  }
}