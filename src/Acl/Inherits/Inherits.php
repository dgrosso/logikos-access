<?php


namespace Logikos\Access\Acl\Inherits;


use Logikos\Access\Acl\Entity;
use Logikos\Access\Acl\Inherits as InheritsInterface;
use Logikos\Util\Config\Field\Field;

class Inherits extends Entity implements InheritsInterface {

  public function role() {
    return $this->get('role');
  }

  public function inherits():array {
    // this may be an array, a config object, a comma sep list as a string...
    $inherits = $this->get('inherits', []);
    if (is_array($inherits)) return $inherits;
    if (is_object($inherits)) return $inherits->toArray();
    return array_map('trim', explode(',', $inherits));
  }

  /**
   * @throws \Logikos\Util\Config\Field\InvalidFieldNameException
   */
  protected function initialize() {
    $this->addFields(
        new Field('role'),
        new Field('inherits')
    );
    parent::initialize();
  }
}