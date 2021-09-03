<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php
  include "actionAuthor.php";
?>
<!doctype html>
<html>
<head>
  <?php include 'header.php'; ?>
  <script type="text/javascript" src="../js/modal.js"></script>
  <script type="text/javascript">
      function confirmDelete(ordinal){
      		$.confirm({
    			title: '',
    			content: '¿Está seguro que desea eliminar este autor?',
    			//type: 'blue',
    			columnClass: 'small',
    			buttons: {
    				Aceptar: {
    					btnClass: 'btn-info',
    					action:function () {
    						document.location.href = 'actionAuthor.php?delete=1&id=' + ordinal;
    					}
    				},
    				Cancelar: {
    					btnClass: 'btn-info',
    					action: function () {
    					
    					}
    				}
    			}
    		});
    		
      	}

      function validate(){
          var msgError = "";
          var cantError = 0;
          if($("#nmAuthor").val() == "" || $("#nmAuthor").val() == null){
            msgError += "El nombre del autor no puede quedar vacío;";
            cantError++;
          }
          if($("#snAuthor").val() == "" || $("#snAuthor").val() == null){
            msgError += "El apellido del autor no puede quedar vacío;";
            cantError++;
          }
          if(cantError > 0){
            msgError = msgError.slice(0,-1);
            showError(msgError);
            return false;
          }else{
            return true;
          }
    }
  
  </script>
</head>
<body style="background-position: 0 0;">
  <?php include "modal.php" ?>
  <div id="body" class="container">
    <?php include 'navAdmin.php'; ?>
    <div class="card" style="width: 98%; margin: 10px 1% 10px 1%;">
      <div class="card-body">
        <h4 class="card-title"><i class="fas fa-user-edit text-info"></i>&nbsp;Autores</h4><hr>
        <?php
          if(isset($_GET["update"])){

            if(isset($_GET["id"])){
              $where = array(
                "ID_AUTOR"=>$_GET["id"]
              );

              $row = $obj->loadSelected($where);
        ?>
          <form method="post" action="actionAuthor.php">
          	<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row["ID_AUTOR"]; ?>" onSubmit="return validate();">
            <div class="form-group">
              <label for="nombre">Nombre:</label>
              <input type="text" class="form-control" id="nmAuthor" name="nmAuthor" value="<?php echo $row["NOMBRE"]; ?>">
            </div>
            <div class="form-group">
              <label for="apellido">Apellido:</label>
              <input type="text" class="form-control" id="snAuthor" name="snAuthor" value="<?php echo $row["APELLIDO"]; ?>">
            </div>
            <button type="submit" name="edit" class="btn btn-info">Actualizar</button>
          </form> 
        <?php
            }
          } else {
        ?>
        <form method="post" action="actionAuthor.php" onSubmit="return validate();">
          <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nmAuthor" name="nmAuthor">
          </div>
          <div class="form-group">
            <label for="nombre">Apellido:</label>
            <input type="text" class="form-control" id="snAuthor" name="snAuthor">
          </div>
          <!--<input type="color">-->
          <button type="submit" name="insert" class="btn btn-info">Guardar</button>
        </form>
        <?php } ?>
        <br>
        <table class='table table-striped table-hover' style='width: 100%;'>
        <?php 
          $rows = $obj->loadAll();
          
          if($rows){ ?>
          <thead>
            <tr style='height: 40px;'>
              <th style='width: 46%;'>Nombre</th>
              <th style='width: 46%;'>Apellido</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
          <?php
              foreach ($rows as $row){ ?>
              <tr style='height: 40px;'>
                <td style='width: 46%;'><?php echo $row["NOMBRE"]; ?></td>
                <td style='width: 46%;'><?php echo $row["APELLIDO"]; ?></td>
                <td><a href="authors.php?update=1&id=<?php echo $row["ID_AUTOR"]; ?>"><i class="fas fa-edit text-info"></i></a></td>
                <td><a href="javascript:confirmDelete('<?php echo $row["ID_AUTOR"]; ?>');"><i class='fas fa-trash-alt text-info'></i></a></td>
              </tr>
            <?php 
              } 
            } else { ?>
              <thead>
                <tr style='height: 40px;'>
                  <th style='width: 100%; text-align: center;'>No hay ning&uacute;n autor cargado.</th>
                </tr>
              </thead>
            <?php } ?>
          </tbody>
        </table>
       </div>
    </div>
    <?php include "../footer.php"; ?>
  </div>
</body>
</html>
