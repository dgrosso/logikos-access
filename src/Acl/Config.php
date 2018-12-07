<?php


namespace Logikos\Access\Acl;


use Logikos\Access\Acl;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Config\StrictConfig;
use Logikos\Util\Validation\Validator;

/**
 * Class Config
 * @package Logikos\Access\Acl
 * @property RoleIterator $roles
 * @property ResourceIterator $resources
 * @property $defaultAction
 */
class Config extends StrictConfig {

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
            new Validator\IsInstanceOf(RoleIterator::class)
        ),
        Field::withValidators(
            'resources',
            new Validator\IsInstanceOf(ResourceIterator::class)
        ),
        new Field('defaultAction')
    );
  }
}