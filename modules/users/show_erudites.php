<?php

$user_obj = new User($sql);

$users = $user_obj->load_multiple_users(10);

?>