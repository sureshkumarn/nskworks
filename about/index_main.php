<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/path_settings.php');
require_once($common_path.'/sitepage.php');
require_once($layout_path.'/layout.php');
require_once $base_path."/datamanager/mysql_datamanager.php";
//require_once('/popgrowth/popgrowth.php');

class AboutPage extends SitePage{

  var $page_type;

  function __construct(){
    parent::__construct();
  }
  
  function create(){
    parent::create();
  }
  
  function get_js_linked(){
    return array('jquery.js','utils.js');
  }

  function save_query($form){
    $usrname = $_POST['usrname'];
    $comment = $_POST['cmt'];

    $query = "INSERT INTO contact_us ('user_name','comment') VALUES ('".$usrname."','".$comment."')";

  }

  function get_content(){
    parent::get_content();
    
    $layout = new Layout($this);
    
    $content = '';

    switch ($this->page_type) {
      case 'crisis':
        $content = $this->about_crisis();
        break;

      case 'contact':
        $content = $this->contact_us();
        break;
      
      default:
        $content = $this->about_site();
        break;
    }

    //var_dump($pc);
    return <<<EOD
  $content
EOD;
  }

  function about_site(){
    return <<<EOD
    <div class="about">
      <h2>About EnvyMYWORLD</h2>
      <div class="cnt">
        <p>EnvyMYWORLD is created in an attempt to explain site visitors about few of the crisis faced in earth which can be/could have been solved if we have taken some living practice. The site is developed in spare time of the developers, so the site is updated slowly.</p>
        <p> If you have any question, or an idea to implement which might give the site users an awareness, please <a href="/about/?p=contact">contact us</a>.
      </div>
    </div>
EOD;
  }

  function about_crisis(){

  }

  function contact_us(){
    $path = $_SERVER['PHP_SELF'].'?p='.$_GET['p'];
    return <<<EOD
    <div class="contact">
      <form action='$path' method="post">
        <div class="field">
          <label for="usrname">Name</label>
          <input type="text" name="usrname" id="usrname" value="">
        </div>
        <div class="field">
        <label for="cmt">Comment</label>
        <textarea name="cmt" id="cmt">
        </textarea>
        </div>
        <div class="field">
          <input type="submit" name="cmtsubmit" id="cmtsubmit" value="Post">
        </div>
      </form>
    </div>
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