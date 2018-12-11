<?php


namespace Logikos\Access\Acl\Resource;

use Logikos\Access\Acl\Entity;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Config\Field\OptionalField;
use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Validation\Validator;

/**
 * Class Resource
 * @package Logikos\Access\Acl\Entity
 * @property string name
 * @property string description
 * @property array
 *  privileges
 */
class Resource extends Entity implements \Logikos\Access\Acl\Resource {

  public function __toString():string {
    return $this->name();
  }

  public function name() {
    return $this->get('name');
  }

  public function description() {
    return $this->get('description', null);
  }

  public function privileges() {
    return $this->get('privileges', [])->toArray();
  }

  /** @throws InvalidConfigStateException */
  protected function initialize() {
    $this->addFields(
        new Field('name'),
        new OptionalField('description'),
        OptionalField::withValidators(
            'privileges',
            new Validator\IsIterable('Must be iterable')
        )
    );
    parent::initialize();
  }
}