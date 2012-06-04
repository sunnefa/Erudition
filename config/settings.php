<?php
/**
 * This file defines some constants for database connection and paths and sets error reporting settings
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

/**
 * The MySQL database username
 */
define('MYSQL_USER', 'erudition');

/**
 * The MySQL hostname 
 */
define('MYSQL_HOST', 'localhost');

/**
 * The MySQL database name 
 */
define('MYSQL_DATA', 'erudition');

/**
 * The MySQL database password 
 */
define('MYSQL_PASS', 'erudition');

/**
 * The root directory 
 */
define('ROOT', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

/**
 * Turn error showing off 
 */
ini_set('display_errors', false);
/**
 * Set an error handler to log errors 
 */
set_error_handler('erudition_errors', E_ERROR);

?>