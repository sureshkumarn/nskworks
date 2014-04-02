<?php
require_once $common_path."/module.php";
require_once $base_path."/datamanager/mysql_datamanager.php";

class PopulationChart extends Module {
  
  protected $countries;
  protected $selected_country;
  protected $connection;
  protected $stats;
  protected $page;
  
  function __construct($page){
    parent::__construct($page);
  }
  
  function get_js_linked(){
    return array('jquery.js','dygraph.js','popgrowth.js');
  }
  
  function get_js(){
    return <<<EOD
 
EOD;
  }

  function create_json(){
        $this->load_country_stats($this->page->country_code);   
        $this->create_excel(); 
  }

  function get_json(){
    return json_encode($this->stats);
  }

  function create_excel(){
    
  }
  
  function get_content(){
    // Select from Geo Location
    // Select from Dropdown
    $this->get_countries();
    $countries = $this->countries;
    
    $str = '<select id="countries"><option value="">Select Country</option>';
    $selected = $this->selected_country;
    for($i=0;$i<sizeof($countries); $i++){
      if($selected==$countries[$i]['code']){
        $str .= '<option value="'.$countries[$i]['code'].'" selected="selected">'.$countries[$i]['name'].'</option>';  
      } else {
        $str .= '<option value="'.$countries[$i]['code'].'">'.$countries[$i]['name'].'</option>';  
      }
    }
    $str .= "</select>";
    $str .= $this->generate_icons();
    $str .= '<div class="poptbl-wrap">
    <h2>Country Stats for <span></span></h2>
<table class="poptbl">
<thead>
  <tr>
    <th>S. No.</th>
    <th>Year</th>
    <th>Population</th>
  </tr>
</thead>
<tbody></tbody>
</table>
    </div>';
    $str .= '<div id="popgraph" class="popgraph"></div>';

    return <<<EOD
  $str
EOD;
    // Compare with High, Low
    // Compare with most popular
  }

  /*
   * Function to generate list or graph icon
  */
  function generate_icons(){
    return <<<EOD
    <span class="display-opt">
    <a href=""><span class="graph">Graph</span></a> | <a href=""><span class="table">Table</span></a>
    </span>
EOD;
  }
  
  function load_country_stats($country){
    $query = "SELECT country_code,country_name,population, year FROM population_table WHERE country_code = '".$country."'";
    if(empty($this->connection)){
      $this->connection = new DatabaseDataManager();
    }
    //var_dump($this->connection);
    if($result = $this->connection->connection->query($query)){
      $popluation_stat = array();
      $count = 0;
      $count_flag = true;
      while($result_obj = $result->fetch_object()){
        if($count_flag){
          $population_stat['country'] = $result_obj->country_name;
          $popluation_stat['country_code'] = $result_obj->country_code;
          $count_flag = false;
        }
        $popluation_stat['population'][$count]['year'] = $result_obj->year;
        $popluation_stat['population'][$count++]['population'] = $result_obj->population;
      }      
    }
    $this->stats = $popluation_stat;
  }
  
  function get_countries(){
    
    $datamgr = new DatabaseDataManager();
    
    //var_dump($datamgr);
    
    $this->connection = $datamgr;
    $query = "SELECT country_name,country_code FROM country_table WHERE country_area != 0";
    if($country_obj = $datamgr->connection->query($query)){
      $count = 0;
     while($country = $country_obj->fetch_object()){
      $countries[$count]['name'] = $country->country_name;
      $countries[$count++]['code'] = $country->country_code;
     }
    }
    $this->countries = $countries;    
  }
  
  function init(){
    if(!empty($_GET['country'])){
      $this->selected_country = $_GET['country'];
    }
  }
}

?>