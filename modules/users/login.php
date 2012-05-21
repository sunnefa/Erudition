<?php

if(isset($_SESSION['user']) || isset($_COOKIE['user'])) {
    echo 'You are already logged in';
    //include the template for the newest users thingy here
} else {
    ob_start();
    include ROOT . 'templates/users/login_form.html';
    $tokens = (isset($_SESSION['messages']['login_failed'])) ? array('MESSAGE_CLASS' => 'error', 'MESSAGE' => $_SESSION['messages']['login_failed']) : array('MESSAGE_CLASS' => '', 'MESSAGE' => '');
    $login_html = replace_tokens(ob_get_clean, $tokens);
    echo $login_html;
}

?>