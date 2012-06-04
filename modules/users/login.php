<?php
/**
 * Controller for the login module
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */
//if the user is already logged in, show a table of the 6 latest users
if(is_logged_in()) {
    include 'show_erudites.php';
} else {
    //include the template for the login form
    ob_start();
    include ROOT . 'templates/users/login_form.html';
    //if the login failed before, certain values have been saved in the session that need to be showed
    $old_email = (isset($_SESSION['old_values']['email_address'])) ? $_SESSION['old_values']['email_address'] : '';
    $message = (isset($_SESSION['messages']['login_failed'])) ? $_SESSION['messages']['login_failed'] : '';
    $class = (isset($_SESSION['messages']['login_failed'])) ? 'error' : '';
    $tokens = array('MESSAGE_CLASS' => $class, 'MESSAGE' => $message, 'PAGE' => $_GET['page'], 'OLD_EMAIL' => $old_email);
    //replace the tokens
    $login_html = replace_tokens(ob_get_clean(), $tokens);
    echo $login_html;
}
//unset the session values
unset($_SESSION['messages']['login_failed']);
unset($_SESSION['old_values']['email_address']);
?>