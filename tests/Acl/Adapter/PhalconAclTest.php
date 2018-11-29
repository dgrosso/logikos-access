<?php

namespace LogikosTest\Access\Acl\Adapter;

use Logikos\Access\Acl\Adapter\PhalconAcl;
use LogikosTest\Access\Acl\TestCase;

class PhalconAclTest extends TestCase {

  public function setUp() {
    parent::setUp();
    $this->loadFixtures();
  }

  protected function loadFixtures() {
    $this->addRoles('admin', 'member', 'guest');
    $this->addResources('dashboard', 'reports', 'members');
    $this->addPrivileges('dashboard', 'login');
    $this->addPrivileges('reports', 'view', 'filter', 'schedule');
    $this->addPrivileges('members', 'view', 'add', 'edit', 'suspend');
    $this->addGrant('member', 'dashboard', 'login');
  }

  public function testCanWriteAndReadFromDb() {
    $r = $this->db->selectFirst('roles', 'role', ['role'=>'admin']);
    $this->assertEquals('admin', $r['role']);
  }


}
