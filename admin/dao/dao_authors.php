<?php

    require_once('db_v2.php');
    require_once('../obj/author.php');
    //Se creó en la actualización 2021 y no se utiliza en la página de autores y podcasts, solo en el módulo de noticias

	class DAOAuthors {

		public function __construct(){}
 
		public function getAll(){
			$db = DatabaseCasero::connect();
			$authorsList = array();
			$stmt = $db->prepare('SELECT * FROM pu_autores WHERE YEAR(FEC_CREACION) = YEAR(NOW()) ORDER BY APELLIDO, NOMBRE');
			$stmt->execute();
			$result = $stmt->get_result();
 
			while($sAuthor = $result->fetch_assoc()) {
				$author = new Author();
			    $author->setId($sAuthor['ID_AUTOR']);
    			$author->setNombre($sAuthor['NOMBRE']);
    			$author->setApellido($sAuthor['APELLIDO']);
				$authorsList[] = $author;
			}
			return $authorsList;
		}
		
		public function getAllInJSON(){
			$db = DatabaseCasero::connect();
			$authorsList = array();
			$stmt = $db->prepare('SELECT * FROM pu_autores WHERE YEAR(FEC_CREACION) = YEAR(NOW()) ORDER BY APELLIDO, NOMBRE');
			$stmt->execute();
			$result = $stmt->get_result();
 
			while($sAuthor = $result->fetch_assoc())
				$authorsList[] = array('id' => $sAuthor['ID_AUTOR'], 'nombre' => $sAuthor['NOMBRE'], 'apellido' => $sAuthor['APELLIDO']);

			return $authorsList;
		}
	}
?>