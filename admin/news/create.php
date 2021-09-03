<?php 
    require_once('../dao/dao_news.php');

    if(!isset($_SESSION['user'])){
        header("Location: ../login.php");
    }
    
    $DAONews = new DAONews();
  
    $response = array();
    
    if(isset($_POST['titulo']) && $_POST['titulo'] != '') {
        
        $response['success'] = true;
        $response['general_message'] = 'Borrador creado correctamente.';
        $response['id'] = $DAONews->insertar($_SESSION['user'], $_POST['titulo']);
        exit(json_encode($response));
    } else {
        $response['success'] = false;
        $response['general_message'] = "El título no puede estar vacío";
        exit(json_encode($response));
    }
    
?>