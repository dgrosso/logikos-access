<?php


namespace LogikosTest\Access\Acl\Entity;


use Logikos\Access\Acl\Entity\Rule;
use LogikosTest\Access\Acl\TestCase;

class RuleTest extends TestCase {
  public function testFoo() {
    $r = new Rule();
    $r->role();
    $r->resource();
    $r->privilege();
    $r->access();
    $this->assertFalse(true, 'start here');
  }

}