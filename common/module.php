<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/path_settings.php");
require_once($common_path."/page.php");

class Module
{
   // Used to store a reference to the page on which the module resides.
   protected $page;

   /*
   *  The following methods comprise the public interface for the class.
   */

   public function __construct($page)
   {
      // All modules store a reference to the page on which they reside.
      // In PHP 5, objects are passed by reference by default. In PHP 4,
      // we would have had to specify we wanted a reference explicitly.
      // We need a reference to the page because the module modifies it.
      $this->page = $page;
   }

   public function create()
   {
      // Add module CSS styles to the page on which the module resides.
      $this->page->add_to_css_linked($this->get_css_linked());
      $this->page->add_to_css($this->get_css());

      // Add module JavaScript to the page on which the module resides.
      $this->page->add_to_js_linked($this->get_js_linked());
      $this->page->add_to_js($this->get_js());

      return $this->get_content();
   }

   public function create_json(){
      return $this->get_json();
   }

   public function get_json(){

   }

   /*
   *  The following methods comprise the abstract interface for the
   *  class. These are methods with empty implementations by default,
   *  many of which specific module classes override for their needs.
   */

   public function get_css_linked()
   {
   }

   public function get_css()
   {
   }

   public function get_js_linked()
   {
   }

   public function get_js()
   {
   }

   public function get_content()
   {
   }
}
?>
