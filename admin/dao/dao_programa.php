<?php
// incluye la clase Db
require_once('db_v2.php');
// require_once('/podcasts/admin/obj/programa.php');
	class DAOPrograma {

		public function __construct(){}
 
		public function insertar($programa){
			$db = DatabaseCasero::connect();
			$insert = $db->prepare('INSERT INTO pu_programas (nombre, descripcion, id_imagen, link_vivo) VALUES(?,?,?,?)');
			echo $programa->getNombre();
			$nombre = $programa->getNombre();
			$descripcion =$programa->getDescripcion();
			$id_imagen = $programa->getIdImagen();
			$link_vivo = $programa->getLinkVivo();
			$insert->bind_param('ssss',$nombre,$descripcion,$id_imagen,$link_vivo);
			$insert->execute();

			return $db;
		}
 
		public function mostrar(){
			$db = DatabaseCasero::connect();
			$listaProgramas = [];
			$stmt = $db->prepare('SELECT * FROM pu_programas');
			$stmt->execute();
			$result = $stmt->get_result();
 
			while($sPrograma = $result->fetch_assoc()) {
				$programa = new Programa();
				$programa->setId($sPrograma['ID']);
				$programa->setNombre($sPrograma['NOMBRE']);
				$programa->setDescripcion($sPrograma['DESCRIPCION']);
				$programa->setIdImagen($sPrograma['ID_IMAGEN']);
				$programa->setLinkVivo($sPrograma['LINK_VIVO']);
				$listaProgramas[] = $programa;
			}
			return $listaProgramas;
		}
 
		public function eliminar($id){
			$db=DatabaseCasero::connect();
			$eliminar=$db->prepare('DELETE FROM pu_programas WHERE id = ?');
			$eliminar->bind_param('i',$id);
			$eliminar->execute();

			return $db;
		}

		public function getPrograma($id){
			$db = DatabaseCasero::connect();
			$select = $db->prepare('SELECT * FROM pu_programas WHERE id = ?');
			$select->bind_param('i',$id);
			$select->execute();
			$result = $select->get_result();
			$sPrograma = $result->fetch_assoc();
			$programa = new Programa();
			$programa->setId($sPrograma['ID']);
			$programa->setNombre($sPrograma['NOMBRE']);
			$programa->setDescripcion($sPrograma['DESCRIPCION']);
			$programa->setIdImagen($sPrograma['ID_IMAGEN']);
			$programa->setLinkVivo($sPrograma['LINK_VIVO']);
			return $programa;
		}
 
		public function actualizar($programa){
			$db=DatabaseCasero::connect();
			$actualizar=$db->prepare('UPDATE pu_programas SET nombre = ?, descripcion = ?, id_imagen = ?, link_vivo = ? WHERE id = ?');
			$id = $programa->getId();
			$nombre = $programa->getNombre();
			$descripcion =$programa->getDescripcion();
			$id_imagen = $programa->getIdImagen();
			$link_vivo = $programa->getLinkVivo();
			$actualizar->bind_param('ssssi',$id,$nombre,$descripcion,$id_imagen,$link_vivo);
			$actualizar->execute();

			return $db;
		}
	}
?>