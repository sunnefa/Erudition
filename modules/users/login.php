<?php

if(isset($_SESSION['user']) || isset($_COOKIE['user'])) {
    include 'show_erudites.php';
} else {
    ob_start();
    include ROOT . 'templates/users/login_form.html';
    $tokens = (isset($_SESSION['messages']['login_failed'])) ? array('MESSAGE_CLASS' => 'error', 'MESSAGE' => $_SESSION['messages']['login_failed']) : array('MESSAGE_CLASS' => '', 'MESSAGE' => '');
    $login_html = replace_tokens(ob_get_clean(), $tokens);
    echo $login_html;
}

unset($_SESSION['messages']['login_failed']);

?>