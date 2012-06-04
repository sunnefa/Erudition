<?php
/**
 * Controller for the login process
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//the user's email
$user_email = $_POST['user_email'];
 
//the user's password
$user_password = md5($_POST['user_password']);

//the user object
$user_obj = new User($sql);

//log the user in
$logged_in = $user_obj->user_login($user_email, $user_password);
//if it work redirect to the home page
if($logged_in) {
    reload('home');
//if it didn't work, reload to the page that referred the login and save the email and an error message in the session
} else {
    $_SESSION['messages']['login_failed'] = 'Wrong username or password';
    $_SESSION['old_values']['email_address'] = $user_email;
    reload($_POST['referrer']);
}
?>