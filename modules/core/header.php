<?php
$navigation = $page->select_multiple_pages();
$nav_text = "";
foreach($navigation as $nav) {
    $not_in_header = array('quiz', 'contact', 'logout', 'forgotten');
    if(is_logged_in()) {
        array_push($not_in_header, 'signup', 'login', 'faq', 'terms', 'about');
        unset($not_in_header[2]);
    } else {
        array_push($not_in_header, 'community', 'courses');
    }
    if(!in_array($nav['page_name'], $not_in_header)) {
        ob_start();
        include ROOT . 'templates/core/navigation.html';
        $class = ($nav['page_name'] == $page->page_name) ? 'current' : '';
        $nav_text .= replace_tokens(ob_get_clean(), array('LINK' => $nav['page_url'], 'LINK_NAME' => $nav['page_title'], 'CLASS' => $class));
    }
}
ob_start();
include ROOT . 'templates/core/header.html';
echo replace_tokens(ob_get_clean(), array('NAV' => $nav_text, 'META_DESCRIPTION' => $page->page_meta_description, 'TITLE' => $page->page_title));

/**
 * TODO: Add a 404 page
 * TODO: Create the account recovery page 
 */
?>