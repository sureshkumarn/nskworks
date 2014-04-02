<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/common/path_settings.php');
require_once('index_main.php');

// Create the instance of start page.
$page = new AboutPage();

$page->page_type = isset($_GET['p'])?$_GET['p'] : 'site';

if(isset($_POST['usrname'])){
	// Code to post contact us form
	$page->save_query($_POST);
}

$page->create();
echo $page->get_page();

?>