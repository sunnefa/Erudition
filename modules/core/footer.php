<?php
/**
 * The controller for the footer module
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//load the navigation links
$navigation = $page->select_multiple_pages();
$nav_text = "";
//loop through the navigation links
foreach($navigation as $nav) {
    //these are not supposed to show in the footer navigation
    $not_in_footer = array('quiz', 'home', 'signup', 'login', 'community', 'logout', 'courses', 'forgotten', '404', 'resources', 'assignments');
    //if the page is not in the $not_in_footer array
    if(!in_array($nav['page_name'], $not_in_footer)) {
        ob_start();
        //include the template
        include ROOT . 'templates/core/navigation.html';
        //replace the template tokens
        $nav_text .= replace_tokens(ob_get_clean(), array('LINK' => $nav['page_url'], 'LINK_NAME' => $nav['page_title'], 'CLASS' => ''));
    }
}
ob_start();
//include the main footer template
include ROOT . 'templates/core/footer.html';
//echo it and replace the template tokens
echo replace_tokens(ob_get_clean(), array('NAV' => $nav_text));
?>