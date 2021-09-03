<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php 

include "db.php";

class Topic extends Database {

	private $table = "pu_temas";

	public function addRegister($types, $fields){

		$sql = "INSERT INTO ".$this->table;
		$sql .= "(NOMBRE,DESCRIPCION,COLOR) ";
		$sql .= "VALUES (?,?";
		//Antes de insertar vamos a generar un color random para el tag
		$hex1 = dechex(mt_rand(0,200));

		if($hex1 < 10){
			$hex1 = "0".$hex1;
		}

		$hex2 = dechex(mt_rand(0,200));

		if($hex2 < 10){
			$hex2 = "0".$hex2;
		}

		$hex3 = dechex(mt_rand(0,200));

		if($hex3 < 10){
			$hex3 = "0".$hex3;
		}

		$hexColor = "#".$hex1.$hex2.$hex3;
		$sql .= ",'".$hexColor."')";

		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $fields["NOMBRE"],$fields["DESCRIPCION"]);
		$result = $stmt->execute();

		return $result;
	}

	public function updateRegister($types,$where, $fields){

		$sql = "UPDATE ".$this->table;
		$sql .= " SET NOMBRE=?, DESCRIPCION=? ";
		$sql .= "WHERE ID_TEMA = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $fields["NOMBRE"],$fields["DESCRIPCION"],$where["ID_TEMA"]);
		$result = $stmt->execute();

		return $result;
	}

	public function deleteRegister($types, $where){

		$sql = "DELETE FROM ".$this->table;
		$sql .= " WHERE ID_TEMA = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $where["ID_TEMA"]);
		$result = $stmt->execute();

		return $result;
	}

	public function loadAll(){

		$sql = "SELECT * FROM ".$this->table;
		$array = array();
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc()){
			$array[] = $row;
		}

		return $array;
	}

	public function loadSelected($where){

		$sql = "SELECT * FROM ".$this->table;
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

$obj = new Topic;

//INSERT
if(isset($_POST["insert"])){

	unset($_POST["insert"]);

	$colArray = array(
		"NOMBRE" => $_POST["nmTopic"],
		"DESCRIPCION" => $_POST["descTopic"]
	);

	if($obj->addRegister("ss",$colArray)){
		$_SESSION['noerror'] = "El tema se ha creado correctamente";
		error_log("El usuario ".$_SESSION['user']." ha creado el tema ".$_POST["nmTopic"]. " correctamente.", 0);
		header("Location: topics.php");
	}else{
		$_SESSION['error'] = "Error al crear tema";
		error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear el tema ".$_POST["nmTopic"], 0);
		header("Location: topics.php");
	}
}

//UPDATE
if(isset($_POST["edit"])){

	unset($_POST["edit"]);
	$colArray = array(
		"NOMBRE" => $_POST["nmTopic"],
		"DESCRIPCION" => $_POST["descTopic"]
	);

	$where = array(
		"ID_TEMA" => $_POST["id"]
	);

	if($obj->updateRegister("ssd",$where,$colArray)){
		$_SESSION['noerror'] = "El tema se ha actualizado correctamente";
		error_log("El usuario ".$_SESSION['user']." ha actualizado el tema ".$_POST["id"]." correctamente.", 0);
		header("Location: topics.php");
	}else{
		$_SESSION['error'] = "Error al actualizar tema";
		error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido actualizar el tema ".$_POST["id"], 0);
		header("Location: topics.php");
	}
}

//DELETE
if(isset($_GET["delete"])){

	unset($_GET["delete"]);
	
	$where = array(
		"ID_TEMA" => $_GET["id"]
	);

	if($obj->deleteRegister("d",$where)){
		$_SESSION['noerror'] = "El tema se ha eliminado correctamente";
		error_log("El usuario ".$_SESSION['user']." ha eliminado el tema ".$_GET["id"]." correctamente.", 0);
		header("Location: topics.php");
	}else{
		$_SESSION['error'] = "Error al eliminar tema";
		error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido eliminar el tema ".$_GET["id"], 0);
		header("Location: topics.php");
	}
}
?>