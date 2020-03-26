<?php

namespace DataSource;

use PDO;

class Database
{
  static function genPdo(): PDO
  {
    $host = getenv('MYSQL_HOST');
    $password = getenv('MARIADB_ROOT_PASSWORD');
    $dbname = getenv('MYSQL_DATABASE');
    $db = new PDO("mysql:dbname=$dbname;host=$host", 'root', $password);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $db;
  }

  static function fetch($table, $args, $whereTexts, $limit = null)
  {
    $param = [];
    $whereList = [];
    $where = function ($name, $format = "%s=:%s", $decorator = "%s")
    use ($args, &$param, &$whereList) {
      if (array_key_exists($name, $args)) {
        $whereList[] = sprintf($format, $name, $name);
        $param[$name] = sprintf($decorator, $args[$name]);
      }
    };

    foreach ($whereTexts as $whereText) {
      if (count($whereText) > 1) {
        $where($whereText[0], $whereText[1], $whereText[2]);
      } else {
        $where($whereText[0]);
      }
    }
    $sql = "select * from $table" . (empty($whereList) ? '' : ' where ' . join(' and ', $whereList)) . ($limit ? " limit $limit" : '');

    $dbh = Database::genPdo();
    $prepare = $dbh->prepare($sql);
    $prepare->execute($param);

    return $prepare->fetchAll(PDO::FETCH_ASSOC);
  }
}
