<?php

class Database
{
	//properties
	private $con;

	//methods
	public function connect() {
		include_once("constants.php");
		//Adding the constants,namely host name/IP adddress,mysql username,password,database to open a new connection to the MySQL server
		$this->con = new Mysqli(HOST,USER,PASS,DB);
		if($this->con) {
			//echo "database connected";
			return $this->con;    //Returns an object representing the connection to the MySQL server
		}
		return "DATABASE_CONNECTION_FAILED";
	}
}

//$db = new Database();   //object
//$db->connect();

?>