<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/path_settings.php");
require_once($common_path."/module.php");

class Layout extends Module
{
   public function __construct($page)
   {
      parent::__construct($page);
   }

   public function get_section($class, $modules)
   {
      if (count($modules) == 0)
         return "";

      foreach ($modules as $content)
         $section .= empty($content) ? "" : $content;

      if (empty($section))
         return "";

      return <<<EOD
<div class="$class">
$section
<!-- $class -->
</div>

EOD;
   }
}
?>
