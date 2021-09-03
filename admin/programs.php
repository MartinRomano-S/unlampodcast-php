<?php
  if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
  }
?>
<?php
	require_once('./obj/programa.php');
	require_once('./dao/dao_programa.php');
	$programaDAO =new DAOPrograma();
	$programa = new Programa();
	$listaPrograma = $programaDAO->mostrar();
?>


<!DOCTYPE html>
<html>
<head>
	<title>Programas</title>
	<?php include 'header.php'; ?>
	<script type="text/javascript" src="../js/modal.js"></script>

	<script>
		function validate(){
			return;
		}
	</script>
</head>
<body>
	<?php include 'modal.php'; ?>
	<div class="container" style="padding: 0px; background-image: url('../img/bgtest.jpg');">
		<?php include 'navAdmin.php'; ?>
		<div class="card" style="width: 98%; margin: 10px 1% 10px 1%;">
			<div class="card-body">
				<h4 class="card-title"><i class="fas fa-headset text-info"></i>&nbsp;Programas</h4><hr>
				<form id="formProgram" name="formProgram" role="form" method="post" action="actionPrograms.php" onsubmit="return validate();" enctype="multipart/form-data">
					<input type="hidden" id="idProgram" name="idProgram">
					<div class="form-group">
						<label for="nombre">Nombre:</label>
						<input type="text" class="form-control" id="inNombre" name="inNombre" >
					</div>
					<div class="form-group">
						<label for="nombre">Descripci&oacute;n:</label>
						<input type="text" class="form-control" id="inDesc" name="inDesc" >
					</div>
					<div class="form-group">
	                	<label for="inImgFile">Imagen:</label>
	                	<div id="showImg" name="showImg" class="text-center">
	                		
	                	</div>
	                	<div class="custom-file">
							<input type="file" class="custom-file-input" id="inImgFile" name="inImgFile">
							<label id="lblImgPort" name="lblImgPort" class="custom-file-label" for="inImgFile" data-browse="Elegir">Seleccionar Archivo</label>
							<input type="hidden" id="idImg" name="idImg">
						</div>
	                </div>
	                <script>
			            $('.custom-file-input').on('change',function(){
			                //get the file name
			                var fileName = $(this).val().split('\\').pop(); 
			                //replace the "Choose a file" label
			                $(this).next('.custom-file-label').html(fileName);
			            });
			        </script>
	                <div class="form-group">
			            <label for="link">Link Vivo:</label>
			            <div class="input-group">
			              <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-link"></i></span></div>
			              <input type="text" class="form-control" id="link" name="link">
			            </div>
			        </div>
			        <?php 
			        if(isset($_POST["update"])){ ?>
						<input type="hidden" id="idProgramaMod" name="idProgramaMod" value="<?php echo $_POST["idEdit"] ?>" />
		        		<button type="submit" class="btn btn-info" name="edit" >Actualizar</button>
		        	<?php  }else{ ?>
			        	<button type="submit" class="btn btn-info" name="insert" >Guardar</button>
		        	<?php } ?>
				</form>
				<br>
				<div class="table-responsive">
	              <table class='table table-striped table-hover' style='width: 100%;'>
	                <?php
	                if($listaPrograma){ ?>
	                <thead>
	                  <tr style='height: 40px;'>
	                    <th style='width: 42%;'>Nombre</th>
	                    <th style='width: 41%;'>Descripción</th>
	                    <th style='width: 13%;'>Link vivo</th>
	                    <th>&nbsp;</th>
	                  </tr>
	                </thead>
	                <tbody>
	                <?php
	                    foreach ($listaPrograma as $programita){ ?>
	                    <tr style='height: 40px;'>
	                      <td><?php echo $programita->getNombre(); ?></td>
	                      <td><?php echo $programita->getDescripcion(); ?></td>
	                      <td><?php echo $programita->getLinkVivo(); ?></td>
	                      <td><a href="actionPrograms.php?delete=1&id=<?php echo $programita->getId(); ?>"><i class='fas fa-trash-alt text-info'></i></a></td>
	                    </tr>
	                <?php 
	                  } 
	                } else { ?>
	                    <thead>
	                      <tr>
	                        <th style='width: 100%; text-align: center;'>No hay ning&uacute;n horario cargado.</th>
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