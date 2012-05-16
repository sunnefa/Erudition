<?php

//is it a get request?
if(count($_GET) > 0) {
    require_once ROOT . 'controllers/core/get.php';
}

//is it a post request?
if($_SERVER['REQUEST_METHOD'] == 'post') {
    require_once ROOT . 'controllers/core/post.php';
}

?>