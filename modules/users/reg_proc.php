<?php
$user = new User($sql);

$user_first_name = $_POST['first_name'];
$user_last_name = $_POST['last_name'];
$user_email = $_POST['email_address'];
$user_password = md5($_POST['password']);
$user_repeat_pass = md5($_POST['repeat_pass']);

if($user_password != $user_repeat_pass) {
    $_SESSION['messages']['password'] = "Passwords don't match";
} else {
    $registered = $user->register_user($user_first_name, $user_last_name, $user_email, $user_password);
    if(!$registered) {
        $_SESSION['messages']['reg_fail'] = 'Registration failed, please try again';
    } else {
        $_SESSION['messages']['reg_success'] = 'You have successfully registered!';
    }
    reload('signup');
}

?>