<?php
/**
 * Controller for logging out
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com>
 */

//if there is a session, destroy it
if(isset($_SESSION['user'])) {
   session_destroy(); 
}
//if there is a cookie, unset it
if(isset($_COOKIE['user'])) {
   unset_cookie('user'); 
}
//redirect to home page
reload('home');
?>