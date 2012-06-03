<?php

$page = new Page($sql);

$found = $page->get_page_by_name($_GET['page']);

if($found) {

    $page->load_page_modules();

    $page_modules = $page->page_modules;

} else {
    reload('404');
}


?>