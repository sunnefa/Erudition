<?php
/**
 * Controller for user activation
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//the activation key in the url
$activation_key = $_GET['activation'];
//create a new user object
$user = new User($sql);
//attempt to activate the user with this activation key
$activate = $user->activate_user($activation_key);

//if it worked show a success message
if($activate) {
    echo '<p class="success">Your account has now been activated. Please stand by while we redirect you to the login page</p>';
//if it didn't work show an error message
} else {
    echo '<p class="error">Something went wrong in the activation, are you sure that is the correct activation key?</p>';
}
//replace certain parts of the url to redirect us to the right place
$base = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$base = (isset($_GET['page'])) ? str_replace($_GET['page'], '', $base) : $base;
$base = (isset($_GET['activation'])) ? str_replace($_GET['activation'], '', $base) : $base;
$base = str_replace('/activation=/', '', $base);
$base = str_replace('index.php', '', $base);
//redirect to the login page, but wait 5 seconds
header("Refresh:5; url=" . $base . "login/");
?>