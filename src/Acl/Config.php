<?php


namespace Logikos\Access\Acl;


use Logikos\Access\Acl;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Config\Field\OptionalField;
use Logikos\Util\Config\StrictConfig;
use Logikos\Util\Validation\Validator;

/**
 * Class Config
 * @package Logikos\Access\Acl
 * @property Resource\Iterator|Resource[] $resources
 * @property Role\Iterator|Role[]         $roles
 * @property Rule\Iterator|Rule[]         $rules
 * @property Inherits\Iterator|Inherits[] $inherits
 * @property int                          $defaultAction
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

  public function withInherits(Inherits\Iterator $inherits) {
    $this->inherits = $inherits;
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
        OptionalField::withValidators(
            'rules',
            new Validator\IsInstanceOf(Rule\Iterator::class)
        ),
        OptionalField::withValidators(
            'inherits',
            new Validator\IsInstanceOf(Inherits\Iterator::class)
        ),
        new Field('defaultAction')
    );
  }
}