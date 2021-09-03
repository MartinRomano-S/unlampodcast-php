<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php 

include "db.php";

class Episode extends Database {

	private $table = "pu_episodios";

	public function loadAll($firstPC){

		$sql = "";
		$array = array();

		//si tenemos uno seleccionado 
		if(!empty($firstPC)){

			//si no es super, debemos ver si el podcast seleccionado es editable por el usuario
			if($_SESSION['type']<>1){
				$sql = "SELECT B.ID_PODCAST, B.NOMBRE FROM pu_usuarios_podcast A INNER JOIN pu_podcasts B ON A.ID_PODCAST = B.ID_PODCAST WHERE A.ID_USUARIO = ? AND A.ID_PODCAST = ?";
			    $stmt = $this->conn->prepare($sql);
			    $stmt->bind_param("sd", $_SESSION['user'],$firstPC);
			    $stmt->execute();
			    $result = $stmt->get_result();

			    if(!$result){
			    	return $array;
			    }
			}

			$sql = "SELECT * FROM ".$this->table." WHERE ID_PODCAST = ?";
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("d",$firstPC);
			$stmt->execute();
			$result = $stmt->get_result();

			while($row = $result->fetch_assoc()){
				$array[] = $row;
			}
		}

		return $array;
	}

	public function loadSelected($where){

		$sql = "SELECT * FROM ".$this->table;
		$array = array();
		$cond = "";

		foreach ($where as $key => $value) {
			$cond .= $key." = '".$value."' AND ";
		}

		$cond = substr($cond, 0, -5);
		$sql .= " WHERE ".$cond;
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		$result = $stmt->get_result();

		if($row = $result->fetch_assoc()){
			$array[] = $row;
			return $row;
		}
		
		return null;
	}
}

$obj = new Episode;
?>