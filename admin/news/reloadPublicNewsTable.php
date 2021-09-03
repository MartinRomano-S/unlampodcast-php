<?php
    require_once('../dao/dao_news.php');
    require_once('../obj/news.php');
    $newsDAO =new DAONews();
    $news = new News();
    
    $todayh = getdate();
	$day = $todayh['mday'];
	$month = $todayh['mon'];
	$year = $todayh['year'];
	
	if(isset($_POST["dateFilter"])) {
    	$fecha = $_POST["dateFilter"];
        
    	if(!empty($fecha)){
    		$date = explode("/", $fecha);
    		$day = $date[1];
    		$month = $date[0];
    		$year = $date[2];
    	}
	}

	if(!checkdate($month, $day, $year)){
	    echo    "
	        <div class='jumbo-header jumbotron bg-light pl-0 pr-0 mt-3'>
                <div class='container justify-content-center'>
                    <div class='row'>
                        <div class='col text-center'>
                            <h6 class='text-center'>No hay ninguna noticia disponible que coincida con el filtro actual.</h6>
                        </div>
                    </div>
                </div>
            </div>";
        return;
	}
    
    $sqlDate = $year."-".$month."-".$day;
    $newsList = $newsDAO->mostrarPublicadas($sqlDate, (isset($_POST['titleFilter']) ? $_POST['titleFilter'] : null));

    if($newsList) {
        $pos = 1;
        
        foreach ($newsList as $news) {
            
            echo    "
                <a class='no-decor' href='./newsGen.php?n=".$news->getId()."' title='Ver noticia'>
                    <div id=newsTableReg".$pos." class='jumbo-header bg-light jumbotron pl-0 pr-0 mt-3'>
                        <div class='container justify-content-center'>
                            <div class='row'>
                                <div class='col text-center'>
                                        <h2 class='text-info text-left text-ellipsis'>
                                                <b>".$news->getTitulo()."</b>
                                        </h2>
                                        <h6 class='text-left'>Autor: ".($news->getAuthorCad() ? $news->getAuthorCad() : " - ")."Fecha: ".$news->getFrontEndPublishDate()."</h6>
                                    <hr class='m-0'>
                                   <!-- <h3 class='text-info'>
                                        <div class='a-share'>
                                            <a class='a-info' id='share' href='javascript:copyToClipboard();'>
                                                &nbsp;<i class='fas fa-share-alt-square'></i>
                                            </a>
                                            <div class='toast hide'>
                                                <div class='toast-body'>
                                                    ¡Enlace copiado!
                                                </div>
                                            </div>
                                        </div>
                                    </h3>-->
                                    <p class='text-left text-ellipsis' style='word-wrap: break-word; max-height: 3.6em; line-height: 1.8em; font-size: 18px'>
                                        ".$news->getBajada()."
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <script>
                    $('#newsTableReg".$pos."').data('id', ".$news->getId().");
                    $('#newsTableReg".$pos."').data('newsData', {
                        titulo : '".$news->getTitulo()."',
                        bajada : '".$news->getBajada()."',
                        cuerpo : '".$news->getCuerpo()."',
                        pubDate : '".$news->getFrontEndPublishDate()."',
                        imgPath : '".$news->getPath_imagen()."',
                        audioPath : '".$news->getPath_audio()."',
                        autores : '".($news->getAuthorCad() ? $news->getAuthorCad() : " - ")."'
                    });
                </script>";
            
            $pos += 1;
        }
    } else {
        echo    "<div class='jumbo-header jumbotron bg-light pl-0 pr-0 mt-3'>
                    <div class='container justify-content-center'>
                        <div class='row'>
                            <div class='col text-center'>
                                <h6 class='text-center'>No hay ninguna noticia disponible que coincida con el filtro actual.</h6>
                            </div>
                        </div>
                    </div>
                </div>";
    }


?>