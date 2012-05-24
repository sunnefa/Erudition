<?php
if(!isset($_SESSION['user']) || isset($_COOKIE['user'])) {
    include ROOT . 'templates/users/registration_form.html';
} else {
    echo 'You are logged in, which means you are registered, so what are you doing here?';
}
?>