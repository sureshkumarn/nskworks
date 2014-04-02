<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/common/path_settings.php');
require_once('index_main.php');

// Create the instance of start page.
$page = new StartPage();

$page->create();

echo $page->get_page();

?>