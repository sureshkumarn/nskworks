<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/path_settings.php');
require_once($common_path.'/sitepage.php');
require_once($layout_path.'/layout.php');
require_once('/popgrowth/popgrowth.php');

class StartPage extends SitePage{

  function __construct(){
    parent::__construct();
  }
  
  function create(){
    parent::create();
  }
  
  function get_content(){
    parent::get_content();
    
    $layout = new Layout($this);
    
    $eg = ''; // Exponential Growth
    $pc = ''; // Population Chart
    
    //$exponential_growth = new ExponentialGrowth();
    //$eg = $exponential_growth->create();
      
    $population_charts = new PopulationChart($this);
    $pc = $population_charts->create();
  
    //var_dump($pc);
    return <<<EOD
  $eg
  $pc
EOD;
  }
  
  /*
  function get_header(){
    
  }
  
  function get_footer(){
    
  }
  */
}
?>