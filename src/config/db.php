<?php
	class db{
		private $dbhost = 'fortson.chnvenows2hj.us-east-1.rds.amazonaws.com';
		private $dbuser = 'admin';
		private $password = 'Tw0Zer019J^mes516!';
		private $dbname = 'meeter';
		
		// connect
		public function connect(){
			$mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname";
			$dbconnection = new PDO($mysql_connect_str, $this->dbuser, $this->password);
			$dbconnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
			return $dbconnection;
		}
	}