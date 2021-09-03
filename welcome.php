<?php
	if(isset($_SESSION['user'])){
		if ($_SESSION['type'] == 1){
			if (isset($_POST['upload'])){ 

				$fileName = $_FILES['file']['name'];
				$fileType = $_FILES['file']['type'];
				$fileSize = $_FILES['file']['size'];
				$fileTmp = $_FILES['file']['tmp_name'];
				$fileStore = "images/".$fileName;

				$allowed =  array('gif','png','jpg','jpeg');
				$ext = pathinfo($fileName, PATHINFO_EXTENSION);
				$ext = strtolower($ext);
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mime = finfo_file($finfo, $fileTmp);
				finfo_close($finfo);


				$longTest = (bool) ((mb_strlen($fileName,"UTF-8") < 225) ? true : false);

				if(!$longTest){
					echo "El nombre del archivo excede los caracteres permitidos.";
					return;
				}

				$extTest = in_array($ext,$allowed);

				if(!$extTest) {
				    echo 'El archivo seleccionado no es una imagen.';
				    return;
				}

				$mimesAllowed =  array('image/png','image/jpeg','image/jpg','image/gif');
				$mimeTest = in_array($mime,$mimesAllowed);

				if(!$mimeTest) {
				    echo 'El archivo seleccionado no es una imagen.';
				    return;
				} 

				if(move_uploaded_file($fileTmp, $fileStore)){
					echo 'Archivo subido correctamente.';
				}
			}
		}else{
			header("Location: index.html");
		}
	}else{
		header("Location: index.html");
	}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Podcast UNLaM</title>
</head>
<body>
<h1>Bienvenido a Podcast UNLaM!</h1>
<p>Esto es una prueba de login</p><br><br>
<form action="?" method="POST" enctype="multipart/form-data">
	<label>Subida de archivos</label>
	<input type="file" name="file"><br><br>
	<input type="submit" name="upload" value="Subir">
</form>
</body>
</html>