<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/path_settings.php');
require_once($common_path.'/sitepage.php');
require_once('popgrowth.php');

class PopulationGrowth extends SitePage {

  var $country_code;

  function __construct(){
    parent::__construct();
  }

  /*
  */
  function create_json(){
    parent::create_json();
    $this->get_json();
  }

  function get_json(){
    $pop_module = new PopulationChart($this);

    $pop_module->create_json();

    $pop_module_json = $pop_module->get_json();
    
    return <<<EOD
      $pop_module_json
EOD;
  }
  
  function create(){
    parent::create();
  }
  
  function get_content(){
    //parent::get_content();
    
    $pop_module = new PopulationChart($this);
    
    $pop_module_content = $pop_module->create();
    
     return <<<EOD
    <div class="countries-wrap">
  $pop_module_content
    </div>
EOD;
  }
}
?>