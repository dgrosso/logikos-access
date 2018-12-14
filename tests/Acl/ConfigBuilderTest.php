<?php

namespace LogikosTest\Access\Acl;

use Logikos\Access\Acl\ConfigBuilder;
use Logikos\Access\Acl\Role;

class ConfigBuilderTest extends TestCase {

  /** @var ConfigBuilder */
  private $builder;

  public function setUp() {
    parent::setUp();
    $this->builder = new ConfigBuilder();
  }

  public function testInit() {
    $this->assertInstanceOf(ConfigBuilder::class, $this->builder);
  }
}
