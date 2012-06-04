<?php
/**
 * The controller for the text module
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */
//new text object based on the id of the page loaded
$text = new Text($sql, $page->page_id);
echo $text->text;
?>