<?php 

    require_once('../dao/dao_news.php');
    
    if(!isset($_SESSION['user'])){
        header("Location: ../login.php");
    }
    
    $result = array();
    
    if(!isset($_POST['id'])) {
        $result['success'] = false;
        $result['general_message'] = "Se produjo un error al actualizar la noticia.";
        exit(json_encode($result));
    }
    
    if(!isset($_POST['newsData'])) {
        $result['success'] = false;
        $result['general_message'] = "Se produjo un error al actualizar la noticia: El título no puede quedar vacío.";
        exit(json_encode($result));
    }
    
    $data = $_POST['newsData'];
    
    $todayh = getdate();
	$day = $todayh['mday'];
	$month = $todayh['mon'];
	$year = $todayh['year'];

	if(!empty($data["datepicker"])){
		$date = explode("/", $data["datepicker"]);
		$day = $date[1];
		$month = $date[0];
		$year = $date[2];
	}

	if(!checkdate($month, $day, $year)){
	    $result['success'] = false;
        $result['general_message'] = "Se produjo un error al actualizar la noticia:  La fecha introducida es incorrecta.";
        exit(json_encode($result));
	}

	$sqlDate = $year."-".$month."-".$day;
    
    $DAONews = new DAONews();
    
    $res = $DAONews->update($_POST['id'], $_SESSION['user'], $data['titulo'], $data['bajada'], $data['cuerpo'], (empty($data["datepicker"]) ? null : $sqlDate), ($data['borrador'] == 'true' ? 1 : 0), isset($data['autores']) ? $data['autores'] : null);
    
    if(!$res) {
        $result['success'] = false;
        $result['general_message'] = "Se produjo un error al actualizar la noticia:  La fecha introducida es incorrecta.";
    } else {
        $result['success'] = true;
        $result['general_message'] = "Noticia guardada correctamente.";
    }

    $result['success'] = true;
    $result['general_message'] = "Noticia guardada correctamente.";
    exit(json_encode($result));
?>