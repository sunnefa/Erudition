<?php
$user_email = $_POST['user_email'];
        
$user_password = md5($_POST['user_password']);

$user_obj = new User($sql);

$logged_in = $user_obj->user_login($user_email, $user_password);
if($logged_in) {
    reload('home');
} else {
    $_SESSION['messages']['login_failed'] = 'Wrong username or password';
    $_SESSION['old_values']['email_address'] = $user_email;
    reload($_POST['referrer']);
}
?>