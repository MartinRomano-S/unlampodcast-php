<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php 

include "db.php";

class Topic extends Database {

	private $table = "pu_autores";

	public function addRegister($types, $fields){

		$sql = "INSERT INTO ".$this->table;
		$sql .= "(NOMBRE,APELLIDO) ";
		$sql .= "VALUES (?,?)";

		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $fields["NOMBRE"],$fields["APELLIDO"]);
		$result = $stmt->execute();

		return $result;
	}

	public function updateRegister($types,$where, $fields){

		$sql = "UPDATE ".$this->table;
		$sql .= " SET NOMBRE=?, APELLIDO=? ";
		$sql .= "WHERE ID_AUTOR = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $fields["NOMBRE"],$fields["APELLIDO"],$where["ID_AUTOR"]);
		$result = $stmt->execute();

		return $result;
	}

	public function deleteRegister($types, $where){

		$sql = "DELETE FROM ".$this->table;
		$sql .= " WHERE ID_AUTOR = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $where["ID_AUTOR"]);
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
		"NOMBRE" => $_POST["nmAuthor"],
		"APELLIDO" => $_POST["snAuthor"]
	);

	if($obj->addRegister("ss",$colArray)){
		$_SESSION['noerror'] = "El autor se ha creado correctamente";
		error_log("El usuario ".$_SESSION['user']." ha creado el autor ".$colArray["NOMBRE"].", ".$colArray["APELLIDO"]." correctamente.", 0);
		header("Location: authors.php");
	}else{
		$_SESSION['error'] = "Error al crear autor";
		error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear el autor ".$colArray["NOMBRE"].", ".$colArray["APELLIDO"].".", 0);
		header("Location: authors.php");
	}
}

//UPDATE
if(isset($_POST["edit"])){

	unset($_POST["edit"]);
	$colArray = array(
		"NOMBRE" => $_POST["nmAuthor"],
		"APELLIDO" => $_POST["snAuthor"]
	);

	$where = array(
		"ID_AUTOR" => $_POST["id"]
	);

	if($obj->updateRegister("ssd",$where,$colArray)){
		$_SESSION['noerror'] = "El autor se ha actualizado correctamente";
		error_log("El usuario ".$_SESSION['user']." ha actualizado el autor ".$colArray["NOMBRE"].", ".$colArray["APELLIDO"]." correctamente.", 0);
		header("Location: authors.php");
	}else{
		$_SESSION['error'] = "Error al actualizar autor";
		error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido actualizado el autor ".$colArray["NOMBRE"].", ".$colArray["APELLIDO"].".", 0);
		header("Location: authors.php");
	}
}

//DELETE
if(isset($_GET["delete"])){

	unset($_GET["delete"]);
	
	$where = array(
		"ID_AUTOR" => $_GET["id"]
	);

	if($obj->deleteRegister("d",$where)){
		$_SESSION['noerror'] = "El autor se ha eliminado correctamente";
		error_log("El usuario ".$_SESSION['user']." ha eliminado el autor con ID: ".$where["ID_AUTOR"]." correctamente.", 0);
		header("Location: authors.php");
	}else{
		$_SESSION['error'] = "Error al eliminar autor";
		error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido eliminar el autor con ID: ".$where["ID_AUTOR"].".", 0);
		header("Location: authors.php");
	}
}
?>