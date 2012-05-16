<?php

$page = new Page($sql);

$page->get_page_by_name($_GET['page']);

$page->load_page_modules();

$page_modules = $page->page_modules;


?>