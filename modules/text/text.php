<?php
/**
 * The controller for the text module
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */
//new text object based on the id of the page loaded

if($page->page_name == 'home') {
    if(is_logged_in()) {
        $text_id = 11;
    } else {
        $text_id = 1;
    }
    $text = new Text($sql);
    $text->get_text_by_id($text_id);
} else {
$text = new Text($sql, $page->page_id);
}

echo $text->text;
?>