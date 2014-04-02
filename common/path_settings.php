<?php
/*
 * @Description : Common File for all the apps which stores the sitewide settings and paths
 */
$base_path = $_SERVER['DOCUMENT_ROOT'];
$server_path =  'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
$common_path = $base_path.'/common';
$layout_path = $common_path.'/layout';
$css_path = $server_path.'/css';
$js_path = $server_path.'/js';

?>