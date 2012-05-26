<?php
if(isset($_SESSION['user'])) {
   session_destroy(); 
}
if(isset($_COOKIE['user'])) {
   unset_cookie('user'); 
}
reload('home');

?>