<?php


namespace Logikos\Access\Acl\Role;

use Logikos\Access\Acl\Role;

class CollectionBuilder {
  /** @var Role\Role[] */
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

  public function getRole($roleName): Role\Role {
    return $this->roles[$roleName];
  }

  private function makeRole($role) {
    if ($role instanceof Role) return $role;
    if (is_string($role)) return Role\Role::build($role);
    if (is_array($role)) {
      $name = $role['name'] ?? $role['role'];
      $inherits = array_key_exists('inherits', $role) ? $role['inherits'] : [];
      return Role\Role::build($name, $inherits);
    }
    return new Role\Role;
  }

  public function addRolesFromTraversable(\Traversable $roles) {
    $this->addRoles($roles);
  }

  public function addRolesFromArray($roles) {
    $this->addRoles($roles);
  }

  private function addRoles($roles) {
    foreach ($roles as $role) $this->addRole($role);
  }

  /**
   * @return \Logikos\Access\Acl\BaseCollection|Role[]
   */
  public function build() {
    return Role\Collection::fromArray($this->roles);
  }
}