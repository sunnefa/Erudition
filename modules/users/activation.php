<?php
$activation_key = $_GET['activation'];

$user = new User($sql);

$activate = $user->activate_user($activation_key);

if($activate) {
    reload('login');
} else {
    echo 'Something went wrong in the activation, are you sure that is the correct activation key?';
}
/**
 * TODO: Fix the part where the redirect doesn't go to the right place, and show a message saying 'Thanks your account is activated'; 
 */
?>