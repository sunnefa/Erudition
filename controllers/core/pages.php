<?php
//controller for the pages

$page = new Page($sql, 1);

$page->load_page_modules();

foreach($page->page_modules as $module) {
    include ROOT . $module->module_path;
}

?>
