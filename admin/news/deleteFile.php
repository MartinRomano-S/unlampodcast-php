<?php
    require_once('../dao/dao_news.php');
    require_once('../obj/news.php');

    if(!isset($_SESSION['user'])){
        header("Location: ../../login.php");
    }
    
    $result = array();
    
    if(!isset($_POST['id'])) {
        $result['success'] = false;
        $result['general_message'] = "No se pudo eliminar el archivo.";
        exit(json_encode($result));
    }
    
    if(!isset($_POST['type'])) {
        $result['success'] = false;
        $result['general_message'] = "No se pudo eliminar el archivo.";
        exit(json_encode($result));
    }
    
    $DAONews = new DAONews();
    $loggedUser = $_SESSION['user'];
    $noticia = $DAONews->get($_POST['id'], $loggedUser);
    
    if($noticia->getId()) {
        $basePath = "../../news/";
        $pathToDelete;
        
        if($_POST['type'] == 'img') {
            $pathToDelete = $basePath."img/".$noticia->getPath_imagen();
            $DAONews->deleteImage($_POST['id'], $loggedUser);
        }
            
        if($_POST['type'] == 'audio') {
            $pathToDelete = $basePath."audio/".$noticia->getPath_audio();
            $DAONews->deleteAudio($_POST['id'], $loggedUser);
        }
  
        if($pathToDelete)
            unlink($pathToDelete);

        $result['success'] = true;
        $result['general_message'] = "Archivo eliminado correctamente.";
    } else {
        $result['success'] = false;
        $result['general_message'] = "Se produjo un error al eliminar el archivo: La noticia ha sido eliminada.";
    }
    
    exit(json_encode($result));
?>