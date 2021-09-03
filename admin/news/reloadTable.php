<?php 

    if(!isset($_SESSION['user'])){
        header("Location: ../../login.php");
    }
    
    require_once('../dao/dao_news.php');
    require_once('../obj/news.php');
    $newsDAO =new DAONews();
    $news = new News();
    $newsList = $newsDAO->mostrar($_SESSION['user']);

    if($newsList) {
        $pos = 1;
        echo    "<thead>
                    <tr style='height: 40px;'>
                        <th style='width: 10%;'>N°</th>
                        <th style='width: 50%;'>Titulo</th>
                        <th style='width: 10%;'>Estado</th>
                        <th class='text-center' style='width: 20%;'>Fecha de Publicaci&oacute;n</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($newsList as $news){
            echo    "<tr id=newsTableReg".$pos." style='height: 40px;'>
                <td>".$pos."</td>
                <td>".$news->getTitulo()."</td>
                <td>".($news->getBorrador() == 0 ? "<span class='badge badge-success w-100'><b>Publicada</b></span>" : "<span class='badge text-light w-100' style='background-color: darkOrange'><b>Borrador</b></span>")."</td>
                <td class='text-center'>".($news->getFecha_publicacion() ? $news->getFecha_publicacion() : '-')."</td>
                <td><a class='edit'><i class='fas fa-edit text-info'></i></a></td>
                <td><a class='delete'><i class='fas fa-trash-alt text-info'></i></a></td>
            </tr>
            <script>
                //TODO ENCODEAR ID
                $('#newsTableReg".$pos."').data('id', ".$news->getId().");
                $('#newsTableReg".$pos."').data('newsData', {
                    titulo : '".$news->getTitulo()."',
                    bajada : '".$news->getBajada()."',
                    cuerpo : '".$news->getCuerpo()."',
                    pubDate : '".$news->getFrontEndPublishDate()."',
                    imgPath : '".$news->getPath_imagen()."',
                    audioPath : '".$news->getPath_audio()."',
                    borrador : ".$news->getBorrador()." == 1 ? true : false,
                    autores : ".json_encode($news->getAutores())."
                });
                
                $('.delete', '#newsTableReg".$pos."').off('click').click(() => {
                    confirmDelete(".$pos.");
                });
                
                $('.edit', '#newsTableReg".$pos."').off('click').click(() => {
                    toggleEditNewsContainer(undefined, undefined, $('#newsTableReg".$pos."'));
                });
            </script>";
            
            $pos += 1;
        }
        echo "</tbody>";

    } else {
        echo    "<thead>
                    <tr>
                        <th class='w-100 text-center'>No hay ning&uacute;na noticia cargada.</th>
                    </tr>
                </thead>";
    }


?>