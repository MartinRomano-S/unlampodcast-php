<?php 
	
	if(isset($_REQUEST["p"]) && isset($_REQUEST["e"])){
		$p = $_REQUEST["p"];
		$e = $_REQUEST["e"];
		

		if($p !== "") {
			if($e !== ""){
				$conn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");
				$sql = "UPDATE pu_episodios";
				$sql .= " SET REPRODUCCIONES = REPRODUCCIONES + 1 ";
				$sql .= "WHERE ID_PODCAST = ? AND ID_EPISODIO = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("dd", $p, $e);
				$stmt->execute();
				mysqli_close($conn);
			}
		}

	}

	if(isset($_REQUEST["v"])){

		$v = $_REQUEST["v"];
		if($v !== "") {
			$conn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");
			$sql = "UPDATE pu_globales SET VALOR = VALOR + 1 WHERE ID = 1";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			mysqli_close($conn);
		}
	}
?>