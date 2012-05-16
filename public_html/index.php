<?php

require_once '../config/init.php';

$sql = new MySQLWrapper(MYSQL_USER, MYSQL_USER, MYSQL_USER, MYSQL_HOST);



include '../controllers/core/pages.php';

?>