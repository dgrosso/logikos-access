<?php


namespace Logikos\Access\Acl\Role;

use Logikos\Access\Acl\Entity;
use Logikos\Access\Acl\Role as RoleInterface;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Config\Field\OptionalField;


class Role extends Entity implements RoleInterface {

  public static function build($name, $inherits=null): Role {
    $self = new static([
        'name' => $name,
        'inherits' => self::makeInherits($inherits)
    ]);

    return $self;
  }

  public static function makeInherits($inherits): array {
    if (is_array($inherits)) return $inherits;
    if (is_string($inherits)) return explode(',', $inherits);
    return [];
  }

  public function __toString():string {
    return $this->name();
  }

  public function name() {
    return $this->get('name');
  }

  public function description() {
    return $this->get('description', null);
  }

  public function inherits() {
    return array_unique($this->inherits->toArray());
  }

  protected function initialize() {
    $this->addFields(
        new Field('name'),
        new OptionalField('description'),
        new OptionalField('inherits')
    );
    parent::initialize();
  }

  protected function defaults(): array {
    return [
        'inherits' => []
    ];
  }
}