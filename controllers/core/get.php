<?php

$page = new Page($sql);

$page->get_page_by_name($_GET['page']);

$page_modules = $page->load_page_modules();


?>