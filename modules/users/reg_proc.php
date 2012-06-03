<?php
$user = new User($sql);

$user_first_name = $_POST['first_name'];
$user_last_name = $_POST['last_name'];
$user_email = $_POST['email_address'];
$user_password = $_POST['password'];
$user_repeat_pass = $_POST['repeat_pass'];

$valid = array();

//do the passwords match
if($user_password != $user_repeat_pass) {
    $_SESSION['messages']['repeat_fail'] = "Passwords don't match";
    $valid[] = false;
} else {
    $valid[] = true;
}

//is the password strong
if(!password_is_strong($user_password)) {
    $_SESSION['messages']['pass_fail'] = "Password must be 8 characters or longer and contain at least 1 uppercase and 1 lowercase character";
    $valid[] = false;
} else {
    $valid[] = true;
}

//is it a valid email address
if(!is_email_address($user_email)) {
    $_SESSION['messages']['email_fail'] = 'That is not a valid email address';
    $valid[] = false;
} else {
    $valid[] = true;
}

//does this email already exist
if($user->email_exists($user_email)) {
    $_SESSION['messages']['email_fail'] = 'That email is already in use. Did you forget your password?';
    $valid[] = false;
} else {
    $valid[] = true;
}

$results = array_count_booleans($valid);

if($results['false'] > 0) {
    echo 'There are false values in this array';
    $_SESSION['messages']['reg_fail'] = 'Registration failed. Please correct all errors and try again.';
    $_SESSION['old_values'] = array(
        'first_name' => $user_first_name,
        'last_name' => $user_last_name,
        'email_address' => $user_email
    );
} else {
    $registered = $user->register_user($user_first_name, $user_last_name, $user_email, md5($user_password));
    if(!$registered) {
        $_SESSION['messages']['reg_fail'] = "Something went wrong with creatin your account. An admin has been notified.";
    } else {
        $_SESSION['messages']['reg_success'] = "Thank you for becoming an Erudite. Please check your email for instructions on how to activate your account";
    }
    
}
reload('signup');
?>