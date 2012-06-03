<?php
$activation_key = $_GET['activation'];

$user = new User($sql);

$activate = $user->activate_user($activation_key);

if($activate) {
    echo '<p class="success">Your account has now been activated. Please stand by while we redirect you to the login page</p>';
} else {
    echo '<p class="error">Something went wrong in the activation, are you sure that is the correct activation key?</p>';
}
$base = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$base = (isset($_GET['page'])) ? str_replace($_GET['page'], '', $base) : $base;
$base = (isset($_GET['activation'])) ? str_replace($_GET['activation'], '', $base) : $base;
$base = str_replace('/activation=/', '', $base);
$base = str_replace('index.php', '', $base);
//header("Refresh:5; url=" . $base . "login/");
?>