<?php
  if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
  } else {
  	header("Location: ./admin.php");
  }
?>