<?php
/**
 * Index.php - All requests go through here
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */
//start a session
session_start();
//start output buffering
ob_start();
//include the init
require_once '../config/init.php';
//make a new Database wrapper object - this object is reused throughout the script
$sql = new MySQLWrapper(MYSQL_DATA, MYSQL_USER, MYSQL_PASS, MYSQL_HOST);
//include the main controller
require_once ROOT . 'controllers/core/main.php';
//loop through the page modules loaded by the get controller
if(is_array($page_modules)) {
    foreach($page_modules as $module) {
        //include the module
        require_once ROOT . $module->module_path;
    }
}
//flush the buffer
ob_end_flush();

?>