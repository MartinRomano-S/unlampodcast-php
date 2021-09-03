<?php

    require_once('db_v2.php');

	class DAONews {

		public function __construct(){}
 
		public function insertar($idUser, $titulo){
			$db = DatabaseCasero::connect();
			$insert = $db->prepare('INSERT INTO pu_noticias (id_usuario, titulo, borrador) VALUES(?,?,1)');
			$insert->bind_param('ss', $idUser, $titulo);
			$insert->execute();

			return $insert->insert_id;
		}
		
		public function get($id, $idUser){
			$db = DatabaseCasero::connect();
			$select = $db->prepare('SELECT * FROM pu_noticias WHERE id = ? AND id_usuario = ?');
			$select->bind_param('is', $id, $idUser);
			$select->execute();
			$result = $select->get_result();
			$sNews = $result->fetch_assoc();
			$news = new News();
			$news->setId($sNews['ID']);
			$news->setTitulo($sNews['TITULO']);
			$news->setBajada($sNews['BAJADA']);
			$news->setCuerpo($sNews['CUERPO']);
			$news->setFecha_publicacion($sNews['FECHA_PUBLICACION']);
			$news->setPath_imagen($sNews['PATH_IMAGEN']);
			$news->setPath_audio($sNews['PATH_AUDIO']);
			$news->setBorrador($sNews['BORRADOR']);
			return $news;
		}
		
		public function getPublishedById($id){
			$db = DatabaseCasero::connect();
			$select = $db->prepare('SELECT * FROM pu_noticias WHERE id = ? AND BORRADOR = 0 AND (FECHA_PUBLICACION <= NOW() OR fecha_publicacion is null)');
			
			$select->bind_param('i', $id);
			$select->execute();
			$result = $select->get_result();
			$sNews = $result->fetch_assoc();
			
			$news = new News();
			$news->setId($sNews['ID']);
			$news->setTitulo($sNews['TITULO']);
			$news->setBajada($sNews['BAJADA']);
			$news->setCuerpo($sNews['CUERPO']);
			$news->setFecha_publicacion($sNews['FECHA_PUBLICACION']);
			$news->setPath_imagen($sNews['PATH_IMAGEN']);
			$news->setPath_audio($sNews['PATH_AUDIO']);
			
			
			$stmt = $db->prepare('SELECT * FROM pu_autores_noticias WHERE id_noticia = ?');
			$stmt->bind_param('i', $sNews['ID']);
			$stmt->execute();
		    $resAutores = $stmt->get_result();
		    $autores = '';
			    
		    if($resAutores) {
		        while($autor = $resAutores->fetch_assoc())
		            $autores .= $autor['APELLIDO'].', '.$autor['NOMBRE'].' - ';
		            
		        $news->setAuthorCad($autores);
		    }
			
			return $news;
		}
		
		public function update($id, $idUser, $titulo, $bajada, $cuerpo, $fecPub, $borrador, $autores){
			$db=DatabaseCasero::connect();
			$actualizar=$db->prepare('UPDATE pu_noticias SET titulo = ?, bajada = ?, cuerpo = ?, fecha_publicacion = ?, borrador = ? WHERE id = ? and id_usuario = ?');
			$actualizar->bind_param('ssssiis', $titulo, $bajada, $cuerpo, $fecPub, $borrador, $id, $idUser);
			$res = $actualizar->execute();
			
			if($res && $autores) {
			    
			    $deleteAutores = $db->prepare('DELETE FROM pu_autores_noticias WHERE id_noticia = ?');
    			$deleteAutores->bind_param('i', $id);
    			$deleteAutores->execute();
			    
			    foreach ($autores as $autor) {
			        $actualizarAutores = $db->prepare('INSERT INTO pu_autores_noticias VALUES(?,?)');
			        $actualizarAutores->bind_param('ii', $id, $autor);
			        $actualizarAutores->execute();
			    }
			}
			
			return $res;
		}
		
		public function updateImagePath($id, $idUser, $path){
			$db=DatabaseCasero::connect();
			$actualizar=$db->prepare('UPDATE pu_noticias SET path_imagen = ? WHERE id = ? and id_usuario = ?');
			$actualizar->bind_param('sis', $path, $id, $idUser);
			
			return $actualizar->execute();
		}
		
		public function updateAudioPath($id, $idUser, $path){
			$db=DatabaseCasero::connect();
			$actualizar=$db->prepare('UPDATE pu_noticias SET path_audio = ? WHERE id = ? and id_usuario = ?');
			$actualizar->bind_param('sis', $path, $id, $idUser);
			return $actualizar->execute();
		}
 
		public function mostrar($idUser){
			$db = DatabaseCasero::connect();
			$newsList = [];
			$stmt = $db->prepare('SELECT * FROM pu_noticias WHERE id_usuario = ?');
			$stmt->bind_param('s', $idUser);
			$stmt->execute();
			$result = $stmt->get_result();
 
			while($sNews = $result->fetch_assoc()) {
				$news = new News();
			    $news->setId($sNews['ID']);
    			$news->setTitulo($sNews['TITULO']);
    			$news->setBajada($sNews['BAJADA']);
    			$news->setCuerpo($sNews['CUERPO']);
    			$news->setFecha_publicacion($sNews['FECHA_PUBLICACION']);
    			$news->setPath_imagen($sNews['PATH_IMAGEN']);
    			$news->setPath_audio($sNews['PATH_AUDIO']);
    			$news->setBorrador($sNews['BORRADOR']);
    			
    			$stmt = $db->prepare('SELECT ID_AUTOR FROM pu_autores_noticias WHERE id_noticia = ?');
    			$stmt->bind_param('i', $sNews['ID']);
    			$stmt->execute();
			    $resAutores = $stmt->get_result();
			    $autores = array();
			    
			    if($resAutores) {
			        while($autor = $resAutores->fetch_assoc())
			            $autores[] = $autor['ID_AUTOR'];
			            
			        $news->setAutores($autores);
			    }
			    
				$newsList[] = $news;
			}
			return $newsList;
		}
		
		public function mostrarPublicadas($fecha, $titulo) {
		    $db = DatabaseCasero::connect();
			$newsList = [];
			$sql = 'SELECT * FROM pu_noticias WHERE borrador = 0 AND (fecha_publicacion <= ? OR fecha_publicacion is null)';
			
			if($titulo != null)
			    $sql .= ' AND titulo like ?';
			    
			$sql .= ' ORDER BY fecha_publicacion DESC';
			
			$stmt = $db->prepare($sql);

			if($titulo != null) {
			    $titulo = ("%".$titulo."%");
		        $stmt->bind_param('ss', $fecha, $titulo);
			}
		    else
			    $stmt->bind_param('s', $fecha);
			
			$stmt->execute();
			$result = $stmt->get_result();
 
			while($sNews = $result->fetch_assoc()) {
				$news = new News();
                $news->setId($sNews['ID']);
                $news->setTitulo($sNews['TITULO']);
                $news->setBajada($sNews['BAJADA']);
                $news->setCuerpo($sNews['CUERPO']);
                $news->setFecha_publicacion($sNews['FECHA_PUBLICACION']);
                $news->setPath_imagen($sNews['PATH_IMAGEN']);
                $news->setPath_audio($sNews['PATH_AUDIO']);
                $news->setBorrador($sNews['BORRADOR']);
                
                $stmt = $db->prepare('SELECT * FROM pu_autores_noticias WHERE id_noticia = ?');
                $stmt->bind_param('i', $sNews['ID']);
                $stmt->execute();
                $resAutores = $stmt->get_result();
                $autores = '';
			    
			    if($resAutores) {
			        while($autor = $resAutores->fetch_assoc())
			            $autores .= $autor['APELLIDO'].', '.$autor['NOMBRE'].' - ';
			            
			        $news->setAuthorCad($autores);
			    }
			    
				$newsList[] = $news;
			}
			return $newsList;
		}
		
		public function delete($id, $loggedUser) {
		    $db=DatabaseCasero::connect();
		    
			$eliminar=$db->prepare('DELETE FROM pu_autores_noticias WHERE id_noticia = ?');
			$eliminar->bind_param('i', $id);
			$eliminar->execute();
		    
			$eliminar=$db->prepare('DELETE FROM pu_noticias WHERE id = ? AND id_usuario = ?');
			$eliminar->bind_param('is', $id, $loggedUser);
			
			return $eliminar->execute();
		}
		
		public function deleteImage($id, $loggedUser) {
		    $db=DatabaseCasero::connect();
			$eliminar=$db->prepare('UPDATE pu_noticias SET path_imagen = NULL WHERE id = ? AND id_usuario = ?');
			$eliminar->bind_param('is', $id, $loggedUser);
			
			return $eliminar->execute();
		}
		
		public function deleteAudio($id, $loggedUser) {
		    $db=DatabaseCasero::connect();
			$eliminar=$db->prepare('UPDATE pu_noticias SET path_audio = NULL WHERE id = ? AND id_usuario = ?');
			$eliminar->bind_param('is', $id, $loggedUser);
			
			return $eliminar->execute();
		}
	}
?>