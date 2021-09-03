<?php
if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
}

require_once('./dao/dao_horarios.php');
require_once('./obj/horario.php');
 
$DAOHorario = new DAOHorario();
$horario = new Horario();
 
 	if(isset($_POST['insert'])) {
		$horario->setIdPrograma($_POST['programa']);
		$horario->setDia($_POST['dia']);
		$horario->setHoraInicio($_POST['timepickerI'].":00");
		$horario->setHoraFin($_POST['timepickerF'].":00");
		$DAOHorario->insertar($horario);
		header('Location: horarios.php');

	} elseif ($_GET['delete']=='1') {
		$DAOHorario->eliminar($_GET['id'], $_GET['idp']);
		header('Location: horarios.php');
	}

 /*elseif(isset($_POST['actualizar'])){
		$libro->setId($_POST['id']);
		$libro->setNombre($_POST['nombre']);
		$libro->setAutor($_POST['autor']);
		$libro->setAnio_edicion($_POST['edicion']);
		$crud->actualizar($libro);
		header('Location: index.php');

	} elseif ($_GET['accion']=='e') {
		$crud->eliminar($_GET['id']);
		header('Location: index.php');		

	} elseif($_GET['accion']=='a'){
		header('Location: actualizar.php');
	}*/
?>