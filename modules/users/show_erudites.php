<?php
/**
 * The controller for showing the list of newest users on the home page
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */
//a user object
$user_obj = new User($sql);

//load 6 users and order them by the date registered to get the newest registered users
$users = $user_obj->load_multiple_users(6, 'user_registered_since');

//loop through the user list, include the template, replace the tokens and so on
$user_list = "";
foreach($users as $key => $user) {
    $li_class = ($key % 2 == 0) ? 'left' : 'right';
    ob_start();
    include ROOT . 'templates/users/user_list.html';
    $user_list .= replace_tokens(ob_get_clean(), array('USER_FIRST_NAME' => $user['user_first_name'], 'USER_IMAGE' => $user['image'], 'USER_LAST_NAME' => $user['user_last_name'], 'LI_CLASS' => $li_class));
}

//show the erudites template
ob_start();

include ROOT . 'templates/users/show_erudites.html';

echo replace_tokens(ob_get_clean(), array('USER_LIST' => $user_list));

?>