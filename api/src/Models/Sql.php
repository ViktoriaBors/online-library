<?php
namespace Viki\Api\Models;

class Sql {
    protected static $connection;

    public static function conn()
    {
        return self::$connection = new \mysqli(getenv('MYSQL_SRV'), getenv('MYSQL_USER'), getenv('MYSQL_PSW'), getenv('MYSQL_DB'));
    }
}
?>


