<?php


namespace Logikos\Access\Acl\Adapter;

use Logikos\Access\Acl;
use Logikos\Access\Acl\Config;
use Logikos\Access\Acl\Role;
use Logikos\Access\Acl\Rule;
use Logikos\Access\Acl\Resource;
use Logikos\Util\Config\InvalidConfigStateException;
use Phalcon\Acl\Adapter\Memory as PhalconAcl;

class Phalcon Implements Acl\Adapter {

  /** @var PhalconAcl */
  private $phalconAcl;

  /** @var Role\Collection|Role[] */
  private $roles;

  /** @var Resource\Collection|Resource[] */
  private $resources;

  /** @var Rule\Collection|Rule[] */
  private $rules;

  /** @var array of roleName => [A, B, C, ...] */
  private $inherits;

  public function __construct() {
    $this->phalconAcl = new PhalconAcl();
    $this->setDefaultAction(Acl::DENY);
  }

  public static function buildFromConfig(Config $config): Acl\Adapter {
    self::validateConfig($config);

    $self = new static();
    $self->setDefaultAction($config->defaultAction);

    self::loadRoles($self, $config);
    self::loadResources($self, $config);
    self::loadRules($self, $config);
    self::loadInherits($self, $config);

    return $self;
  }

  public function setDefaultAction($action) {
    $this->phalconAcl->setDefaultAction($action);
  }

  public function getDefaultAction() {
    return $this->phalconAcl->getDefaultAction();
  }

  public function isAllowed($role, $resource, $privilege) {
    return $this->phalconAcl->isAllowed($role, $resource, $privilege);
  }

  public function isRole($role) {
    return $this->phalconAcl->isRole($role);
  }

  public function getRoles(): Role\Collection {
    return $this->roles;
  }

  public function isResource($resource) {
    return $this->phalconAcl->isResource($resource);
  }

  public function getResources(): Resource\Collection {
    return $this->resources;
  }

  public function getRules(): Rule\Collection {
    return $this->rules;
  }

  public function getDirectGrantsForRole($role) {
    $grants = [];
    $roleName = (string) $role; // makes it compatable with RoleEntity object
    foreach ($this->rules as $rule) {
      if ($rule->access() == ACL::ALLOW && $rule->role() == $roleName) {
        $grants[] = [
            'resource'  => $rule->resource(),
            'privilege' => $rule->privilege()
        ];
      }
    }
    return $grants;
  }

  public function getGrantsForRole($role) {
    $grants = [];
    $roleName = (string) $role; // makes it compatable with RoleEntity object
    foreach ($this->resources as $resource) {
      foreach ($resource->privileges() as $privilege) {
        if ($this->isAllowed($roleName, $resource->name(), $privilege)) {
          $grants[] = [
              'resource' => $resource->name(),
              'privilege' => $privilege,
              'via' => $this->grantVia($roleName, $resource->name(), $privilege)
          ];
        }
      }
    }
    return $grants;
  }

  protected function grantVia($roleName, $resourceName, $privilege) {
    $via = [];
    $iRoles = $this->inherits[$roleName] ?? [];
    foreach ($iRoles as $iRole) {
      if ($this->isAllowed($iRole, $resourceName, $privilege)) {
        array_push($via, $iRole);
      }
    }
    return $via;
  }

  protected static function loadRoles(Phalcon $self, Config $config) {
    $self->roles = Role\Collection::fromArray($config->get('roles')->toArray());

    foreach ($self->roles as $r)
      $self->phalconAcl->addRole($r->name());
  }

  protected static function loadResources(Phalcon $self, Config $config) {
    $self->resources = Resource\Collection::fromArray($config->get('resources')->toArray());

    foreach ($self->resources as $r)
      $self->phalconAcl->addResource($r->name(), $r->privileges());
  }

  protected static function loadRules(Phalcon $self, Config $config) {
    $self->rules = $config->has('rules')
        ? Rule\Collection::fromArray($config->get('rules')->toArray())
        : Rule\Collection::fromArray([]);


    foreach ($self->rules as $r)
      $self->phalconAcl->allow($r->role(), $r->resource(), $r->privilege());
  }

  protected static function loadInherits(Phalcon $self, Config $config) {
    $roles = [];

    foreach ($config->inherits ?? [] as $i) {
      self::appendInherits($roles, $i->role(), $i->inherits());
    }

    foreach ($self->roles as $r) {
      self::appendInherits($roles, $r->name(), $r->inherits());
    }

    foreach ($roles as $role => $iRoles)
      foreach ($iRoles as $iRole)
        $self->phalconAcl->addInherit($role, $iRole);

    $self->inherits = $roles;
  }

  protected static function appendInherits(&$roles, $roleName, $inherits) {
    $roles[$roleName] = array_merge($roles[$roleName] ?? [], $inherits);
  }


  protected static function validateConfig(Config $config) {
    try {
      $config->validate();
      $config->lock();
    }
    catch (InvalidConfigStateException $e) {
      throw new Acl\InvalidAclConfig($e);
    }
  }
}