<?php
include_once "ezsql/ez_sql_core.php";
include_once "ezsql/ez_sql_mysql.php";

$db = new ezSQL_mysql('root','root','wordpress1','127.0.0.1');

$my_tables = $db->get_results("select * from wp1_comments");

foreach ($my_tables as $k) { 
    echo($k->comment_author);
    echo("<br/>"); 
} 