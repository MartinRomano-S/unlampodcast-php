<?php 

    require_once('../dao/dao_authors.php');
    
    if(!isset($_SESSION['user'])){
        header("Location: ../login.php");
    }
    
    $DAOAuthors = new DAOAuthors();
    
    exit(json_encode($DAOAuthors->getAllInJSON()));
?>