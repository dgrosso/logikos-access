<?php


namespace LogikosTest\Access;


use Logikos\Database\Connection;
use Logikos\Database\DbConfig;

class Db extends Connection {

  /** @var DbConfig */
  private static $dbConfig;

  /** @var Connection */
  private static $connection;

  public static function connection() {
    return self::$connection ?: self::$connection = self::newConnection();
  }

  public static function newConnection(array $options = null) {
    return Connection::buildFromConfig(static::dbConfig(), $options);
  }

  public static function dbConfig() {
    return self::$dbConfig ?: self::$dbConfig = DbConfig::sqlite();
  }
}