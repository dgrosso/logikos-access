<?php


namespace Logikos\Access\Acl\Role;

use Logikos\Access\Acl\Role;

class CollectionBuilder {
  /** @var Role[] */
  private $roles = [];

  public function addRole($role) {
    $roleObject = $this->makeRole($role);
    $roleObject->validate();
    $this->roles[$roleObject->name()] = $roleObject;
  }

  public function addRoleWithInherits($roleName, $inherits) {
    $this->roles[$roleName] = Role\Role::build($roleName, $inherits);
  }

  public function addInherit($roleName, $inheritRoleName) {
    $role = $this->roles[$roleName];
    $inherits = $role->inherits();
    array_push($inherits, $inheritRoleName);
    $this->roles[$roleName] = Role\Role::build($roleName, $inherits);
  }

  public function roles() {
    return $this->roles;
  }

  public function hasRole($roleName) {
    return array_key_exists($roleName, $this->roles);
  }

  public function getRole($roleName) {
    return $this->roles[$roleName];
  }

  private function makeRole($role) {
    if ($role instanceof Role) return $role;
    if (is_string($role)) return Role\Role::build($role);
    if (is_array($role)) return new Role\Role($role);
    return new Role\Role;
  }

  public function addRolesFromTraversable(\Traversable $iterator) {
    $c = new Role\Collection($iterator);
    foreach ($c as $role) $this->addRole($role);
  }
}