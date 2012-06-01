<?php
$navigation = $page->select_multiple_pages();
$nav_text = "";
foreach($navigation as $nav) {
    $not_in_footer = array('trek_quiz', 'home', 'signup', 'login', 'community', 'logout', 'courses');
    if(!in_array($nav['page_name'], $not_in_footer)) {
        ob_start();
        include ROOT . 'templates/core/navigation.html';
        $nav_text .= replace_tokens(ob_get_clean(), array('LINK' => $nav['page_url'], 'LINK_NAME' => $nav['page_title'], 'CLASS' => ''));
    }
}
ob_start();
include ROOT . 'templates/core/footer.html';
echo replace_tokens(ob_get_clean(), array('NAV' => $nav_text));
?>