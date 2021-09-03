<?php
    require_once('../dao/dao_news.php');
    require_once('../obj/news.php');
    $newsDAO =new DAONews();
    $news = new News();
    
    if(!isset($_POST['n']) || $_POST['n'] == -1)  {
        echo    "<div class='jumbo-header jumbotron bg-light pl-0 pr-0 mt-3'>
                    <div class='container justify-content-center'>
                	    <div class='row'>
                            <div class='col text-center'>
                	            <img src='./img/tooltip.png' alt='UNLaM Podcast' style='height: 96px;'><h2 class='text-info'>UNLaM Podcast</h2>
                            </div>
                        </div>
                        <hr>
                        <div class='row'>
                            <div class='col text-center'>
                                <h6 class='text-center'>No se pudo recuperar la noticia.</h6>
                            </div>
                        </div>
                    </div>
                </div>";
        return;
    }
    
    $news = $newsDAO->getPublishedById($_POST['n']);
    
    if($news->getId()) {
            
            echo    "
                <div class='jumbo-header bg-light jumbotron mt-3'>
                    <div class='container justify-content-center'>
                        <div class='row'>
                            <div class='col text-center'>
                                <h1 class='text-info text-left text-ellipsis'>
                                        ".$news->getTitulo()."
                                </h1>
                                <h5 class='text-left'><b>Autor: ".($news->getAuthorCad() ? $news->getAuthorCad() : " - ")."Fecha: ".$news->getFrontEndPublishDate()."</b></h5>
                                <hr class='mt-0 mb-0'>
                                <p class='text-left text-ellipsis' style='word-wrap: break-word; max-height: 3.6em; line-height: 1.8em; font-size: 18px'>
                                    ".$news->getBajada()."
                                </p>
                            </div>
                        </div>
                        <hr class='mt-0'>
                        <div class='row'>
                                <div class='col'>
                                    <div class='text-justify'>
                                        ".$news->getCuerpo()."
                                    </div>
                                </div>
                            </div>
                        </div><br>";
                        
            if($news->getPath_audio()) {
                echo "
                    <br>
                    <div class='row'>
                        <div class='col pl-5 pr-5'>
                            <audio class='w-100' controls controlsList='nodownload'>
                                <source src='./news/audio/".$news->getPath_audio()."' type='audio/ogg'>
                                <source src='./news/audio/".$news->getPath_audio()."' type='audio/mpeg'>
                                Tu navegador no soporta la reproducci&oacute;n de audios.
                            </audio>
                        </div>
                    </div>
                    <br>
                ";
            }
                        
            if($news->getPath_imagen()) {
                echo "
                <div class='row'>
                        <div class='col pl-5 pr-5'>
                            <img class='img-fluid img-thumbnail' src='./news/img/".$news->getPath_imagen()."' alt='Imágen de la noticia'>
                        </div>
                    </div>
                </div>
                ";
            }
        echo "
                </div>
            </div>";
    } else {
        echo    "
        <div class='jumbo-header jumbotron bg-light pl-0 pr-0 mt-3'>
            <div class='container justify-content-center'>
        	    <div class='row'>
                    <div class='col text-center'>
        	            <img src='./img/tooltip.png' alt='UNLaM Podcast' style='height: 96px;'><h2 class='text-info'>UNLaM Podcast</h2>
                    </div>
                </div>
                <hr>
                <div class='row'>
                    <div class='col text-center'>
                        <h6 class='text-center'>No se pudo recuperar la noticia.</h6>
                    </div>
                </div>
            </div>
        </div>";
    }

/*


                                <h3 class='text-info'>
                                    <div class='a-share'>
                                        <p class='clipBoardLink' style='display: none;'>Leé mi última publicación en @UNLaMPodcast desde: http://unlampodcast.com/newsGen.php?n=".$news->getId()."</p>
                                        <a class='a-info' id='share' href='javascript:copyToClipboard(\".clipBoardLink\");'>
                                            &nbsp;<i class='fas fa-share-alt-square'></i>
                                        </a>
                                        <div class='toast hide'>
                                            <div class='toast-body'>
                                                ¡Enlace copiado!
                                            </div>
                                        </div>
                                    </div>
                                </h3>*/

?>