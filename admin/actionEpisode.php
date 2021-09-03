<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php 

include "db.php";

class Episode extends Database {

	private $table = "pu_episodios";

	public function addRegister($types, $fields){

		$fileName = $_FILES['episodeAudio']['name'];
		$fileType = $_FILES['episodeAudio']['type'];
		$fileSize = $_FILES['episodeAudio']['size'];
		$fileTmp = $_FILES['episodeAudio']['tmp_name'];
		$route = "../episodes/";


		$allowed =  array('mp3','wav');
		$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		$ext = strtolower($ext);
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $fileTmp);
		finfo_close($finfo);

		$longTest = (bool) ((mb_strlen($fileName,"UTF-8") < 225) ? true : false);

		if(!$longTest){
			$_SESSION['error'] =  "El archivo supera la longitud de nombre permitida;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear un episodio para el podcast ".$fields["ID_PODCAST"].": El archivo supera la longitud de nombre permitida.", 0);
			header("Location: episodes.php?pc=".$fields["ID_PODCAST"]);
		}

		$extTest = in_array($ext,$allowed);

		if(!$extTest) {
		    $_SESSION['error'] =  "El archivo seleccionado no es un audio;";
		    error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear un episodio para el podcast ".$fields["ID_PODCAST"].": El archivo seleccionado no es un audio [EXT].", 0);
			header("Location: episodes.php?pc=".$fields["ID_PODCAST"]);
		}

		$mimesAllowed = array('audio/mpeg','audio/x-wav');
		$mimeTest = in_array($mime,$mimesAllowed);

		if(!$mimeTest) {
		    $_SESSION['error'] =  "El archivo seleccionado no es un audio;";
		    error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear un episodio para el podcast ".$fields["ID_PODCAST"].": El archivo seleccionado no es un audio [MIME].", 0);
			header("Location: episodes.php?pc=".$fields["ID_PODCAST"]);
		} 

		$sql = "SELECT MAX(ID_EPISODIO) + 1 AS NEW_ID FROM ".$this->table;
		$sql .= " WHERE ID_PODCAST = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("d", $fields["ID_PODCAST"]);
		$stmt->execute();
		$result = $stmt->get_result();

		$idEpisode = 1;

		if($row = $result->fetch_assoc()){
			if(!is_null($row["NEW_ID"])){
				$idEpisode = $row["NEW_ID"];
			}
		}

		$fileStore = "P".$fields["ID_PODCAST"]."E".$idEpisode.".".$ext;

		if(move_uploaded_file($fileTmp,$route.$fileStore)){
			$_SESSION['noerror'] = "El episodio se ha creado correctamente";
			error_log("El usuario ".$_SESSION['user']." ha subido el archivo del episodio para el podcast ".$fields["ID_PODCAST"]." correctamente.", 0);
		}
		

		$sql = "INSERT INTO ".$this->table;
		$sql .= "(ID_EPISODIO,TITULO,DESCRIPCION,ID_PODCAST,FECHA_PUBLICACION,ID_AUDIO) ";
		$sql .= "VALUES (?,?,?,?,?,?)";

		$epconn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");
		$stmt = $epconn->prepare($sql);
		$stmt->bind_param("i".$types."s", $idEpisode, $fields["TITULO"], $fields["DESCRIPCION"], $fields["ID_PODCAST"], $fields["FECHA_PUBLICACION"], $fileStore);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Episodio creado correctamente.";
			error_log("El usuario ".$_SESSION['user']." ha creado del episodio para el podcast ".$fields["ID_PODCAST"]." correctamente.", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al crear el episodio;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear un episodio para el podcast ".$fields["ID_PODCAST"].".", 0);
		}

		mysqli_close($epconn);
		return $result;
	}

	public function updateRegister($types,$where, $fields){

		$sql = "UPDATE ".$this->table;
		$sql .= " SET TITULO=?, DESCRIPCION=?, FECHA_PUBLICACION=? ";
		$sql .= "WHERE ID_PODCAST = ? AND ID_EPISODIO = ?";
		$stmt = $this->conn->prepare($sql);

		$stmt->bind_param($types."dd", $fields["TITULO"], $fields["DESCRIPCION"], $fields["FECHA_PUBLICACION"],$where["ID_PODCAST"],$where["ID_EPISODIO"]);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Episodio actualizado correctamente.";
			//error_log("El usuario ".$_SESSION['user']." ha actualizado el episodio ".$where["ID_EPISODIO"]." para el podcast ".$fields["ID_PODCAST"].".", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al actualizar el episodio;";
			//error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido actualizar el episodio ".$where["ID_EPISODIO"]." para el podcast ".$fields["ID_PODCAST"].".", 0);
		}

		return $result;
	}

	public function deleteRegister($types, $where){

		//primero debemos buscar el id del archivo
		$sql = "SELECT * FROM ".$this->table." WHERE ID_PODCAST = ? AND ID_EPISODIO = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $where["ID_PODCAST"], $where["ID_EPISODIO"]); 
		$stmt->execute();
		$result = $stmt->get_result();

		if($row = $result->fetch_assoc()){
			unlink("../episodes/".$row["ID_AUDIO"]);
		}
		$sql = "DELETE FROM ".$this->table;
		$sql .= " WHERE ID_PODCAST = ? AND ID_EPISODIO = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param($types, $where["ID_PODCAST"], $where["ID_EPISODIO"]);
		$result = $stmt->execute();

		if($result){
			$_SESSION['noerror'] = "Episodio borrado correctamente.";
			error_log("El usuario ".$_SESSION['user']." ha eliminado el episodio ".$where["ID_EPISODIO"]." para el podcast ".$where["ID_PODCAST"].".", 0);
		} else {
			$_SESSION['error'] =  "Se produjo un error al borrar el episodio;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido eliminar el episodio ".$where["ID_EPISODIO"]." para el podcast ".$where["ID_PODCAST"].".", 0);
		}

		return $result;
	}

	public function loadAll($firstPC){

		$sql = "";
		$array = array();

		//si tenemos uno seleccionado 
		if(!empty($firstPC)){

			//si no es super, debemos ver si el podcast seleccionado es editable por el usuario
			if($_SESSION['type']<>1){
				$sql = "SELECT B.ID_PODCAST, B.NOMBRE FROM pu_usuarios_podcast A INNER JOIN pu_podcasts B ON A.ID_PODCAST = B.ID_PODCAST WHERE A.ID_USUARIO = ? AND A.ID_PODCAST = ?";
			    $stmt = $this->conn->prepare($sql);
			    $stmt->bind_param("sd", $_SESSION['user'],$firstPC);
			    $stmt->execute();
			    $result = $stmt->get_result();

			    if(!$result){
			    	return $array;
			    }
			}

			$sql = "SELECT * FROM ".$this->table." WHERE ID_PODCAST = ?";
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("d",$firstPC);
			$stmt->execute();
			$result = $stmt->get_result();

			while($row = $result->fetch_assoc()){
				$array[] = $row;
			}
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

$obj = new Episode;

//INSERT
if(isset($_POST["isInsert"])){

	unset($_POST["isInsert"]);

	$todayh = getdate();
	$day = $todayh['mday'];
	$month = $todayh['mon'];
	$year = $todayh['year'];
	$hour = $todayh['hours'].":".$todayh['minutes'];


	if(!empty($_POST["datepicker"])){
		$date = explode("/", $_POST["datepicker"]);
		$day = $date[1];
		$month = $date[0];
		$year = $date[2];
	}

	if(!empty($_POST["timepicker"])){
		$hour = $_POST["timepicker"];
	}

	if(!checkdate($month, $day, $year)){
		$_SESSION['error'] =  "La fecha introducida es incorrecta;";
		error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear un episodio para el podcast ".$fields["ID_PODCAST"].": La fecha introducida es incorrecta.", 0);
		header("Location: episodes.php?pc=".$_POST["id"]);
	}

	$sqlDate = $year."-".$month."-".$day." ".$hour.":00";

	$colArray = array(
		"ID_PODCAST" => $_POST["selPodcast"],
		"TITULO" => $_POST["nmTitle"],
		"DESCRIPCION" => $_POST["descEpisode"],
		"FECHA_PUBLICACION" => $sqlDate
	);

	if($obj->addRegister("ssis",$colArray)){
		header("Location: episodes.php?pc=".$_POST["selPodcast"]);
	}
}

//UPDATE
if(isset($_POST["isEdit"])){

	unset($_POST["isEdit"]);
	$day = 0;
	$month = 0;
	$year = 0;
	$hour = "00:00";

	if(!empty($_POST["datepicker"])){
		$date = explode("/", $_POST["datepicker"]);
		$day = $date[1];
		$month = $date[0];
		$year = $date[2];
	}

	if(!empty($_POST["timepicker"])){
		$hour = $_POST["timepicker"];
	}

	if($day <> 0 && $month <> 0 && $year <> 0){
		if(!checkdate($month, $day, $year)){
			$_SESSION['error'] =  "La fecha introducida es incorrecta;";
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido actualizar un episodio para el podcast ".$fields["ID_PODCAST"].": La fecha introducida es incorrecta.", 0);
			header("Location: episodes.php?pc=".$_POST["id"]);
		}
	}

	$sqlDate = $year."-".$month."-".$day." ".$hour.":00";

	$colArray = array(
		"TITULO" => $_POST["nmTitle"],
		"DESCRIPCION" => $_POST["descEpisode"],
		"FECHA_PUBLICACION" => $sqlDate
	);

	$where = array(
		"ID_PODCAST" => $_POST["id"],
		"ID_EPISODIO" => $_POST["ide"]
	);

	if($obj->updateRegister("sss",$where,$colArray)){
		header("Location: episodes.php?pc=".$_POST["id"]);
	}
}

//DELETE
if(isset($_GET["delete"])){

	unset($_GET["delete"]);
	
	$where = array(
		"ID_PODCAST" => $_GET["id"],
		"ID_EPISODIO" => $_GET["idep"]
	);

	if($obj->deleteRegister("dd",$where)){
		header("Location: episodes.php?pc=".$_GET["id"]);
	}
}
?>