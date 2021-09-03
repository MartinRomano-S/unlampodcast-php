<?php
	$error='';
	if(!isset($_SESSION['user'])){ 
		if(isset($_POST['submit'])){

			if(empty($_POST['user']) || empty($_POST['pass'])){
				$error = "<span class='text-info'>Usuario o contraseña invalida.</span>";
			}
			else
			{
				$user=$_POST['user'];
				$pass=$_POST['pass'];
				$conn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");

				$stmt = $conn->prepare('SELECT * FROM pu_usuarios WHERE ID_USUARIO=? AND BLOQUEADO = 0');
				$stmt->bind_param('s',$user);
				$stmt->execute();

				$result = $stmt->get_result();

				if(($row = $result->fetch_assoc())){
					if(password_verify($pass,$row['CLAVE'])){
						$_SESSION['user'] = $user;
						$_SESSION['type'] = $row['SUPER'];

						//si ingresa, reiniciamos los intentos
						$sql = "UPDATE pu_usuarios SET INTENTOS = 0 WHERE ID_USUARIO=?";
						$stmt = $conn->prepare($sql);
						$stmt->bind_param('s',$row["ID_USUARIO"]);
						$stmt->execute();

						error_log("El usuario ".$_SESSION['user']." ha iniciado sesion", 0);
						header("Location: ./admin/admin.php");
					} else {


						//Debemos incrementar el contador de intentos y si es el 3er intento fallido, bloquearlo
						$sql = "UPDATE pu_usuarios SET INTENTOS = INTENTOS + 1";

						if($row["INTENTOS"]+1==3){
							$sql.= " ,BLOQUEADO = 1";
						}

						$sql.= " WHERE ID_USUARIO=?";

						$stmt = $conn->prepare($sql);
						$stmt->bind_param('s',$row["ID_USUARIO"]);
						$stmt->execute();
						$error = "<span class='text-info'>Usuario o contraseña invalida.</span>";
					}
				} else {
					$error = "<span class='text-info'>Usuario o contraseña invalida.</span>";
				}

				mysqli_close($conn); // Closing connection
			} 
		}
	}else{
			header("Location: ./admin/admin.php");
	}
?>