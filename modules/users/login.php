<?php

if(is_logged_in()) {
    include 'show_erudites.php';
} else {
    ob_start();
    include ROOT . 'templates/users/login_form.html';
    $old_email = (isset($_SESSION['old_values']['email_address'])) ? $_SESSION['old_values']['email_address'] : '';
    $message = (isset($_SESSION['messages']['login_failed'])) ? $_SESSION['messages']['login_failed'] : '';
    $class = (isset($_SESSION['messages']['login_failed'])) ? 'error' : '';
    $tokens = array('MESSAGE_CLASS' => $class, 'MESSAGE' => $message, 'PAGE' => $_GET['page'], 'OLD_EMAIL' => $old_email);
    $login_html = replace_tokens(ob_get_clean(), $tokens);
    echo $login_html;
}

unset($_SESSION['messages']['login_failed']);
unset($_SESSION['old_values']['email_address']);
?>