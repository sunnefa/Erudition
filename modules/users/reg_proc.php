<?php
$user = new User($sql);

$user_first_name = $_POST['first_name'];
$user_last_name = $_POST['last_name'];
$user_email = $_POST['email_address'];
$user_password = md5($_POST['password']);
$user_repeat_pass = md5($_POST['repeat_pass']);

if($user_password != $user_repeat_pass) {
    $_SESSION['messages']['repeat_fail'] = "Passwords don't match";
    $_SESSION['messages']['reg_fail'] = 'Registration failed. Please correct all errors and try again.';
} elseif(!password_is_strong($user_password)) {
    $_SESSION['messages']['pass_fail'] = "Password must be 8 characters or longer and contain at least 1 uppercase and 1 lowercase character";
    $_SESSION['messages']['reg_fail'] = 'Registration failed. Please correct all errors and try again.';
} elseif(!is_email_address($user_email)) {
    $_SESSION['messages']['email_fail'] = 'That is not a valid email address';
    $_SESSION['messages']['reg_fail'] = 'Registration failed. Please correct all errors and try again.';
} else {
    $registered = $user->register_user($user_first_name, $user_last_name, $user_email, $user_password);
    if(!$registered) {
        $_SESSION['messages']['reg_fail'] = 'Registration failed. Please correct all errors and try again.';
    } else {
        $_SESSION['messages']['reg_success'] = 'You have successfully registered!';
    }
    
}
reload('signup');
?>