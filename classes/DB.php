<?php

define('DB_DRIVER','mysql');
define('DB_HOST','localhost');
define('DB_NAME','brightsproject');
define('DB_USER','root');
define('DB_PASS','');


class DB{
	private static $instance;

	private $link;

	private function __construct()
	{
		$connect_str = DB_DRIVER . ':host='. DB_HOST . ';dbname=' . DB_NAME;
		$this->link = new PDO($connect_str, DB_USER, DB_PASS);
	}

	public static function GetInstance(){
		if(self::$instance == null)
			self::$instance = new self();

		return self::$instance;
	}


	public function Select($sql){
		$result = $this->link->query($sql);

		$errors = $this->link->errorInfo();

		if ( $this->link->errorCode() != 0000 )
			echo "SQL error :".$errors[2]."<br/>";

		$rows = [];
		while($row = $result->fetch()){
			$rows[] = $row;
		}
		return $rows;
	}

	public function Insert($table, $object){
		$values = "";
		$i = 0;
		$count = count($object);
		foreach ($object as $key => $value)
		{
			$values .= " {$key} = '{$value}'";
			if ( $i + 1 != $count ){
				$values .= ",";
			}
			$i++;
		}

		$sql = "INSERT INTO {$table} SET {$values};";

		$this->link->exec($sql);

		return $this->link->lastInsertId();
	}

	public function Update($table, $object, $where){
		$values = "";
		$i = 0;
		$count = count($object);
		foreach ($object as $key => $value)
		{
			$values .= " {$key} = '{$value}'";
			if ( $i + 1 != $count ){
				$values .= ",";
			}
			$i++;
		}

		$sql = "UPDATE {$table} SET {$values} WHERE {$where};";

		$result = $this->link->exec($sql);

		$errors = $this->link->errorInfo();

		if ( $this->link->errorCode() != 0000 )
			echo "SQL error :".$errors[2]."<br/>";

		return $result;
	}

	public function Delete($table, $where){
		$result = $this->link->exec("DELETE FROM {$table} WHERE {$where}");
		$errors = $this->link->errorInfo();

		if ( $this->link->errorCode() != 0000 )
			echo "SQL error :".$errors[2]."<br/>";

		return $result;
	}
}