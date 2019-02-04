<?php
Class DB {
	
	public $db = NULL;
	
	public function __construct()
	{
		require_once(R.'/system/db_config.php');
		$this->db=new mysqli(server, user, pass, db);

		if($this->db->connect_errno)
		{
			echo 'Connect failed: Error connecting to MySQL database';
			exit();
		}
		$this->db->set_charset('utf8mb4'); 
	}

	public function query($var)
	{
		$query=$this->db->query($var);
		return $query;
	}

	public function escape($var)
	{
		return $this->db->real_escape_string($var);
	}

	public function guard($var)
	{
		return self::escape(htmlspecialchars(trim($var)));
	}

	public function insert_id()
	{
		return $this->db->insert_id;
	}

	public function fass($var)
	{
		$result=$this->query($var)->fetch_assoc();
		return $result;
	}

	public function n_r($var)
	{
		return $this->query($var)->num_rows;
	}

	public function fass_c($var)
	{
		$result=$this->query($var)->fetch_assoc();
		return $result['count'];
	}
	
}
?>