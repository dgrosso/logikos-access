<?php


namespace LogikosTest\Access;


use Nette\Database\Connection;
use Logikos\Util\Config\MutableConfig;

class Db extends Connection {

  /** @var DbConfig */
  private static $dbConfig;

  /** @var Db */
  private static $instance;

  public function __construct($dsn, $user = null, $password = null, array $options = null) {
    parent::__construct(
        $dsn,
        $user,
        $password,
        $options
    );
  }

  /**
   * @param  string $sql
   * @param  array  $binds
   * @return \PDOStatement
   * @throws \Exception
   */
  public function pdoQuery($sql, array $binds=[]) {
    return $this->_execute(
        $this->getPdo()->prepare($sql),
        $binds
    );
  }

  private function _bindParams(\PDOStatement $sth, $binds) {
    foreach ($binds as $name=>$value) {
      $sth->bindParam($this->bindParamName($name), $value);
    }
  }

  private function _execute(\PDOStatement $sth, array $binds=[]) {
    $this->_bindParams($sth, $binds);
    if ($sth->execute() === false) {
      $sql = $sth->queryString;
      $bindParams = json_encode($binds);
      throw new \Exception("Failed to execute {$sql} with binds {$bindParams}");
    }
    return $sth;
  }

  private function bindParamName($name) {
    return preg_match("/^:.+/", $name) ? $name : ":{$name}";
  }

  public function insert($table, array ...$rows) {
    $this->query("INSERT INTO {$table}", ...$rows);
    return $this->getInsertId();
  }

  public function deleteWhere($table, array $where) {
    return $this->query("DELETE FROM {$table} WHERE ?", $where);
  }

  public function selectWhere($table, $itemString, array $where) {
    return $this->query("SELECT {$itemString} FROM {$table} WHERE ?", $where);
  }

  public function selectFirst($table, $itemString, array $where) {
    return $this->selectWhere($table, $itemString, $where)->fetch();
  }

  public static function connection() {
    return self::$instance ?: self::$instance = self::newConnection();
  }

  public static function newConnection(array $options = null) {
    return new static(
        self::dsn(),
        self::$dbConfig->user,
        self::$dbConfig->pass,
        $options
    );
  }

  private static function dsn() {
    return sprintf(
        'mysql:dbname=%s;host=%s',
        self::dbConfig()->name,
        self::dbConfig()->host
    );
  }

  private static function sqliteDsn() {
    return sprintf(
        self::driver(),
        self::location()
    );
  }

  /**
   * @return DbConfig
   */
  private static function dbConfig() {
    return self::$dbConfig ?: self::$dbConfig = new DbConfig(AppConfig::dbConfig());
  }

  private static function conf() {
    return new MutableConfig([
        'host',

    ]);
  }

  private static function getenv($name, $default='') {
    return getenv($name) ?: $default;
  }

  private static function driver() {
    return self::getenv('DB_DRIVER', 'sqlite');
  }
  private static function location() {
    return self::getenv('DB_LOCATION', ':memory:');
  }
  private static function host() {
    return self::getenv('DB_HOST', 'localhost');
  }
}