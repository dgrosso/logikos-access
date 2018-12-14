<?php


namespace Logikos\Access\Acl\Rule;


use Logikos\Access\Acl;
use Logikos\Access\Acl\Rule as RuleInterface;
use Logikos\Access\Acl\Rule\Iterator as RuleIterator;
use Logikos\Access\Acl\Rule\Rule as RuleEntity;

class Collection extends Acl\BaseCollection implements RuleIterator {
  public function current(): RuleInterface {
    return $this->buildRule(parent::current());
  }

  private function buildRule($row) {
    if (is_a($row, RuleInterface::class))
      return $row;

    return new RuleEntity([
        'role' => $row['role'],
        'resource' => $row['resource'],
        'privilege' => $row['privilege'],
        'access' => $row['access'] ?? Acl::ALLOW
    ]);
  }
}