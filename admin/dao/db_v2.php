<?php
class DatabaseCasero
{
	private static $conn;

	private function __construct(){}

	public static function connect(){
		self::$conn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");
		return self::$conn;
	}
}
?>