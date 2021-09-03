<?php
	if(isset($_SESSION['user'])){
		error_log("El usuario ".$_SESSION['user']." se ha desconectado", 0);
		unset($_SESSION['user']);
	}

	if(isset($_SESSION['type'])){
		unset($_SESSION['type']);
	}
	
	header("Location: ./login.php");
?>