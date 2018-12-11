<?php


namespace LogikosTest\Access\Acl\Rule;


use Logikos\Access\Acl;
use Logikos\Access\Acl\Rule;
use LogikosTest\Access\Acl\TestCase;
use PHPUnit\Framework\Assert;

class RuleTest extends TestCase {

  public function testImplementsInterface() {
    $r = new Rule\Rule();
    Assert::assertInstanceOf(Rule::class, $r);
  }

  public function testRequiredFields() {
    $r = new Rule\Rule();
    $this->assertFieldValidationFailed($r, 'role');
    $this->assertFieldValidationFailed($r, 'resource');
    $this->assertFieldValidationFailed($r, 'privilege');
  }

  public function testRuleAccessDefaultIsAllowed() {
    $r = new Rule\Rule();
    $this->assertEquals(Acl::ALLOW, $r->access());
  }

  public function testCanGetData() {
    $r = new Rule\Rule([
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