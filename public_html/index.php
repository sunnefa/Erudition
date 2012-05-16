<?php

require_once '../config/init.php';

$sql = new MySQLWrapper(MYSQL_USER, MYSQL_USER, MYSQL_USER, MYSQL_HOST);

if(isset($_REQUEST['page'])) {
    
}

include '../controllers/core/pages.php';

?>