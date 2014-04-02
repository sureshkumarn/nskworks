<?php
  //error_reporting(E_ALL);
  require_once($_SERVER['DOCUMENT_ROOT'].'/path_settings.php');
  require_once('index_main.php');
  
  error_reporting(E_ALL);

  $data_type = Utills::get_data_type();
  if($data_type == 'json'){
    $country_code = $_GET['cc'];
  	$page = new PopulationGrowth();
  	$page->country_code = $country_code;
    $page->create_json();
    
    echo $page->get_json();
  } else {
  	$page = new PopulationGrowth();
  	$page->create();
  	echo $page->get_page();	
  }
?>