<?php
session_start();
ob_start();
require_once '../config/init.php';

$sql = new MySQLWrapper(MYSQL_DATA, MYSQL_USER, MYSQL_PASS, MYSQL_HOST);
require_once ROOT . 'controllers/core/main.php';
if(is_array($page_modules)) {
    foreach($page_modules as $module) {
        require_once ROOT . $module->module_path;
    }
}
ob_end_flush();

?>