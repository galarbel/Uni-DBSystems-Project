<?php 

include_once 'constants.php';
include_once 'funcs.php';
include_once 'ezsql_core.php';
include_once 'ezsql_mysql.php';

$db = new ezSQL_mysql($DBUsername,$DBPassword,$DBName,$DBServer);

?>