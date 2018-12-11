<?php


namespace LogikosTest\Access\Acl;

use Logikos\Database\Connection;
use LogikosTest\Access\Db;
use LogikosTest\Access\TestCase as AccessTestCase;
use Nette\Database\Helpers as DbHelpers;

class TestCase extends AccessTestCase {

  const ROLES = [
      ['role' => 'admin'],
      ['role' => 'member'],
      ['role' => 'guest']
  ];

  const RESOURCES = [
      ['resource' => 'dashboard', 'privileges' => ['login','add-widget']],
      ['resource' => 'reports',   'privileges' => ['read','schedule']]
  ];

  const RULES = [
      ['role' => 'admin',  'resource' => 'reports', 'privilege' => 'read'],
      ['role' => 'admin',  'resource' => 'reports', 'privilege' => 'schedule'],
      ['role' => 'member', 'resource' => 'reports', 'privilege' => 'read'],
  ];

  /** @var Connection */
  protected $db;

  public function setUp() {
    $this->db = Db::connection();
    DbHelpers::loadFromFile($this->db, self::DIR_TESTS.'/aclschema.sql');
  }

  protected function addRoles(...$roles) {
    foreach ($roles as $r) {
      $row = ['role' => $r];
      $this->db->insert('roles', $row);
    }
  }

  protected function addResources(...$resources) {
    foreach ($resources as $r) {
      $row = ['resource' => $r];
      $this->db->insert('resources', $row);
    }
  }

  protected function addPrivileges($resource, ...$privileges) {
    foreach ($privileges as $r) {
      $row = ['resource' => $resource, 'privilege' => $r];
      $this->db->insert('privileges', $row);
    }
  }

  protected function addGrant($role, $resource, ...$privileges) {
    foreach ($privileges as $r) {
      $row = ['role' => $role, 'resource' => $resource, 'privilege' => $r];
      $this->db->insert('role_privileges', $row);
    }
  }

  protected function inheritRoles($role, $inheritedRoles) {
    foreach ($inheritedRoles as $r) {
      $row = ['role' => $role, 'role_inherit' => $r];
      $this->db->insert('role_inherits', $row);
    }
  }
}