<?php


namespace Logikos\Access\Acl\Entity;


use Logikos\Access\Acl;
use Logikos\Access\Acl\Entity;
use Logikos\Access\Acl\Rule as RuleInterface;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Validation\Validator\Callback;

class Rule extends Entity implements RuleInterface {

  public function role() {
    return $this->get('role');
  }

  public function resource() {
    return $this->get('resource');
  }

  public function privilege() {
    return $this->get('privilege');
  }

  public function access() {
    return $this->get('access');
  }

  protected function defaults(): array {
    return [
        'access' => Acl::ALLOW
    ];
  }

  protected function initialize() {
    $this->addFields(
        new Field('role'),
        new Field('resource'),
        new Field('privilege'),
        Field::withValidators('access',
            new Callback(
                [$this, 'isValidAccess'],
                'Must pass Acl::ALLOW or Acl::DENY'
            )
        )
    );
  }

  public function isValidAccess($value) {
    return in_array($value, [Acl::ALLOW, Acl::DENY]);
  }
}