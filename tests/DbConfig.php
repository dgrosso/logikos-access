<?php


namespace LogikosTest\Access;

use Logikos\Util\Config\Field;
use Logikos\Util\Config\StrictConfig;

/**
 * @property string  $host
 * @property string  $user
 * @property string  $pass
 * @property string  $name
 * @property integer $port
 * @property mixed   driver
 * @property mixed   location
 */
class DbConfig extends StrictConfig {
  const DEFAULT_MYSQL_PORT      = 3306;
  const DEFAULT_PG_PORT         = 5432;
  const DEFAULT_SQLITE_LOCATION = ':memory:';

  public function initialize() {
    $this->addFields(
        new Field\Field('driver'),
        new Field\OptionalField('location'), // only used with sqlite driver
        new Field\Field('host'),
        new Field\Field('user'),
        new Field\Field('pass'),
        new Field\Field('name'),
        new Field\OptionalField('port')
    );
  }

  public static function sqlite($location = self::DEFAULT_SQLITE_LOCATION) {
    return new self([
        'driver' => 'sqlite',
        'location' => $location
    ]);
  }

  public static function mysql($host, $name, $user, $pass, $port=self::DEFAULT_MYSQL_PORT) {
    return new self([
        'driver' => 'mysql',
        'host'   => $host,
        'name'   => $name,
        'user'   => $user,
        'pass'   => $pass,
        'port'   => $port
    ]);
  }

  public function dsn() {
    if ($this->isSqlite()) {
      return $this->sqliteDsn();
    }
    return $this->otherDsn();
  }

  public function isSqlite(): bool {
    return substr($this->driver, 0, 6) == 'sqlite';
  }

  private function sqliteDsn() {
    return sprintf(
        "%s:%s",
        $this->driver,
        $this->location
    );
  }

  private function otherDsn() {
    return sprintf(
        "%s:host=%s;port=%s;dbname=%s",
        $this->driver,
        $this->host,
        $this->port,
        $this->name
    );
  }

}