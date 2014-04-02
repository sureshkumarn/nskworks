<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/path_settings.php");
require_once "datamanager.php";
class DatabaseDataManager extends DataManager {

	protected $host = 'localhost';
	protected $user = 'root';
	protected $password = '';
	protected $db = 'nskworks_population';

	var $connection;

	function __construct($connection=''){
		parent::__construct();
		if($connection){
			$this->connection = $connection;	
		} else {
			$this->connection = $this->db_open();
		}
		//return $this->connection;
	}
	
	protected function db_open(){
		return new mysqli($this->host,$this->user,$this->password,$this->db);
	}

	protected function db_close(){
		$this->connection->close();
	}

}

?>