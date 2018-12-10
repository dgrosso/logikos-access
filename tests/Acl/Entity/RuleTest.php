<?php


namespace LogikosTest\Access\Acl\Entity;


use Logikos\Access\Acl;
use Logikos\Access\Acl\Entity\Rule;
use LogikosTest\Access\Acl\TestCase;

class RuleTest extends TestCase {

  public function testRequiredFields() {
    $r = new Rule();
    $this->assertFieldValidationFailed($r, 'role');
    $this->assertFieldValidationFailed($r, 'resource');
    $this->assertFieldValidationFailed($r, 'privilege');
  }

  public function testRuleAccessDefaultIsAllowed() {
    $r = new Rule();
    $this->assertEquals(Acl::ALLOW, $r->access());
  }

  public function testCanGetData() {
    $r = new Rule([
        'role' => 'member',
        'resource' => 'reports',
        'privilege' => 'download'
    ]);

    $this->assertEquals('member', $r->role());
    $this->assertEquals('reports', $r->resource());
    $this->assertEquals('download', $r->privilege());
    $this->assertTrue($r->isValid());
  }

}