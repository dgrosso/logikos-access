<?php


namespace Logikos\Access\Acl\Entity;

use Logikos\Access\Acl\Resource as ResourceInterface;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Config\Field\OptionalField;
use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\StrictConfig;

/**
 * Class Resource
 * @package Logikos\Access\Acl\Entity
 * @property string name
 * @property string description
 */
class Resource extends StrictConfig implements ResourceInterface {

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
    $this->validate();
    $this->lock();
  }
}