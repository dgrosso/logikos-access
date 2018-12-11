<?php


namespace Logikos\Access\Acl\Rule;

use Logikos\Access\Acl\Rule as RuleInterface;

interface Iterator extends \Iterator {
  public function current(): RuleInterface;
}