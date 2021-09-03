<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
  $passEncrypt = "emese455";
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
	<?php include 'header.php'; ?>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
	<script>
	    function confirmDelete(ordinal){
      		$.confirm({
    			title: '',
    			content: '¿Está seguro que desea eliminar este podcast?',
    			//type: 'blue',
    			columnClass: 'small',
    			buttons: {
    				Aceptar: {
    					btnClass: 'btn-info',
    					action:function () {
    						document.location.href = 'actionPodcasts.php?delete=1&id=' + ordinal;
    					}
    				},
    				Cancelar: {
    					btnClass: 'btn-danger',
    					action: function () {
    					
    					}
    				}
    			}
    		});
    		
      	}
		function validate(){
			var msgError = "";
			var cantError = 0;
			if($("#nombre").val() == ""){
				msgError += "El nombre no puede quedar vacío;";
				cantError++;
			}
			if($("#desc").val() == ""){
				msgError += "La descripcion no puede quedar vacía;";
				cantError++;
			}
			/*
			if($("#usuarios").val() == ""){
				msgError += "Debe seleccionar al menos un usuario;";
				cantError++;
			}
			*/
			if($("#autores").val() == ""){
				msgError += "Debe seleccionar al menos un autor;";
				cantError++;
			}
			if($("#temas").val() == ""){
				msgError += "Debe seleccionar al menos un tema;";
				cantError++;
			}
			if(cantError > 0){
				msgError = msgError.slice(0,-1)
				showError(msgError);
				return false;
			}else{
				return true;
			}
		}
		function showError(msgError){
			var vectorMsg = msgError.split(";");
			$(".modal-title").empty();
			$(".modal-body").empty();
			$(".modal-title").append("Se han detectado los siguientes errores:");
			$(".modal-body").append("<div class='alert alert-danger' role='alert'><ul id='ulModal' name='ulModal'></ul></div>");
			for(i=0;i<vectorMsg.length;i++){
				$("#ulModal").append("<li>" + vectorMsg[i] + "</li>");
			}
			$("#modal").modal('show');
		}
		function showNoError(msg){
			$(".modal-title").empty();
			$(".modal-body").empty();
			$(".modal-body").append("<div class='alert alert-success' role='alert'>"+ msg + "</div>");
			$("#modal").modal('show');
		}

		function edit(idEdit){
			$("#idEdit").val(idEdit);
			$("#fedit").submit();
		}
		function cargarEdit(idImg,nombre,desc,linkSpotify,usuarios,idAutores,autores,idTemas,temas,imagenPortada,imagenEpisodios){
			var vec;

			$("#nombre").val(nombre);
			$("#desc").val(desc);
			$("#link").val(linkSpotify);
			
			if(usuarios != null && usuarios != ""){
				vec = usuarios.split(",");

				for(i=0;i<vec.length;i++){
					$('#usuarios option[value=' + vec[i] + ']').attr('selected','selected');
				}
			}

			if(idAutores != null && idAutores != ""){
				vec = idAutores.split(",");

				for(i=0;i<vec.length;i++){
					$('#autores option[value=' + vec[i] + ']').attr('selected','selected');
				}
			}

			if(idTemas != null && idTemas != ""){
				vec = idTemas.split(",");

				for(i=0;i<vec.length;i++){
					$('#temas option[value=' + vec[i] + ']').attr('selected','selected');
				}				
			}


			if(imagenPortada != null && imagenPortada != ""){
				$("#idImgPort").prepend("<img src='../images/podcasts/portada/"+ idImg + "." + imagenPortada  + "?dummy=" + Math.floor(Math.random() * 100000)  +"' class='img-fluid' style='height:300px;'></img>");
				$("#lblImgPort").attr("data-browse","Cambiar");
				$("#imagenPortada").on('change', function() {
					$("#idImgPort").empty();
				});
			}
			if(imagenEpisodios != null && imagenEpisodios != ""){
				$("#idImgEp").prepend("<img src='../images/podcasts/episodio/"+ idImg + "." + imagenEpisodios  + "?dummy=" + Math.floor(Math.random() * 100000)  +"' class='img-fluid' style='height:300px;'></img>");
				$("#lblImgEp").attr("data-browse","Cambiar");
				$("#imagenEpisodios").on('change', function() {
					$("#idImgEp").empty();
				});
			}
			
		}
		function del(idEdit){
			$("#idDelete").val(idEdit);
			$("#fdelete").submit();
		}

		function showDesc(pcName, rowNumber){
			$(".modal-title").empty();
			$(".modal-title").append(pcName);
			$(".modal-body").append($(("#desc" + rowNumber)).val());
			$("#modal").modal('show');
		}

	</script>
</head>
<?php 
	$conn = mysqli_connect("localhost","unl4mp0dc4st","12345","unlam_podcast");
 ?>
<body>

	<?php 
		if( isset($_SESSION['error']) ){
	        echo "<script>$(document).ready(function(){showError('".$_SESSION['error']."');});</script>";
	        unset($_SESSION['error']);
		}else{
			if(isset($_SESSION['noerror'])){
				echo "<script>$(document).ready(function(){showNoError('".$_SESSION['noerror']."');});</script>";
	        	unset($_SESSION['noerror']);
			}
		}
	 ?>
	<div class="modal fade" tabindex="-1" role="dialog" id="modal" name="modal">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title"></h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	
	      </div> 
	    </div>
	  </div>
	</div>
	<div id="body" class="container">
		<?php include "navAdmin.php"; ?>
		<div class="card" style="width: 98%; margin: 10px 1% 10px 1%;">
	    	<div class="card-body">
	        	<h4 class="card-title"><i class="fas fa-microphone-alt text-info"></i>&nbsp;Podcasts</h4><hr>
	        	<form role="form" action="actionPodcasts.php" method="post" id="formUser" name="formUser"  onSubmit="return validate();" enctype="multipart/form-data">
	        		<div class="form-group">
	                	<label for="nombre">Nombre:</label>
	                	<input type="text" class="form-control" id="nombre" name="nombre"></input>
	                </div>

	                <div class="form-group">
	                	<label for="desc">Descripción:</label>
	                	<input type="text" class="form-control" id="desc" name="desc"></input>
	                </div>

	            	<div class="form-group">
	                	<label for="usuarios">Usuarios que pueden editar:</label>
	                	<select class="form-control selectpicker" multiple="multiple" id="usuarios" name="usuarios[]" title="Seleccionar usuarios...">
	                		<?php 
		                		$stmt = $conn->prepare('SELECT * FROM pu_usuarios WHERE ID_USUARIO <> ? AND SUPER = 0 ORDER BY ID_USUARIO');
		                		$stmt->bind_param('s',$_SESSION["user"]);
								$stmt->execute();
								$result = $stmt->get_result();
								while ($row = $result->fetch_assoc()) {
									print "<option value='{$row['ID_USUARIO']}'>{$row['ID_USUARIO']}</option> ";
								}
							 ?>
	                	</select>
	                </div>
	                <div class="form-group">
	                	<label for="autores">Autores:</label>
	                	<select class="form-control selectpicker" multiple id="autores" name="autores[]" title="Seleccionar autores...">
	                		<?php 
		                		$stmt = $conn->prepare('SELECT * FROM pu_autores ORDER BY APELLIDO, NOMBRE');
								$stmt->execute();
								$result = $stmt->get_result();
								while ($row = $result->fetch_assoc()) {
									print "<option value='{$row['ID_AUTOR']}'>{$row['APELLIDO']}, {$row['NOMBRE']}</option> ";
								}
							 ?>
	                	</select>
	                </div>
	                <div class="form-group">
	                	<label for="temas">Temas:</label>
	                	<select class="form-control selectpicker" multiple id="temas" name="temas[]" onchange="" title="Seleccionar temas...">
	                		<?php 
		                		$stmt = $conn->prepare('SELECT * FROM pu_temas ORDER BY NOMBRE');
								$stmt->execute();
								$result = $stmt->get_result();
								while ($row = $result->fetch_assoc()) {
									print "<option value='{$row['ID_TEMA']}'>{$row['NOMBRE']}</option> ";
								}
							 ?>
	                	</select>
	                </div>
	                <div class="form-group">
			            <label for="link">Link Spotify:</label>
			            <div class="input-group">
			              <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-link"></i></span></div>
			              <input type="text" class="form-control" id="link" name="link">
			            </div>
			        </div>
	                <div class="form-group">
	                	<label for="imagenPortada">Imagen de portada (Banner):</label>
	                	<div id="idImgPort" name="idImgPort" class="text-center">
	                		
	                	</div>
	                	<div class="custom-file">
							<input type="file" class="custom-file-input" id="imagenPortada" name="imagenPortada">
							<label id="lblImgPort" name="lblImgPort" class="custom-file-label" for="imagenPortada" data-browse="Elegir">Seleccionar Archivo</label>
						</div>	
	                </div>

	                <div class="form-group">
	                	<label for="imagenEpisodios">Imagen de Episodios (Carátula):</label>
	                	<div id="idImgEp" name="idImgEp" class="text-center">

	                	</div>
	                	<div class="custom-file"> 
							<input type="file" class="custom-file-input" id="imagenEpisodios" name="imagenEpisodios">
							<label id="lblImgEp" name="lblImgEp" class="custom-file-label" for="imagenEpisodios" data-browse="Elegir">Seleccionar Archivo</label>
						</div>	
	                </div>
	                <script>
			            $('.custom-file-input').on('change',function(){
			                //get the file name
			                var fileName = $(this).val().split('\\').pop(); 
			                //replace the "Choose a file" label
			                $(this).next('.custom-file-label').html(fileName);
			            })
			        </script>
			        <?php 
			        if(isset($_POST["idEdit"])){ ?>
		        		
						<input type="hidden" id="idPodcastMod" name="idPodcastMod" value="<?php echo $_POST["idEdit"] ?>" />
		        		<button type="submit" class="btn btn-info" name="editar" >Actualizar</button>
		        	<?php  }else{ ?>

			        	<button type="submit" class="btn btn-info" name="aceptar" >Guardar</button>

		        	<?php } ?>

			        
	              	
	        	</form>

	        	<form id="fedit" name="fedit" action="podcasts.php" method="post">
	        		<input type="hidden" id="idEdit" name="idEdit" value="" />
	        	</form>
	        	<form id="fdelete" name="fdelete" action="actionPodcasts.php" method="post">
	        		<input type="hidden" id="idDelete" name="idDelete" value="" />
	        	</form>

	        	<br>
	        	<div class="table-responsive">
		        	<table class='table table-striped table-hover' style='width: 100%;'>
				        <?php 
				        if($_SESSION["type"] == 1){
				    		$stmt = $conn->prepare('SELECT * FROM pu_podcasts ');
				        }else{
				        	$stmt = $conn->prepare('SELECT * FROM pu_podcasts P INNER JOIN pu_usuarios_podcast PU ON P.ID_PODCAST = PU.ID_PODCAST WHERE PU.ID_USUARIO = ? ');
				        	$stmt->bind_param('s',$_SESSION["user"]);
				        }
				        
						$stmt->execute();
						$result = $stmt->get_result();

					    if($result->num_rows > 0){ ?>
					          <thead>
					            <tr style='height: 40px;'>
					              <th>Nombre</th>
					              <th>Descripción</th>
					              <th>Usuarios</th>
					              <th>Autores</th>
					              <th>Temas</th>
					              <th>&nbsp;</th>
					              <th>&nbsp;</th>
					            </tr>
					          </thead>
					          <tbody>
						<?php

						$curr = 0;

			              while ($row = $result->fetch_assoc()) {
			              	$idPodcast = $row["ID_PODCAST"];
			              	// SELECT PARA LOS USUARIOS
			              	$stmt = $conn->prepare('SELECT * FROM pu_usuarios_podcast UP WHERE UP.ID_PODCAST = ? ');
			              	$stmt->bind_param('i',$idPodcast);
							$stmt->execute();
							$resultUser = $stmt->get_result();
							$cadUser = "";
							while ($rowUser = $resultUser->fetch_assoc()){
								$cadUser .= $rowUser["ID_USUARIO"] . ", ";
							}
							$cadUser = rtrim($cadUser, ', ');
							// SELECT PARA LOS AUTORES
							$stmt = $conn->prepare('SELECT * FROM pu_autores A INNER JOIN pu_autores_podcast AP ON A.ID_AUTOR = AP.ID_AUTOR WHERE AP.ID_PODCAST = ? ');
							$stmt->bind_param('i',$idPodcast);
							$stmt->execute();
							$resultAutores = $stmt->get_result();
							$cadAutores = "";
							$cadIdAutores ="";

							while ($rowAutores = $resultAutores->fetch_assoc()){
								$cadIdAutores .= $rowAutores["ID_AUTOR"] . ",";
								$cadAutores .= $rowAutores["NOMBRE"] . " ".$rowAutores["APELLIDO"].", ";
							}
							$cadAutores = rtrim($cadAutores, ', ');
							$cadIdAutores = rtrim($cadIdAutores, ',');
							// SELECT PARA LOS TEMAS
							$stmt = $conn->prepare('SELECT * FROM pu_temas T INNER JOIN pu_temas_podcast TP ON T.ID_TEMA = TP.ID_TEMA WHERE TP.ID_PODCAST = ? ');
							$stmt->bind_param('i',$idPodcast);
							$stmt->execute();
							$resultTemas = $stmt->get_result();
							$cadTemas = "";
							$cadIdTemas = "";
							while ($rowTemas = $resultTemas->fetch_assoc()){
								$cadIdTemas .= $rowTemas["ID_TEMA"] . ",";
								$cadTemas .= $rowTemas["NOMBRE"] . ", ";
							}
							$cadTemas = rtrim($cadTemas, ', ');
							$cadIdTemas = rtrim($cadIdTemas, ',');

							if(isset($_POST["idEdit"])){
							    $idDecrypted = openssl_decrypt($_POST["idEdit"],"AES-128-ECB",$passEncrypt);
								if($idDecrypted == $idPodcast){
									echo "<script>cargarEdit('".$idPodcast."','".$row["NOMBRE"]."','".$row["DESCRIPCION"]."','".$row["LINK_SPOTIFY"]."','".$cadUser."','".$cadIdAutores."','".$cadAutores."','".$cadIdTemas."','".$cadTemas."','".$row["ID_IMG_PORTADA"]."','".$row["ID_IMG_EPISODIO"]."');</script>";
								}
							}
						?>
				              <tr style='height: 40px;'>
				                <td><?php echo $row["NOMBRE"]; ?></td>
				                <td class="text-center">
				                	<input type="hidden" id="desc<?php echo $curr;?>" name="desc<?php echo $curr;?>" value="<?php echo $row["DESCRIPCION"]; ?>">
				                	<a title="Ver descripción" href="javascript:showDesc('<?php echo $row["NOMBRE"]; ?>','<?php echo $curr;?>');"><i class="fas fa-file-alt text-info"></i></a>
				                </td>
				                <td><?php echo $cadUser ?></td>
				                <td><?php echo $cadAutores; ?></td>
				                <td><?php echo $cadTemas ?></td>
				                <td><a title="Editar" href="javascript:edit('<?php echo openssl_encrypt($row["ID_PODCAST"],"AES-128-ECB",$passEncrypt); ?>')"><i class="fas fa-edit text-info"></i></a></td>
				                <td><a title="Eliminar" href="javascript:confirmDelete('<?php echo rawurldecode(openssl_encrypt($row["ID_PODCAST"],"AES-128-ECB",$passEncrypt)); ?>');"><i class='fas fa-trash-alt text-info'></i></a></td>
				              </tr>
				            <?php 
				            	$curr = $curr + 1;
				              } 
				            } else { ?>
				              <thead>
				                <tr style='height: 40px;'>
				                  <th style='width: 100%; text-align: center;'>No hay ning&uacute;n podcast cargado.</th>
				                </tr>
				              </thead>
				            <?php } ?>
				          </tbody>
				    </table>
				</div>
	    	</div>
		</div>
		<?php include "../footer.php"; ?>
	</div>
</body>
</html>