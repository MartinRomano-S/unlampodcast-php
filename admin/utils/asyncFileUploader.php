<?php

    class AsyncFileUploader {
        
        const IMAGE = 'image';
        const AUDIO = 'audio';
        
        public static function uploadFile($file, $type, $destination, $filename) {
            $fileName = $_FILES['file']['name'];
        	$fileType = $_FILES['file']['type'];
        	$fileSize = $_FILES['file']['size'];
        	$fileTmp = $_FILES['file']['tmp_name'];
        	//$type = isset($_POST['type']) ? $_POST['type'] : '';
        	//$route = "../news/";
        
        	$allowed = array('png','jpg','jpeg');
        	$mimesAllowed = array('image/png','image/jpeg','image/jpg');
        	
        	if($type == 'audio') {
        	    $allowed = array('mp3','wav');
        	    $mimesAllowed = array('audio/mpeg','audio/x-wav');
        	}
        	
        	$ext = pathinfo($fileName, PATHINFO_EXTENSION);
        	$ext = strtolower($ext);
        	$finfo = finfo_open(FILEINFO_MIME_TYPE);
        	$mime = finfo_file($finfo, $fileTmp);
        	finfo_close($finfo);
        
            $response = array();
            $response['success'] = false;
            $response['general_message'] = "Error al subir el archivo: ";
        
        	$longTest = (bool) ((mb_strlen($fileName,"UTF-8") < 225) ? true : false);
        
        	if(!$longTest){
                $response['general_message'] .= "El archivo supera la longitud de nombre permitida";
                return $response;
        	}
        
        	$extTest = in_array($ext,$allowed);
        
        	if(!$extTest) {
        		$response['general_message'] .= "La extensión de archivo no está permitida.";
                return $response;
        	}
        
        	$mimeTest = in_array($mime,$mimesAllowed);
        
        	if(!$mimeTest) {
        		$response['general_message'] .= "El tipo de archivo no está permitido.";
                return $response;
        	} 
        
        	$fileStore = $filename.".".$ext;
        
        	if(move_uploaded_file($fileTmp,$destination.$fileStore)){
                $response['success'] = true;
        		$response['general_message'] = "Archivo guardado correctamente.";
        		$response['destination_path'] = $fileStore;
                return $response;
        	}
        	
        	$response['general_message'] .= "No se pudo subir el archivo, por favor contacte con un administrador.";
            return $response;
        }
    }
?>