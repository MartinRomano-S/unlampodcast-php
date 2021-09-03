<?php
  if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
  } 

  if(isset($_SESSION['type'])){
  	if($_SESSION['type']==0){
  		header("Location: ./admin.php");
  	}
  }
?>
<?php 

include "db.php";

class User extends Database {

	private $table = "pu_usuarios";

	public function addRegister($types, $fields){

		$sql = "INSERT INTO ".$this->table;
		$sql .= " VALUES (?,?,?,?,?,?)";
		$stmt = $this->conn->prepare($sql);
		$hash = password_hash ($fields["CLAVE"],PASSWORD_DEFAULT);
		$intentos = 0;

		$stmt->bind_param($types."i", $fields["ID_USUARIO"],$hash,$fields["MAIL"],$fields["SUPER"],$fields["BLOQUEADO"],$intentos);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Usuario creado correctamente.";
			error_log("El usuario ".$_SESSION['user']." ha creado el usuario ".$fields["ID_USUARIO"]. " correctamente.", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al crear el usuario;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear el usuario ".$fields["ID_USUARIO"], 0);
		}

		return $result;
	}

	public function updateRegister($types, $where, $fields){

		$sql = "UPDATE ".$this->table;
		$sql .= " SET MAIL=?, SUPER=?, BLOQUEADO=?, INTENTOS = 0";
		$sql .= " WHERE ID_USUARIO = ?";
		$stmt = $this->conn->prepare($sql);

		$stmt->bind_param($types, $fields["MAIL"],$fields["SUPER"],$fields["BLOQUEADO"],$where["ID_USUARIO"]);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Usuario actualizado correctamente.";
			error_log("El usuario ".$_SESSION['user']." ha actualizado el usuario ".$fields["ID_USUARIO"]. " correctamente.", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al actualizar el usuario;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido actualizar el usuario ".$fields["ID_USUARIO"], 0);
		}

		return $result;
	}

	public function updatePWD($types, $where, $fields){

		$sql = "UPDATE ".$this->table;
		$sql .= " SET CLAVE = ?, INTENTOS = 0 , BLOQUEADO = 0";
		$sql .= " WHERE ID_USUARIO = ?";
		$stmt = $this->conn->prepare($sql);
		$hash = password_hash ($fields["CLAVE"],PASSWORD_DEFAULT);

		$stmt->bind_param($types,$hash,$where["ID_USUARIO"]);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Contraseñactualizada correctamente.";
			error_log("El usuario ".$_SESSION['user']." ha actualizado la contraseña del usuario ".$where["ID_USUARIO"]. " correctamente.", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al actualizar la contraseña";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido actualizar la contraseña del usuario ".$where["ID_USUARIO"], 0);
		}

		return $result;
	}

	public function deleteRegister($where){

		$sql = "DELETE FROM ".$this->table;
		$array = array();
		$cond = "";

		foreach ($where as $key => $value) {
			$cond .= $key." = '".$value."' AND ";
		}

		$cond = substr($cond, 0, -5);
		$sql .= " WHERE ".$cond;
		$stmt = $this->conn->prepare($sql);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Usuario eliminado correctamente.";
			error_log("El usuario ".$_SESSION['user']." ha eliminado el usuario ".$_GET["id"]. " correctamente.", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al eliminar el usuario;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido eliminar el usuario ".$_GET["id"], 0);
		}

		return $result;
	}

	public function loadAll(){

		$sql = "SELECT ID_USUARIO, MAIL, SUPER, BLOQUEADO FROM ".$this->table;
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

		$sql = "SELECT ID_USUARIO, MAIL, SUPER, BLOQUEADO FROM ".$this->table;
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

$obj = new User;

//INSERT
if(isset($_POST["insert"])){

	unset($_POST["insert"]);

	$colArray = array(
		"ID_USUARIO" => $_POST["idUser"],
		"MAIL" => $_POST["mail"],
		"CLAVE" => $_POST["pwd"],
		"SUPER" => $_POST["flagS"],
		"BLOQUEADO" => $_POST["flagB"]
	);

	if($obj->addRegister("sssii",$colArray)){
		header("Location: users.php");
	}
}

//UPDATE
if(isset($_POST["edit"])){

	unset($_POST["edit"]);
	$colArray = array(
		"ID_USUARIO" => $_POST["idUser"],
		"MAIL" => $_POST["mail"],
		"SUPER" => $_POST["flagS"],
		"BLOQUEADO" => $_POST["flagB"]
	);

	$where = array(
		"ID_USUARIO" => $_POST["idUserOld"]
	);

	if($obj->updateRegister("siis",$where,$colArray)){
		header("Location: users.php");
	}
}

//CHANGE
if(isset($_POST["change"])){

	unset($_POST["change"]);

	if($_POST["pwd"]<>$_POST["rppwd"]){
		$_SESSION['error'] = "Las contraseñas no coinciden;";
		header("Location: users.php?change=1&idUser=".$_POST["idUser"]);
	}

	$colArray = array(
		"CLAVE" => $_POST["pwd"]
	);

	$where = array(
		"ID_USUARIO" => $_POST["idUser"]
	);

	if($obj->updatePWD("ss",$where,$colArray)){
		header("Location: users.php");
	}
}

//DELETE
if(isset($_GET["delete"])){

	unset($_GET["delete"]);
	
	$where = array(
		"ID_USUARIO" => $_GET["id"]
	);

	if($obj->deleteRegister($where)){
		header("Location: users.php");
	}
}
?>