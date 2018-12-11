<?php


namespace Logikos\Access\Acl;


use Logikos\Access\Acl;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Config\StrictConfig;
use Logikos\Util\Validation\Validator;

/**
 * Class Config
 * @package Logikos\Access\Acl
 * @property Resource\Iterator $resources
 * @property Role\Iterator $roles
 * @property Rule\Iterator $rules
 * @property $defaultAction
 */
class Config extends StrictConfig {

  public function withRoles(Role\Iterator $roles) {
    $this->roles = $roles;
  }

  public function withResources(Resource\Iterator $resources) {
    $this->resources = $resources;
  }

  public function withRules(Rule\Iterator $rules) {
    $this->rules = $rules;
  }

  protected function defaults(): array {
    return [
      'defaultAction' => Acl::DENY
    ];
  }

  protected function initialize() {
    $this->initFields();
  }

  protected function initFields() {
    $this->addFields(
        Field::withValidators(
            'roles',
            new Validator\IsInstanceOf(Role\Iterator::class)
        ),
        Field::withValidators(
            'resources',
            new Validator\IsInstanceOf(Resource\Iterator::class)
        ),
        new Field('defaultAction')
    );
  }
}