<?php
/**
 * Created by PhpStorm.
 * User: galar
 * Date: 1/6/2017
 * Time: 9:55 AM
 */

include_once '../Global/MysqliDb.php';
include_once '../Global/constants.php';

$db = new MysqliDb ($DBServer, $DBUsername, $DBPassword, $DBName);


die; //so someone won't start this on mistake :)

$format =
    "
         INSERT IGNORE INTO DbMysql12.temp_table_1 
          (foursqaure_id, 
          name,address) 
          VALUES 
            (1,
            ?,?);
        ";

$cate = $db->rawQuery($format, [1,null]);

$cate = $db->rawQuery('select * from temp_table_1 where foursqaure_id = ? limit 5', [1]);
echo json_encode($cate);

$db->rawQuery("DELETE FROM temp_table_1 WHERE foursqaure_id = 1");

