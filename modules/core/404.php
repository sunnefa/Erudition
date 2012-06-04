<?php
/**
 * The controller for the 404 module
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//send the appropriate header response
header('HTTP/1.0 404 Not Found');
//include the 404 template
include ROOT . 'templates/core/404.html';
?>