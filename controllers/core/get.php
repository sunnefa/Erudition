<?php
/**
 * The controller for all get requests
 * 
 * @author Sunnefa Lind <sunnefa_lind@hotmail.com> 
 */

//create the page object
$page = new Page($sql);
//check if a page by this name was found
$found = $page->get_page_by_name($_GET['page']);
//if it was, load the modules
if($found) {

    $page->load_page_modules();

    $page_modules = $page->page_modules;
//if it wasn't, show the 404 page
} else {
    reload('404');
}
?>