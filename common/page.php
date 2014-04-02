<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/path_settings.php");
require_once($common_path."/module.php");
require_once($common_path."/utils.php");

class Page
{
   // Members for aggregating CSS styles from modules as they are added
   // to the page, storing information about how and when to link files,
   // and keeping track of various sections of CSS styles on the page.
   protected $css;
   protected $css_linked;

   protected $css_linked_info;
   protected $css_linked_used;
   protected $css_is_local = true;

   protected $css_common;
   protected $css_page;
   protected $css_module;

   protected $css_id;

   // Members for aggregating JavaScript from modules as they are added
   // to the page, storing information about how and when to link files,
   // and keeping track of various sections of JavaScript on the page.
   protected $js;
   protected $js_linked;

   protected $js_linked_info;
   protected $js_linked_used;
   protected $js_is_local;

   protected $js_common;
   protected $js_page;
   protected $js_module;

   protected $js_is_top;

   // Members to manage loading and saving data stored by the backend.
   protected $load_args;
   protected $load_data;
   protected $load_stat;
   protected $save_args;
   protected $save_data;
   protected $save_stat;
   protected $save_data_flag;

   // Members to manage meta information about the page and its body.
   protected $title;
   protected $equiv;
   protected $desc;
   protected $keywd;
   protected $body;
   protected $data_type = '';

   /*
   *  The following methods comprise the public interface for the class.
   */

   public function __construct()
   {
      $this->css = "";
      $this->css_linked = "";
      $this->css_linked_info = array();
      $this->css_linked_used = array();
      $this->css_is_local = true;
      $this->css_common = "";
      $this->css_page = "";
      $this->css_module = "";
      $this->css_id = "";

      $this->js = "";
      $this->js_linked = "";
      $this->js_linked_info = array();
      $this->js_linked_used = array();
      $this->js_is_local = true;
      $this->js_common = "";
      $this->js_page = "";
      $this->js_module = "";
      $this->js_is_top = false;

      $this->load_args = array();
      $this->load_data = array();
      $this->load_stat = "";
      $this->save_args = array();
      $this->save_data = array();
      $this->sava_stat = "";
      $this->save_data_flag = false;

      $this->title = "";
      $this->equiv = "";
      $this->desc = "";
      $this->keywd = "";
   }

   public function create()
   {
      $this->register_links();

      if ($this->save_data_flag)
         $this->save_data();

      $this->load_data();

      // Do these steps now to give the page the opportunity to execute
      // them based on data from the backend that may have been loaded.
      $this->set_title();
      $this->set_meta();
      $this->set_css_id();

      // This needs to be done before the modules add JavaScript and CSS
      // so that files from multiple sources appear in the order linked.
      $this->set_js_common();
      $this->set_js_page();
      $this->set_css_common();
      $this->set_css_page();

      $header = $this->get_header();
      $content = $this->get_content();
      $footer = $this->get_footer();

      // We wrap the body of the page in a canvas division with its own
      // body, which provides some additional hooks useful for styling.
      $this->body = <<<EOD
<div id="wrap">
   <div class="page">
$header
$content
$footer
   <!-- sitecvsbd -->
   </div>
<!-- sitecvs -->
</div>

EOD;

      return $this->body;
   }

   public function get_page()
   {
      if (empty($this->css_id))
         $css_id = "";
      else
         $css_id = " id=\"".$this->css_id."\"";

      $doctype = $this->get_doctype();
      $meta = $this->get_meta();
      $title = $this->get_title();

      // Generally, itâs a good idea for performance to place JavaScript
      // at the bottom of the page; however, a flag lets us alter this.
      if ($this->js_is_top)
      {
         $js_top = $this->get_all_js();
         $js_btm = "";
      }
      else
      {
         $js_top = "";
         $js_btm = $this->get_all_js();
      }

      $css = $this->get_all_css();

      // Return the entire page suitable for echoing back to the browser.
      return <<<EOD
$doctype
<html>
<head>
$meta
$title
$css
$js_top
</head>
<body{$css_id}>
$this->body
$js_btm
</body>
</html>

EOD;
   }

   public function get_doctype()
   {
      return <<<EOD
<!DOCTYPE HTML>

EOD;
   }

   public function get_meta()
   {
      $meta = <<<EOD
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

EOD;

      if (!empty($this->equiv))
      {
         $meta .= <<<EOD
<meta name="http-equiv" content="{$this->equiv}" />

EOD;
      }

      if (!empty($this->desc))
      {
         $meta .= <<<EOD
<meta name="description" content="{$this->desc}" />

EOD;
      }

      if (!empty($this->keywd))
      {
         $meta .= <<<EOD
<meta name="keywords" content="{$this->keywd}" />

EOD;
      }

      return $meta;
   }

   public function get_title()
   {
      return <<<EOD
<title>{$this->title}</title>

EOD;
   }

   public function add_to_css_linked($keys)
   {
      $this->css_linked .= $this->manage_css_linked($keys);
   }

   public function add_to_css($css)
   {
      $this->css .= $css;
   }

   public function get_all_css()
   {
      // First, we get all the styles that were appended by modules.
      $this->css_module = $this->css_linked;
      $this->css_module .= $this->create_css($this->css);

      // Then we assemble all the CSS styles for the page in one block.
      return <<<EOD
<!-- Common CSS -->
$this->css_common
<!-- Page CSS -->
$this->css_page
<!-- Module CSS -->
$this->css_module
EOD;
   }

   public function add_to_js_linked($keys)
   {
      $this->js_linked .= $this->manage_js_linked($keys);
   }

   public function add_to_js($js)
   {
      $this->js .= $js;
   }

   public function get_all_js()
   {
      // First, we get all JavaScript that was appended by modules.
      $this->js_module = $this->js_linked;
      $this->js_module .= $this->create_js($this->js);

      // Then we assemble all the JavaScript for the page in one block.
      return <<<EOD
<!-- Common JS -->
$this->js_common
<!-- Page JS -->
$this->js_page
<!-- Module JS -->
$this->js_module
EOD;
   }

   public function set_js_top()
   {
      $this->js_is_top = true;
   }

   /*
   *  The following methods comprise the abstract interface for the
   *  class. These are methods with empty implementations by default,
   *  many of which specific page classes override for their needs.
   */

   public function get_css_common()
   {
   }

   public function get_css_linked()
   {
   }

   public function get_css()
   {
   }

   public function get_js_common()
   {
   }

   public function get_js_linked()
   {
   }

   public function get_js()
   {
   }

   public function load_data()
   {
   }

   public function save_data()
   {
   }

   public function get_header(){
      //return "<div>This is Header!!!</div>";
   }

   public function get_footer(){
   }

   public function get_content()
   {
   }

   public function set_title()
   {
   }

   public function set_meta()
   {
   }

   public function set_css_id()
   {
   }

   public function register_links()
   {
   }

   /*
   *  The following methods are for implementation details in the class.
   */

   private function manage_css_linked($keys)
   {
      $css = "";

      if (empty($keys))
         return "";

      // Normalize so that we can pass keys individually or as an array.
      if (!is_array($keys))
         $keys = array($keys);

      foreach ($keys as $k)
      {
         // Log an error for unknown keys when there is no link to add.
         if (!array_key_exists($k, $this->css_linked_info))
         {
            error_log("Page::manage_css_linked: Key \"".$k."\" missing");
            continue;
         }

         // Add the link only if it hasn't been added to the page before.
         if (array_search($k, $this->css_linked_used) === false)
         {
            $this->css_linked_used[] = $k;
            $css .= $this->create_css_linked($k);
         }
      }

      return $css;
   }

   private function create_css_linked($k)
   {
      //echo $k;
      // Links can be fetched locally or from an also-known-as location.
      if ($this->css_is_local)
         $path = $this->css_linked_info[$k]["loc_path"];
      else
         $path = $this->css_linked_info[$k]["aka_path"];
      
      // Links have an optional media type (with a default type "all").
      if (empty($this->css_linked_info[$k]["media"]))
         $media = "all";
      else
         $media = $this->css_linked_info[$k]["media"];

      return <<<EOD
<link href="$path" type="text/css" rel="stylesheet" media="$media" />

EOD;
   }

   private function create_css($css)
   {
      if (!empty($css))
      {
         return <<<EOD
<style type="text/css" media="all" >$css</style>

EOD;
      }
      else
      {
         return "";
      }
   }

   private function set_css_common()
   {
      $this->css_common = $this->manage_css_linked($this->get_css_common());
   }

   private function set_css_page()
   {
      $this->css_page = $this->manage_css_linked($this->get_css_linked());
      $this->css_page .= $this->create_css($this->get_css());
   }

   private function manage_js_linked($keys)
   {
      $js = "";

      if (empty($keys))
         return "";

      // Normalize so that we can pass keys individually or as an array.
      if (!is_array($keys))
         $keys = array($keys);

      foreach ($keys as $k)
      {
         // Log an error for unknown keys when there is no link to add.
         if (!array_key_exists($k, $this->js_linked_info))
         {
            error_log("Page::manage_js_linked: Key \"".$k."\" missing");
            continue;
         }

         // Add the link only if it hasn't been added to the page before.
         if (array_search($k, $this->js_linked_used) === false)
         {
            $this->js_linked_used[] = $k;
            $js .= $this->create_js_linked($k);
         }
      }

      return $js;
   }

   private function create_js_linked($k)
   {
      // Links can be fetched locally or from an also-known-as location.
      if ($this->js_is_local)
         $path = $this->js_linked_info[$k]["loc_path"];
      else
         $path = $this->js_linked_info[$k]["aka_path"];

      return <<<EOD
<script src="$path" type="text/javascript"></script>

EOD;
   }

   private function create_js($js)
   {
      if (!empty($js))
      {
         return <<<EOD
<script type="text/javascript">$js</script>

EOD;
      }
      else
      {
         return "";
      }
   }

   private function set_js_common()
   {
      $this->js_common = $this->manage_js_linked($this->get_js_common());
   }

   private function set_js_page()
   {
      $this->js_page = $this->manage_js_linked($this->get_js_linked());
      $this->js_page .= $this->create_js($this->get_js());
   }

   public function get_data_type(){
      return $this->data_type;
   }

   public function set_data_type($data_type){
      $this->data_type = $data_type;
   }

   public function create_json(){

   }

   public function get_json(){
      
   }
}
?>
