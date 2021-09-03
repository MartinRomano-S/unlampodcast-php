<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php
	require_once('./dao/dao_programa.php');
	require_once('./obj/programa.php');

	$programaDAO =new DAOPrograma();
	$programa = new Programa();

	$programa->setId($_POST['idProgram']);
	$programa->setNombre($_POST['inNombre']);
	$programa->setDescripcion($_POST['inDesc']);
	$programa->setIdImagen($_POST['idImg']);
	$programa->setLinkVivo($_POST['link']);
	$errores = 0;
	$msgError = "";

	if(isset($_GET["delete"])){
		$idProgram = $_GET['id'];
		$programToDelete = $programaDAO->getPrograma($idProgram);
		$extImg = $programToDelete->getIdImagen();
		$conn = $programaDAO->eliminar($idProgram);

		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al eliminar programa;";}

		if($extImg != null && $extImg != ""){
			$myFile = "../images/podcasts/programa/".$idProgram.".".$extImg;
			unlink($myFile);
		}

		if($errores == 0){
			$conn->commit();
			$_SESSION['noerror'] = "El programa se ha eliminado correctamente";
		}else{
			$conn->rollback();
			$msgError = rtrim($msgError, ';');
			$_SESSION['error'] = $msgError;
		}
	}elseif(isset($_POST["edit"])){

	}elseif(isset($_POST["insert"])){
		unset($_POST["insert"]);
		echo "hola";
		$hayImagen = 0;

		if($_FILES['inImgFile']['size'] != 0) {
			$hayImagen = 1;
			$fileName = $_FILES['inImgFile']['name'];
			$ext = pathinfo($fileName, PATHINFO_EXTENSION);
		}
		$programa->setIdImagen($ext);
		$tmpPrograma = $programa;
		$conn = $programaDAO->insertar($tmpPrograma);
		$idProgram = mysqli_insert_id($conn);
		if($conn->sqlstate != "00000"){$errores++;$msgError .= "Error al insertar programa;";}

		if($errores == 0){
			$upload = 1;
			if($hayImagen == 1){
				$target_dir = "../images/podcasts/programa/";
				$fileName = $idProgram;
				$fileType = $_FILES['inImgFile']['type'];
				$fileSize = $_FILES['inImgFile']['size'];
				$fileTmp = $_FILES['inImgFile']['tmp_name'];
				$fileStore = $target_dir.$fileName.".".$ext;

				$allowed =  array('gif','png','jpg','jpeg');
				
				$ext = strtolower($ext);
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $fileTmp);
				finfo_close($finfo);

				$extTest = in_array($ext,$allowed);

				if(!$extTest) {
				    $msgError .= "El archivo de imagen seleccionado no es una imagen.;";
				    $uploadPortadaOk = 0;
				}

				$mimesAllowed =  array('image/png','image/jpeg','image/jpg','image/gif');
				$mimeTest = in_array($mime,$mimesAllowed);

				if(!$mimeTest) {
				    $msgError .= "El archivo de imagen seleccionado no es una imagen.;";
				    $uploadPortadaOk = 0;
				} 
				if ($fileSize > 5000000) {
				    $msgError .= "Imagen muy larga;";
				    $uploadPortadaOk = 0;
				}
				if(move_uploaded_file($fileTmp, $fileStore)){
					echo"Archivo subido correctamente.";
				}else{
					$msgError .= "Error al mover imagen;";
					$uploadPortadaOk = 0;
				}
			}
		}else{
			$conn->rollback();
			$msgError = rtrim($msgError, ';');
			$_SESSION['error'] = $msgError;
			echo $msgError;
		}
	}
	#header("Location: programs.php");
?>