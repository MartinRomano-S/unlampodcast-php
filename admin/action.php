<?php

include "db.php";

class DataOperation extends Database
{
	public $tableName;

	public function addRegister($table, $fields){
		$sql = "INSERT INTO ".$table;
		$sql .= "(".implode(",", array_keys($fields)).") ";
		$sql .= "VALUES ('".implode("','", array_values($fields))."')";
		$stmt = $this->conn->prepare($sql);
		$result = $stmt->execute();

		return $result;
	}

	public function updateRegister($table, $where, $fields){

		$sql = "UPDATE ".$table;
		$array = array();
		$set = "";
		$cond = "";

		foreach ($fields as $key => $value) {
			$set .= $key." = '".$value."' , ";
		}

		$set = substr($set, 0, -3);

		foreach ($where as $key => $value) {
			$cond .= $key." = '".$value."' AND ";
		}

		$cond = substr($cond, 0, -5);
		$sql .= " SET ".$set." WHERE ".$cond;
		$stmt = $this->conn->prepare($sql);
		$result = $stmt->execute();

		return $result;
	}

	public function deleteRegister($table, $where){

		$sql = "DELETE FROM ".$table;
		$array = array();
		$cond = "";

		foreach ($where as $key => $value) {
			$cond .= $key." = '".$value."' AND ";
		}

		$cond = substr($cond, 0, -5);
		$sql .= " WHERE ".$cond;
		$stmt = $this->conn->prepare($sql);
		$result = $stmt->execute();

		return $result;
	}

	public function loadAll($table){

		$sql = "SELECT * FROM ".$table;
		$array = array();
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc()){
			$array[] = $row;
		}

		return $array;
	}

	public function loadSelected($table,$where){

		$sql = "SELECT * FROM ".$table;
		$array = array();
		$cond = "";

		foreach ($where as $key => $value) {
			$cond .= $key." = '".$value."' AND ";
		}

		$cond = substr($cond, 0, -5);
		$sql .= " WHERE ".$cond;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();

		if($row = $result->fetch_assoc()){
			$array[] = $row;
			return $row;
		}
		
		return null;
	}
}

$obj = new DataOperation;

//INSERT
if(isset($_POST["insert"])){

	$tableName = $_POST["tableName"];
	$retLocation = $_POST["retLocation"];

	unset($_POST["retLocation"]);
	unset($_POST["tableName"]);
	unset($_POST["insert"]);

	$colArray = array();

	foreach($_POST as $name => $value) {
    	
    	if($value != "on"){
	    	$colArray[$name] = $value;
	    }
	}

	if($obj->addRegister($tableName,$colArray)){
		header("Location: ".$retLocation);
	}
}

//UPDATE
if(isset($_POST["edit"])){

	$tableName = $_POST["tableName"];
	$retLocation = $_POST["retLocation"];
	$where = array(
	    $_POST["field"]=>$_POST["pk"]
	);

	unset($_POST["edit"]);
	unset($_POST["field"]);
	unset($_POST["pk"]);
	unset($_POST["retLocation"]);
	unset($_POST["tableName"]);

	$colArray = array();

	foreach($_POST as $name => $value) {
    	
    	if($value != "on"){
	    	$colArray[$name] = $value;
	    }
	}

	if($obj->updateRegister($tableName,$where,$colArray)){
		header("Location: ".$retLocation);
	}
}

//DELETE
if(isset($_POST["delete"])){

	$tableName = $_POST["tableName"];
	$retLocation = $_POST["retLocation"];

	unset($_POST["retLocation"]);
	unset($_POST["tableName"]);
	unset($_POST["delete"]);
	
	$where = array();

	foreach($_POST as $name => $value) {
    	
    	if(!empty($value)){
	    	$where[$name] = $value;
	    }
	}

	if($obj->deleteRegister($tableName,$where)){
		header("Location: ".$retLocation);
	}
}
?>