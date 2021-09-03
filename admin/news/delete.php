<?php
    require_once('../dao/dao_news.php');
    require_once('../obj/news.php');

    if(!isset($_SESSION['user'])){
        header("Location: ../../login.php");
    }
    
    $result = array();
    
    if(!isset($_POST['id'])) {
        $result['success'] = false;
        $result['general_message'] = "No se pudo eliminar la noticia.";
        exit(json_encode($result));
    }
    
    $DAONews = new DAONews();
    $loggedUser = $_SESSION['user'];
    $noticia = $DAONews->get($_POST['id'], $loggedUser);
    
    if($noticia->getId()) {
        $res = $DAONews->delete($_POST['id'], $loggedUser);
                
        if($res) {
            
            $imgPath = $noticia->getPath_imagen();
            $audioPath = $noticia->getPath_audio();
            $basePath = "../../news/";
            
            if($imgPath) {
                $imgPath = $basePath."img/".$imgPath;
                unlink($imgPath);
            }
                
            if($audioPath) {
                $audioPath = $basePath."audio/".$audioPath;
                unlink($audioPath);
            }
                
            $result['success'] = true;
            $result['general_message'] = "Noticia eliminada correctamente.";
        } else {
            $result['success'] = false;
            $result['general_message'] = "Se produjo un error al eliminar la noticia.";
        }
    } else {
        $result['success'] = false;
        $result['general_message'] = "Se produjo un error al eliminar la noticia: La noticia ya había sido eliminada.";
    }
    
    exit(json_encode($result));
?>