<?php


namespace Logikos\Access\Acl;


interface Adapter {

  public static function buildFromConfig(Config $config): Adapter;

  public function getDefaultAction();

  public function isAllowed($role, $resource, $privilege);
  public function isRole($role);
  public function isResource($resource);
}