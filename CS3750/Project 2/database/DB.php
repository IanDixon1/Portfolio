<?php
require_once("core/Config.php");

// class to handle all the database stuff
// everything is static so the connection will persist for the duration of the request
// bootstrap initializes the connection
class DB {
  protected static $db;

  // set up the database connection
  public static function init() {
    self::$db = new mysqli(Config::$dbHost, Config::$dbUser, Config::$dbPassword, Config::$dbSchema);

    if (self::$db->connect_error) {
      die("Database connection failed!<br />" . self::$db->connect_error);
    }
  }

  // get the id of the last inserted row
  // see: https://www.w3schools.com/php/php_mysql_insert_lastid.asp
  public static function lastID() {
    return self::$db->insert_id;
  }

  // execute the query as a prepared statement with the given parameters,
  // and return the result
  // see: https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php
  // $sql is the query that will be executed
  // $paramTypeString is the string defining the types of the parameters
  //     see types section of: https://www.php.net/manual/en/mysqli-stmt.bind-param.php
  // $params is an array of parameters to be bound to the query
  public static function execute($sql, $paramTypeString, $params) {
    $statement = self::$db->prepare($sql);
    $statement->bind_param($paramTypeString, ...$params);
    $statement->execute();
    $result = $statement->get_result();
    $statement->close();
    return $result;
  }

  // execute the query and return the result
  // do not use this if a prepared statement would be more appropriate.
  // if I see you concatenating stuff into your queries, you will receive a stern talking-to.
  public static function select($sql) {
    $result = self::$db->query($sql);
    return $result;
  }
}
?>
