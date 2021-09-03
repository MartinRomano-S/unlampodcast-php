<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php
    $passEncrypt = "emese455";
	if(isset($_GET["delete"])){
		
		$idPodcast = rawurldecode(openssl_decrypt($_GET["id"],"AES-128-ECB",$passEncrypt));
		$extPort = "";
		$extEp = "";
		$errores = 0;
		$msgError = "";

		$conn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");
		
		$sql = "SELECT * FROM pu_episodios ";
		$sql .="WHERE ID_PODCAST = ? ";

		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$idPodcast);
			$result = $stmt->execute();
			$result = $stmt->get_result();
			if($result->num_rows > 0){
				$_SESSION['error'] = "El podcast tiene episodios asociados, para eliminar el podcast primero elimine sus episodios";
				error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido eliminar el podcast ".$idPodcast.". El podcast tiene episodios asociados, para eliminar el podcast primero elimine sus episodios", 0);
				header("Location: podcasts.php");
				return;
			}
		}else{
		   var_dump($conn->error);
		   $errores++;
		}


		$sql = "SELECT * FROM pu_podcasts ";
		$sql .="WHERE ID_PODCAST = ? ";

		if($stmt = $conn->prepare($sql)){
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i',$idPodcast);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$extPort = $row["ID_IMG_PORTADA"];
			$extEp = $row["ID_IMG_EPISODIO"];
		}else{
		   var_dump($conn->error);
		   $errores++;
		}
		$conn->begin_transaction();

    	$sql = "DELETE FROM pu_usuarios_podcast ";
		$sql .="WHERE ID_PODCAST = ? ";

		if($stmt = $conn->prepare($sql)){
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i',$idPodcast);
			$result = $stmt->execute();
		}else{
		   var_dump($conn->error);
		   $errores++;
		}

		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al eliminar usuarios de podcast;";}

    	$sql = "DELETE FROM pu_autores_podcast ";
		$sql .="WHERE ID_PODCAST = ? ";

		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$idPodcast);
			$result = $stmt->execute();
		}else{
		   var_dump($conn->error);
		   $errores++;
		}

		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al eliminar autores de podcast;";}

    	$sql = "DELETE FROM pu_temas_podcast ";
		$sql .="WHERE ID_PODCAST = ? ";

		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$idPodcast);
			$result = $stmt->execute();
		}else{
		   var_dump($conn->error);
		   $errores++;
		}

		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al eliminar temas de podcast;";}

    	$sql = "DELETE FROM pu_podcasts ";
		$sql .="WHERE ID_PODCAST = ? ";

		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$idPodcast);
			$result = $stmt->execute();
		}else{
		   var_dump($conn->error);
		   $errores++;
		}
		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al eliminar podcast;";}

		if($extPort != null && $extPort != ""){
			$myFile = "../images/podcasts/portada/".$idPodcast.".".$extPort;
			unlink($myFile);

		}
		if($extEp != null && $extEp != ""){
			$myFile = "../images/podcasts/episodio/".$idPodcast.".".$extEp;
			unlink($myFile);
		}

		if($errores == 0){
			$conn->commit();
			$_SESSION['noerror'] = "El podcast se ha eliminado correctamente";
			error_log("El usuario ".$_SESSION['user']." ha eliminado el podcast ".$idPodcast." correctamente", 0);
			header("Location: podcasts.php");
		}else{
			$conn->rollback();
			$msgError = rtrim($msgError, ';');
			$_SESSION['error'] = $msgError;
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido eliminar el podcast ".$idPodcast.".", 0);
			header("Location: podcasts.php");
		}
	}elseif(isset($_POST["idPodcastMod"])){

		$hayImagenPortada = 0;
		$hayImagenEpisodios = 0;
		$errores = 0;
		$idPodcast = rawurldecode(openssl_decrypt($_POST["idPodcastMod"],"AES-128-ECB",$passEncrypt));
		$msgError = "";
		$extPort = null;
		$extEp = null;
		$extPortOld = null ;
		$extEpOld = null;
		if($_FILES['imagenPortada']['size'] != 0) {
			$hayImagenPortada = 1;
			$fileName = $_FILES['imagenPortada']['name'];
			$extPort = pathinfo($fileName, PATHINFO_EXTENSION);
		}
		if($_FILES['imagenEpisodios']['size'] != 0) {
			$hayImagenEpisodios = 1;
			$fileName = $_FILES['imagenEpisodios']['name'];
			$extEp = pathinfo($fileName, PATHINFO_EXTENSION);
		}
		
		$conn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");

		$sql = "SELECT * FROM pu_podcasts ";
		$sql .=" WHERE ID_PODCAST = ?  ";
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$idPodcast);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			$extPortOld = $row["ID_IMG_PORTADA"];
			$extEpOld = $row["ID_IMG_EPISODIO"];
		}else{
		   var_dump($conn->error);
		   $errores++;
		}

		$conn->begin_transaction();
		$sql = "UPDATE pu_podcasts ";
		$sql .="SET NOMBRE = ?,DESCRIPCION = ?, LINK_SPOTIFY = ? ";
		if($extPort != null){
			$sql .= ",ID_IMG_PORTADA = ? ";
		}
		if($extEp != null){
			$sql .=  ",ID_IMG_EPISODIO = ? ";
		}
		$sql .="WHERE ID_PODCAST = ? ";
		if($stmt = $conn->prepare($sql)){
			if($extPort != null and $extEp !=null){
				$stmt->bind_param("sssssi",$_POST['nombre'],$_POST['desc'],$_POST['link'],$extPort,$extEp,$idPodcast);
			}elseif($extPort == null and $extEp !=null){
				$stmt->bind_param("ssssi",$_POST['nombre'],$_POST['desc'],$_POST['link'],$extEp,$idPodcast);
			}elseif($extPort != null and $extEp ==null){
				$stmt->bind_param("ssssi",$_POST['nombre'],$_POST['desc'],$_POST['link'],$extPort,$idPodcast);
			}elseif($extPort == null and $extEp ==null){
				$stmt->bind_param("sssi",$_POST['nombre'],$_POST['desc'],$_POST['link'],$idPodcast);
			}
			
			$result = $stmt->execute();
		}else{
		   var_dump($conn->error);
		   $errores++;
		}
		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al actualizar podcast;";}

		$sql = "DELETE FROM pu_usuarios_podcast ";
		$sql .="WHERE ID_PODCAST = ? ";
		if($stmt = $conn->prepare($sql)){
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i',$idPodcast);
			$result = $stmt->execute();
		}else{
		   var_dump($conn->error);
		   $errores++;
		}

		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error de base de datos;";}

		if ($_SESSION['type'] == 0 ){
			if(!isset($_POST['usuarios'])){
				$_POST['usuarios'] = array();
			}
			array_push($_POST['usuarios'], $_SESSION['user']);
		}

		foreach ($_POST['usuarios'] as $selectedOption){

	    	$sql = "INSERT INTO pu_usuarios_podcast ";
			$sql .="(ID_USUARIO,ID_PODCAST) ";
			$sql .="VALUES (?,?)";
			if($stmt = $conn->prepare($sql)){
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('si',$selectedOption,$idPodcast);
				$result = $stmt->execute();
			}else{
			   var_dump($conn->error);
			   $errores++;
			}
		}
		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al actualizar usuarios que pueden editar;";}

		$sql = "DELETE FROM pu_autores_podcast ";
		$sql .="WHERE ID_PODCAST = ? ";
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$idPodcast);
			$result = $stmt->execute();
		}else{
		   var_dump($conn->error);
		   $errores++;
		}
		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error de base de datos;";}

		foreach ($_POST['autores'] as $selectedOption){
	    	$sql = "INSERT INTO pu_autores_podcast ";
			$sql .="(ID_AUTOR,ID_PODCAST) ";
			$sql .="VALUES (?,?)";
			if($stmt = $conn->prepare($sql)){
				$stmt->bind_param('ii',$selectedOption,$idPodcast);
				$result = $stmt->execute();
			}else{
			   var_dump($conn->error);
			   $errores++;
			}
		}

		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al actualizar autores de podcast;";}

		$sql = "DELETE FROM pu_temas_podcast ";
		$sql .="WHERE ID_PODCAST = ? ";
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('i',$idPodcast);
			$result = $stmt->execute();
		}else{
		   var_dump($conn->error);
		   $errores++;
		}
		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error de base de datos;";}

		foreach ($_POST['temas'] as $selectedOption){
	    	$sql = "INSERT INTO pu_temas_podcast ";
			$sql .="(ID_TEMA,ID_PODCAST) ";
			$sql .="VALUES (?,?)";
			if($stmt = $conn->prepare($sql)){
				$stmt->bind_param('ii',$selectedOption,$idPodcast);
				$result = $stmt->execute();
			}else{
			   var_dump($conn->error);
			   $errores++;
			}
		}

		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al actualizar temas de podcast;";}
		
		if($errores == 0){
			$uploadPortadaOk = 1;
			$upladEpisodioOk = 1;
			if($hayImagenPortada == 1){
				$target_dir = "../images/podcasts/portada/";
				$fileName = $idPodcast;
				$ext = $extPort;
				$fileType = $_FILES['imagenPortada']['type'];
				$fileSize = $_FILES['imagenPortada']['size'];
				$fileTmp = $_FILES['imagenPortada']['tmp_name'];
				$fileStore = $target_dir.$fileName.".".$ext;
				$fileStoreOld = $target_dir.$fileName.".".$extPortOld;

				$allowed =  array('gif','png','jpg','jpeg');
				
				$ext = strtolower($ext);
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $fileTmp);
				finfo_close($finfo);

				$extTest = in_array($ext,$allowed);

				if(!$extTest) {
				    $msgError .= "El archivo de imagen de portada seleccionado no es una imagen.;";
				    $uploadPortadaOk = 0;
				}

				$mimesAllowed =  array('image/png','image/jpeg','image/jpg','image/gif');
				$mimeTest = in_array($mime,$mimesAllowed);

				if(!$mimeTest) {
				    $msgError .= "El archivo de imagen de portada seleccionado no es una imagen.;";
				    $uploadPortadaOk = 0;
				} 
				if ($fileSize > 5000000) {
				    $msgError .= "Imagen muy larga;";
				    $uploadPortadaOk = 0;
				}
				if($extPortOld != null && file_exists($fileStoreOld)){
					unlink($fileStoreOld);
				}
				if(move_uploaded_file($fileTmp, $fileStore)){
					echo"Archivo subido correctamente.";
				}else{
					$msgError .= "Error al mover imagen de portada;";
					$uploadPortadaOk = 0;
				}
			}
			
			if($hayImagenEpisodios == 1){
				$target_dir = "../images/podcasts/episodio/";
				$fileName = $idPodcast;
				$ext = $extEp;
				$fileType = $_FILES['imagenEpisodios']['type'];
				$fileSize = $_FILES['imagenEpisodios']['size'];
				$fileTmp = $_FILES['imagenEpisodios']['tmp_name'];
				$fileStore = $target_dir.$fileName.".".$ext;
				$fileStoreOld = $target_dir.$fileName.".".$extEpOld;

				$allowed =  array('gif','png','jpg','jpeg');
				
				$ext = strtolower($ext);
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $fileTmp);
				finfo_close($finfo);

				echo $extPort;
				echo $fileStore;
				$extTest = in_array($ext,$allowed);

				if(!$extTest) {
				    $msgError .= "El archivo de imagen de episodio seleccionado no es una imagen.;";
				    $upladEpisodioOk = 0;
				}

				$mimesAllowed =  array('image/png','image/jpeg','image/jpg','image/gif');
				$mimeTest = in_array($mime,$mimesAllowed);

				if(!$mimeTest) {
				    $msgError .= "El archivo de imagen de episodio seleccionado no es una imagen.;";
				    $upladEpisodioOk = 0;
				} 
				if ($fileSize > 5000000) {
				    $msgError .= "Imagen de episodio muy larga;";
				    $upladEpisodioOk = 0;
				}
				if($extEpOld != null && file_exists($fileStoreOld)){
					unlink($fileStoreOld);
				}
				if(move_uploaded_file($fileTmp, $fileStore)){
					echo 'Archivo subido correctamente.';
				}else{
					$msgError .= "Error al mover imagen de portada;";
					$upladEpisodioOk = 0;
				}
			}

			if ($uploadPortadaOk == 1 && $upladEpisodioOk == 1){
				$conn->commit();
				$_SESSION['noerror'] = "El podcast se ha actualizado correctamente";
			}else{
				$msgError = rtrim($msgError, ';');
				$_SESSION['error'] = $msgError;
			}
			error_log("El usuario ".$_SESSION['user']." ha actualizado el podcast ".$idPodcast." correctamente", 0);
			header("Location: podcasts.php");
		}else{
			$conn->rollback();
			$msgError = rtrim($msgError, ';');
			$_SESSION['error'] = $msgError;
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido actualizar el podcast ".$idPodcast, 0);
			header("Location: podcasts.php");
		}
		header("Location: podcasts.php");
	}else{
		 $hayImagenPortada = 0;
		$hayImagenEpisodios = 0;
		$errores = 0;
		$id_podcast = -1;
		$msgError = "";
		$extPort = null;
		$extEp = null;

		$todayh = getdate();
		$day = $todayh['mday'];
		$month = $todayh['mon'];
		$year = $todayh['year'];
		$hour = $todayh['hours'].":".$todayh['minutes'];
		$sqlDate = $year."-".$month."-".$day." ".$hour.":00";

		if($_FILES['imagenPortada']['size'] != 0) {
			$hayImagenPortada = 1;
			$fileName = $_FILES['imagenPortada']['name'];
			$extPort = pathinfo($fileName, PATHINFO_EXTENSION);
		}
		if($_FILES['imagenEpisodios']['size'] != 0) {
			$hayImagenEpisodios = 1;
			$fileName = $_FILES['imagenEpisodios']['name'];
			$extEp = pathinfo($fileName, PATHINFO_EXTENSION);
		}
		
		$conn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");
		$conn->begin_transaction();
		$sql = "INSERT INTO pu_podcasts ";
		$sql .="(NOMBRE,DESCRIPCION,LINK_SPOTIFY,ID_IMG_PORTADA,ID_IMG_EPISODIO,FEC_CREACION) ";
		$sql .="VALUES (?,?,?,?,?,?)";
		if($stmt = $conn->prepare($sql)){
			$stmt->bind_param('ssssss',$_POST['nombre'],$_POST['desc'],$_POST['link'],$extPort,$extEp, $sqlDate);
			$result = $stmt->execute();
			$idPodcast = mysqli_insert_id($conn);
		}else{
		   var_dump($conn->error);
		   $errores++;
		}
		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al insertar podcast;";}

		if ($_SESSION['type'] == 0 ){
			if(!isset($_POST['usuarios'])){
				$_POST['usuarios'] = array();
			}
			array_push($_POST['usuarios'], $_SESSION['user']);
		}

		foreach ($_POST['usuarios'] as $selectedOption){

	    	$sql = "INSERT INTO pu_usuarios_podcast ";
			$sql .="(ID_USUARIO,ID_PODCAST) ";
			$sql .="VALUES (?,?)";
			if($stmt = $conn->prepare($sql)){
				$stmt->bind_param('si',$selectedOption,$idPodcast);
				$result = $stmt->execute();
			}else{
			   var_dump($conn->error);
			   $errores++;
			}
		}
		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al insertar usuarios que pueden editar;";}

		foreach ($_POST['autores'] as $selectedOption){
	    	$sql = "INSERT INTO pu_autores_podcast ";
			$sql .="(ID_AUTOR,ID_PODCAST) ";
			$sql .="VALUES (?,?)";
			if($stmt = $conn->prepare($sql)){
				$stmt->bind_param('ii',$selectedOption,$idPodcast);
				$result = $stmt->execute();
			}else{
			   var_dump($conn->error);
			   $errores++;
			}
		}

		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al insertar autores de podcast;";}

		foreach ($_POST['temas'] as $selectedOption){
	    	$sql = "INSERT INTO pu_temas_podcast ";
			$sql .="(ID_TEMA,ID_PODCAST) ";
			$sql .="VALUES (?,?)";
			if($stmt = $conn->prepare($sql)){
				$stmt->bind_param('ii',$selectedOption,$idPodcast);
				$result = $stmt->execute();
			}else{
			   var_dump($conn->error);
			   $errores++;
			}
		}

		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al insertar temas de podcast;";}
		
		if($errores == 0){
			$uploadPortadaOk = 1;
			$upladEpisodioOk = 1;
			if($hayImagenPortada == 1){
				$target_dir = "../images/podcasts/portada/";
				$fileName = $idPodcast;
				$ext = $extPort;
				$fileType = $_FILES['imagenPortada']['type'];
				$fileSize = $_FILES['imagenPortada']['size'];
				$fileTmp = $_FILES['imagenPortada']['tmp_name'];
				$fileStore = $target_dir.$fileName.".".$ext;

				$allowed =  array('gif','png','jpg','jpeg');
				
				$ext = strtolower($ext);
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $fileTmp);
				finfo_close($finfo);

				$extTest = in_array($ext,$allowed);

				if(!$extTest) {
				    $msgError .= "El archivo de imagen de portada seleccionado no es una imagen.;";
				    $uploadPortadaOk = 0;
				}

				$mimesAllowed =  array('image/png','image/jpeg','image/jpg','image/gif');
				$mimeTest = in_array($mime,$mimesAllowed);

				if(!$mimeTest) {
				    $msgError .= "El archivo de imagen de portada seleccionado no es una imagen.;";
				    $uploadPortadaOk = 0;
				} 
				if ($fileSize > 5000000) {
				    $msgError .= "Imagen muy larga;";
				    $uploadPortadaOk = 0;
				}
				if(move_uploaded_file($fileTmp, $fileStore)){
					echo"Archivo subido correctamente.";
				}else{
					$msgError .= "Error al mover imagen de portada;";
					$uploadPortadaOk = 0;
				}
			}
			
			if($hayImagenEpisodios == 1){
				$target_dir = "../images/podcasts/episodio/";
				$fileName = $idPodcast;
				$ext = $extEp;
				$fileType = $_FILES['imagenEpisodios']['type'];
				$fileSize = $_FILES['imagenEpisodios']['size'];
				$fileTmp = $_FILES['imagenEpisodios']['tmp_name'];
				$fileStore = $target_dir.$fileName.".".$ext;

				$allowed =  array('gif','png','jpg','jpeg');
				
				$ext = strtolower($ext);
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $fileTmp);
				finfo_close($finfo);

				$extTest = in_array($ext,$allowed);

				if(!$extTest) {
				    $msgError .= "El archivo de imagen de episodio seleccionado no es una imagen.;";
				    $upladEpisodioOk = 0;
				}

				$mimesAllowed =  array('image/png','image/jpeg','image/jpg','image/gif');
				$mimeTest = in_array($mime,$mimesAllowed);

				if(!$mimeTest) {
				    $msgError .= "El archivo de imagen de episodio seleccionado no es una imagen.;";
				    $upladEpisodioOk = 0;
				} 
				if ($fileSize > 5000000) {
				    $msgError .= "Imagen de episodio muy larga;";
				    $upladEpisodioOk = 0;
				}
				if(move_uploaded_file($fileTmp, $fileStore)){
					echo 'Archivo subido correctamente.';
				}else{
					$msgError .= "Error al mover imagen de portada;";
					$upladEpisodioOk = 0;
				}
			}

			if ($uploadPortadaOk == 1 && $upladEpisodioOk == 1){
				$conn->commit();
				$_SESSION['noerror'] = "El podcast se ha creado correctamente";
				error_log("El usuario ".$_SESSION['user']." ha creado el podcast ".$idPodcast, 0);
			}else{
				$msgError = rtrim($msgError, ';');
				$_SESSION['error'] = $msgError;
				error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear el podcast ".$idPodcast, 0);
			}
			header("Location: podcasts.php");
		}else{
			$conn->rollback();
			$msgError = rtrim($msgError, ';');
			$_SESSION['error'] = $msgError;
			error_log("[ERROR] El usuario ".$_SESSION['user']." no ha podido crear el podcast ".$idPodcast, 0);
			header("Location: podcasts.php");
		}
		header("Location: podcasts.php");
	}
 ?>
