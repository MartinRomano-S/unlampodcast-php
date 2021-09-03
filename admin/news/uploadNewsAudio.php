<?php

    require_once('../utils/asyncFileUploader.php');
    require_once('../dao/dao_news.php');
    require_once('../obj/news.php');

    if(!isset($_SESSION['user'])){
        header("Location: ../login.php");
    }
    
    $result = array();
    
    if(!isset($_POST['extraData'])) {
        $result['success'] = false;
        $result['general_message'] = "Se produjo un error al subir el archivo.";
        exit(json_encode($result));
    }
    
    $DAONews = new DAONews();
    $loggedUser = $_SESSION['user'];
    $noticia = $DAONews->get($_POST['extraData'], $loggedUser);
    
    if($noticia->getId()) {
        $result = AsyncFileUploader::uploadFile($_FILES['file'], AsyncFileUploader::AUDIO, "../../news/audio/", $noticia->getId());
        
        if($result['success'] == true) {
            $res = $DAONews->updateAudioPath($_POST['extraData'], $loggedUser, $result['destination_path']);
            
            if(!$res) {
                unset($result['destination_path']);
                $result['success'] = false;
                $result['general_message'] = "Se produjo un error al subir el archivo. No se pudo actualizar la noticia.";
            }
        }
    } else {
        $result['success'] = false;
        $result['general_message'] = "Se produjo un error al subir el archivo. No se pudo encontrar la noticia.";
    }
    
    exit(json_encode($result));
?>