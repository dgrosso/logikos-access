<?php


namespace Logikos\Access\Acl\Entity;

use Logikos\Access\Acl\Entity;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Config\Field\OptionalField;
use Logikos\Util\Config\InvalidConfigStateException;

/**
 * Class Resource
 * @package Logikos\Access\Acl\Entity
 * @property string name
 * @property string description
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

  /** @throws InvalidConfigStateException */
  protected function initialize() {
    $this->addFields(
        new Field('name'),
        new OptionalField('description')
    );
    parent::initialize();
  }
}