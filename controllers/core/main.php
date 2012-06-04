<?php
/**
 * The main controller, delegates control to post or get
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//is it a get request?
if(count($_GET) > 0) {
    require_once ROOT . 'controllers/core/get.php';
}

//is it a post request?
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once ROOT . 'controllers/core/post.php';
}

?>