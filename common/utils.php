<?php

/*
 * @Description : 
 *
*/

class Utills {
	static function get_data_type(){
		return (isset($_GET['data_type']) && $_GET['data_type'] != '') ? $_GET['data_type'] : '';
	}	
}
?>