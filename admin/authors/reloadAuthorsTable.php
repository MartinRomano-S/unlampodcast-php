<?php 
    require_once('../dao/dao_authors.php');
    require_once('../obj/author.php');
    $authorDAO =new DAOAuthors();
    $author = new Author();
    $authorsList = $authorDAO->getAll();

    if($authorsList) {
        $pos = 1;
        echo    "<thead>
                    <tr style='height: 40px;'>
                        <th style='width: 10%;'>#</th>
                        <th style='width: 50%;'>Apellido</th>
                        <th style='width: 10%;'>Nombre</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($authorsList as $author){
            echo    "
            <tr style='height: 40px;'>
                <td>".$pos."</td>
                <td>".$author->getApellido()."</td>
                <td>".$author->getNombre()."</td>
            </tr>
            ";
            
            $pos += 1;
        }
        echo "</tbody>";

    } else {
        echo    "<thead>
                    <tr>
                        <th class='w-100 text-center'>No hay ning&uacute;n autor en el año actual.</th>
                    </tr>
                </thead>";
    }


?>