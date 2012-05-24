<?php
session_start();
ob_start();
require_once '../config/init.php';

$sql = new MySQLWrapper(MYSQL_USER, MYSQL_USER, MYSQL_USER, MYSQL_HOST);

require_once ROOT . 'controllers/core/main.php';
//var_dump($page_modules);
if(is_array($page_modules)) {
    foreach($page_modules as $module) {
        require_once ROOT . $module->module_path;
    }
}
ob_end_flush();

?>