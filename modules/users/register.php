<?php
if(!isset($_SESSION['user']) || isset($_COOKIE['user'])) {
    ob_start();
    include ROOT . 'templates/users/registration_form.html';
    $tokens = (isset($_SESSION['messages']['reg_fail'])) ? array('MESSAGE_CLASS' => '', 'MESSAGE' => $_SESSION['messages']['reg_fail']) : array('MESSAGE_CLASS' => 'invisible', 'MESSAGE' => '');
    echo replace_tokens(ob_get_clean(), $tokens);
} else {
    echo 'You are logged in, which means you are registered, so what are you doing here?';
}
?>