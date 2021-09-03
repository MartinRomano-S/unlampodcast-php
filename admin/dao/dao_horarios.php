<?php
// incluye la clase Db
require_once('db_v2.php');
 
	class DAOHorario {

		public function __construct(){}

		public function getNewId($horario) {
			$db = DatabaseCasero::connect();

			$sql = "SELECT MAX(ID_HORARIO) + 1 AS NEW_ID FROM pu_horarios_programas";
			$sql .= " WHERE ID_PROGRAMA = ?";
			$stmt = $db->prepare($sql);
			$idPrograma = $horario->getIdPrograma();
			$stmt->bind_param("d", $idPrograma);
			$stmt->execute();
			$result = $stmt->get_result();

			$newId = 1;

			if($row = $result->fetch_assoc()){
				if(!is_null($row["NEW_ID"])){
					$newId = $row["NEW_ID"];
				}
			}

			return $newId;
		}
 
		public function insertar($horario){
			$newId = self::getNewId($horario);
			$db = DatabaseCasero::connect();
			$insert = $db->prepare('INSERT INTO pu_horarios_programas (ID_HORARIO, ID_PROGRAMA, DIA, HORARIO, HORARIO_FIN) VALUES(?,?,?,?,?)');
			$insert->bind_param('ddsss', $newId, $horario->getIdPrograma(), $horario->getDia(), $horario->getHoraInicio(), $horario->getHoraFin());
			$result = $insert->execute();

			if($result){
				$_SESSION['noerror'] = "Horario creado correctamente.";
			} else {
				$_SESSION['error'] =  "Se produjo un error al crear el horario;";
			}
		}
 
		public function mostrar(){
			$db = DatabaseCasero::connect();
			$listaHorarios = [];
			$stmt = $db->prepare('SELECT * FROM pu_horarios_programas');
			$stmt->execute();
			$result = $stmt->get_result();
 
			while($sHorario = $result->fetch_assoc()) {
				$horario = new Horario();
				$horario->setId($sHorario['ID_HORARIO']);
				$horario->setIdPrograma($sHorario['ID_PROGRAMA']);
				$horario->setDia($sHorario['DIA']);
				$horario->setHoraInicio($sHorario['HORARIO']);
				$horario->setHoraFin($sHorario['HORARIO_FIN']);
				$listaHorarios[] = $horario;
			}
			
			return $listaHorarios;
		}

		public function mostrarPorPrograma($idPrograma){
			$db = DatabaseCasero::connect();
			$listaHorarios = [];
			$stmt = $db->prepare('SELECT * FROM pu_horarios_programas WHERE ID_PROGRAMA = ?');
			$stmt->bind_param('s', $idPrograma);
			$stmt->execute();
			$result = $stmt->get_result();
 
			while($sHorario = $result->fetch_assoc()) {
				$horario = new Horario();
				$horario->setId($sHorario['ID_HORARIO']);
				$horario->setIdPrograma($sHorario['ID_PROGRAMA']);
				$horario->setDia($sHorario['DIA']);
				$horario->setHoraInicio($sHorario['HORARIO']);
				$horario->setHoraFin($sHorario['HORARIO_FIN']);
				$listaHorarios[] = $horario;
			}
			
			return $listaHorarios;
		}

		public function mostrarProximoPrograma(){
			$db = DatabaseCasero::connect();
			$listaHorarios = [];
			$idDiaHoy = date("N");
			$stmt = $db->prepare('SELECT * FROM pu_horarios_programas WHERE DIA = ? AND TIMESTAMPDIFF(MINUTE,HORARIO,NOW()) <= 10 AND TIMESTAMPDIFF(MINUTE,HORARIO,NOW()) > -10');
			$stmt->bind_param('d', $idDiaHoy);
			$stmt->execute();
			$result = $stmt->get_result();

			while($sHorario = $result->fetch_assoc()) {
				$horario = new Horario();
				$horario->setId($sHorario['ID_HORARIO']);
				$horario->setIdPrograma($sHorario['ID_PROGRAMA']);
				$horario->setDia($sHorario['DIA']);
				$horario->setHoraInicio($sHorario['HORARIO']);
				$horario->setHoraFin($sHorario['HORARIO_FIN']);
				$listaHorarios[] = $horario;
			}
			
			return $listaHorarios;
		}
 
		public function eliminar($id, $idPrograma){
			$db=DatabaseCasero::connect();
			$eliminar=$db->prepare('DELETE FROM pu_horarios_programas WHERE ID_PROGRAMA = ? AND ID_HORARIO = ?');
			$eliminar->bind_param('ss', $idPrograma, $id);
			$result = $eliminar->execute();
			
			if($result){
				$_SESSION['noerror'] = "Horario borrado correctamente.";
			} else {
				$_SESSION['error'] =  "Se produjo un error al borrar el horario;";
			}
		}

		public function getPrograma($id){
			/*$db = DatabaseCasero::connect();
			$select = $db->prepare('SELECT * FROM pu_programas WHERE id =:id');
			$select->bindValue('id',$id);
			$select->execute();
			$sPrograma = $select->fetch();
			$programa = new Programa();
			$programa->setId($sPrograma['id']);
			$programa->setNombre($sPrograma['nombre']);
			$programa->setDescripcion($sPrograma['descripcion']);
			$programa->setIdImagen($sPrograma['id_imagen']);
			return $programa;*/
		}
 
		public function actualizar($programa){
			/*$db=DatabaseCasero::connect();
			$actualizar=$db->prepare('UPDATE pu_programas SET nombre=:nombre, descripcion=:descripcion, id_imagen=:id_imagen WHERE id =:id');
			$actualizar->bindValue(':id',$programa->getId());
			$actualizar->bindValue(':nombre',$programa->getNombre());
			$actualizar->bindValue(':descripcion',$programa->getDescripcion());
			$actualizar->bindValue(':id_imagen',$programa->getIdImagen());
			$actualizar->execute();*/
		}
	}
?>