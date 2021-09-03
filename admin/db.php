<?php


class Database
{
	public $conn;

	public function __construct(){
		$this->conn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");
	}

}

$obj = new Database;
?>