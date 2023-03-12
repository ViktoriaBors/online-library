<?php
namespace Viki\Api;

class Sql {
    protected static $connection;

    public function __construct()
    {
        self::$connection = new \mysqli(getenv('MYSQL_SRV'), getenv('MYSQL_USER'), getenv('MYSQL_PSW'), getenv('MYSQL_DB'));
    }

    public static function conn()
    {
        return self::$connection;
    }
}
?>


