<?php

$user_obj = new User($sql);

$users = $user_obj->load_multiple_users(6, 'user_registered_since');

$user_list = "";
foreach($users as $key => $user) {
    $li_class = ($key % 2 == 0) ? 'left' : 'right';
    ob_start();
    include ROOT . 'templates/users/user_list.html';
    $user_list .= replace_tokens(ob_get_clean(), array('USER_FIRST_NAME' => $user['user_first_name'], 'USER_IMAGE' => $user['image'], 'USER_LAST_NAME' => $user['user_last_name'], 'LI_CLASS' => $li_class));
}

ob_start();

include ROOT . 'templates/users/show_erudites.html';

echo replace_tokens(ob_get_clean(), array('USER_LIST' => $user_list));

?>