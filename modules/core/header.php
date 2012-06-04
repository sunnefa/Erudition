<?php
/**
 * The controller for the header module
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//load the navigation
$navigation = $page->select_multiple_pages();
$nav_text = "";
//loop through the navigation links
foreach($navigation as $nav) {
    //these are not supposed to be in the header
    $not_in_header = array('quiz', 'contact', 'logout', 'forgotten', '404');
    if(is_logged_in()) {
        //if a user is logged in, these are not supposed to be in the header either
        array_push($not_in_header, 'signup', 'login', 'faq', 'terms', 'about');
        //except the logout page
        //unset($not_in_header[2]);
    } else {
        //when a user is not logged in, these are not supposed to show up
        array_push($not_in_header, 'community', 'courses', 'resources', 'assignments');
    }
    //if the page is not in the $not_in_header array
    if(!in_array($nav['page_name'], $not_in_header)) {
        ob_start();
        //load the template
        include ROOT . 'templates/core/navigation.html';
        //if the current item in the loop is also the current page being shown, highlight it
        $class = ($nav['page_name'] == $page->page_name) ? 'current' : '';
        //replace the template tokens in the navigation template
        $nav_text .= replace_tokens(ob_get_clean(), array('LINK' => $nav['page_url'], 'LINK_NAME' => $nav['page_title'], 'CLASS' => $class));
    }
}

if(is_logged_in()) {
    $logged_in_class = 'login_menu';
    $user_id = (isset($_SESSION['e_user'])) ? $_SESSION['e_user'] : $_COOKIE['user'];
    $user = new User($sql, $user_id);
    $user_name = $user->user_first_name . ' ' . $user->user_last_name;
} else {
    $logged_in_class = 'invisible';
    $user_name = '';
}

ob_start();
//load the main header template
include ROOT . 'templates/core/header.html';
//echo it out and replace the template tokens
echo replace_tokens(ob_get_clean(), array('NAV' => $nav_text, 'META_DESCRIPTION' => $page->page_meta_description, 'TITLE' => $page->page_title, 'USERNAME' => $user_name, 'LOGGED_IN_CLASS' => $logged_in_class));
?>