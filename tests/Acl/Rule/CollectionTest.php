<?php


namespace LogikosTest\Access\Acl\Rule;

use Logikos\Access\Acl\Rule;
use LogikosTest\Access\Acl\TestCase;
use PHPUnit\Framework\Assert;

class CollectionTest extends TestCase {
  const DATA = [
      ['role' => 'admin', 'resource' => 'reports', 'privilege' => 'read'],
      ['role' => 'admin', 'resource' => 'reports', 'privilege' => 'schedule'],
      ['role' => 'admin', 'resource' => 'reports', 'privilege' => 'filter'],
      ['role' => 'member', 'resource' => 'reports', 'privilege' => 'read'],
  ];

  public function testBuildFromArray() {
    $data = self::DATA;
    $collection = Rule\Collection::fromArray($data);

    $this->assertCollectionContainsCorrectData($collection);
  }

  public function testWithArrayOfRules() {
    $data = array_map(
        function($row) { return new Rule\Rule($row); },
        self::DATA
    );
    $collection = Rule\Collection::fromArray($data);
    $this->assertCollectionContainsCorrectData($collection);
  }

  protected function assertCollectionContainsCorrectData(Rule\Collection $collection) {
    $found = [];
    /** @var Rule $rule */
    foreach ($collection as $rule) {
      Assert::assertInstanceOf(Rule\Rule::class, $rule);
      array_push($found, $rule->__toString());
    }

    Assert::assertCount(4, $found);
    Assert::assertContains('ALLOW:admin@reports!read', $found);
    Assert::assertContains('ALLOW:admin@reports!schedule', $found);
    Assert::assertContains('ALLOW:admin@reports!filter', $found);
    Assert::assertContains('ALLOW:member@reports!read', $found);
  }
}