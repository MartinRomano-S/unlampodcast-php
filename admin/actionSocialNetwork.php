<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php 

include "db.php";

class SocialNetwork extends Database {

	private $table = "pu_redes_podcast";

	public function addRegister($types, $fields){

		$sql = "INSERT INTO ".$this->table;
		$sql .= "(ID_RED_SOCIAL,TEXTO_LINK,LINK) ";
		$sql .= "VALUES (?,?,?)";

		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $fields["ID_RED_SOCIAL"], $fields["TEXTO_LINK"],$fields["LINK"]);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Red social creada correctamente.";
			error_log("El usuario ".$_SESSION['user']." ha creado la red social ID_RED_SOCIAL: ".$fields["ID_RED_SOCIAL"]." correctamente.", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al crear la Red Social;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear la red social ".$fields["ID_RED_SOCIAL"].".", 0);
		}

		return $result;
	}

	public function updateRegister($types,$where, $fields){

		$sql = "UPDATE ".$this->table;
		$sql .= " SET ID_RED_SOCIAL=?, TEXTO_LINK=?, LINK=? ";
		$sql .= "WHERE ORDINAL = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $fields["ID_RED_SOCIAL"],$fields["TEXTO_LINK"],$fields["LINK"],$where["ORDINAL"]);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Red social actualizada correctamente.";
			error_log("El usuario ".$_SESSION['user']." ha actualizado la red social ".$where["ORDINAL"]." correctamente.", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al actualizar la Red Social;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido actualizar la red social ".$where["ORDINAL"].".", 0);
		}

		return $result;
	}

	public function deleteRegister($types, $where){

		$sql = "DELETE FROM ".$this->table;
		$sql .= " WHERE ORDINAL = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $where["ORDINAL"]);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Red social borrada correctamente.";
			error_log("El usuario ".$_SESSION['user']." ha eliminado la red social ".$where["ORDINAL"]." correctamente.", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al borrar la Red Social;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido eliminar la red social ".$where["ORDINAL"].".", 0);
		}

		return $result;
	}

	public function loadAll(){

		$sql = "SELECT * FROM ".$this->table." A INNER JOIN pu_red_social B ON A.ID_RED_SOCIAL = B.ID_RED_SOCIAL";
		$array = array();
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc()){
			$array[] = $row;
		}

		return $array;
	}

	public function loadAllRS(){

		$sql = "SELECT * FROM pu_red_social";
		$array = array();
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc()){
			$array[] = $row;
		}

		return $array;
	}

	public function loadAllRSFiltered($where){

		$sql = "SELECT * FROM pu_red_social";
		$array = array();
		$cond = "";

		foreach ($where as $key => $value) {
			$cond .= $key." <>".$value." AND ";
		}

		$cond = substr($cond, 0, -5);
		$sql .= " WHERE ".$cond;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc()){
			$array[] = $row;
		}

		return $array;
	}

	public function loadSelected($where){

		$sql = "SELECT * FROM ".$this->table." A INNER JOIN pu_red_social B ON A.ID_RED_SOCIAL = B.ID_RED_SOCIAL";
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

$obj = new SocialNetwork;

//INSERT
if(isset($_POST["insert"])){

	unset($_POST["insert"]);

	$colArray = array(
		"ID_RED_SOCIAL" => $_POST["sel1"],
		"TEXTO_LINK" => $_POST["txtLink"],
		"LINK" => $_POST["link"]
	);

	if($obj->addRegister("dss",$colArray)){
		header("Location: socialNetworks.php");
	}
}

//UPDATE
if(isset($_POST["edit"])){

	unset($_POST["edit"]);
	$colArray = array(
		"ID_RED_SOCIAL" => $_POST["sel1"],
		"TEXTO_LINK" => $_POST["txtLink"],
		"LINK" => $_POST["link"]
	);

	$where = array(
		"ORDINAL" => $_POST["id"]
	);

	if($obj->updateRegister("dssd",$where,$colArray)){
		header("Location: socialNetworks.php");
	}
}

//DELETE
if(isset($_GET["delete"])){

	unset($_GET["delete"]);
	
	$where = array(
		"ORDINAL" => $_GET["id"]
	);

	if($obj->deleteRegister("d",$where)){
		header("Location: socialNetworks.php");
	}
}
?>