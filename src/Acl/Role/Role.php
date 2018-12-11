<?php


namespace Logikos\Access\Acl\Role;

use Logikos\Access\Acl\Entity;
use Logikos\Access\Acl\Role as RoleInterface;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Config\Field\OptionalField;
use Logikos\Util\Config\InvalidConfigStateException;


class Role extends Entity implements RoleInterface {

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