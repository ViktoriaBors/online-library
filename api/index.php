<?php
namespace Viki\Api;
use Viki\Api\Models\Sql;

require 'vendor/autoload.php';
require "src/Router.php";

define("MYSQL_SRV", getenv('MYSQL_SRV'));
define("MYSQL_USER", getenv('MYSQL_USER'));
define("MYSQL_PSW", getenv('MYSQL_PSW'));
define("MYSQL_DB", getenv('MYSQL_DB'));

?>
