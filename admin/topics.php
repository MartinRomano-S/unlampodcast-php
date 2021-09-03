<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php
  include "actionTopic.php";
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
    			content: '¿Está seguro que desea eliminar este tema?',
    			//type: 'blue',
    			columnClass: 'small',
    			buttons: {
    				Aceptar: {
    					btnClass: 'btn-info',
    					action:function () {
    						document.location.href = 'actionTopic.php?delete=1&id=' + ordinal;
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
          if($("#nmTopic").val() == "" || $("#nmTopic").val() == null){
            msgError += "El nombre del tema no puede quedar vacío;";
            cantError++;
          }
          if($("#descTopic").val() == "" || $("#descTopic").val() == null){
            msgError += "La descripción del tema no puede quedar vacía;";
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
<body>
  <?php include "modal.php" ?>
  <div id="body" class="container">
    <?php include 'navAdmin.php'; ?>
    <div class="card" style="width: 98%; margin: 10px 1% 10px 1%;">
      <div class="card-body">
        <h4 class="card-title"><i class="fas fa-tags text-info"></i>&nbsp;Temas</h4><hr>
        <?php
          if(isset($_GET["update"])){

            if(isset($_GET["id"])){
              $where = array(
                "ID_TEMA"=>$_GET["id"]
              );

              $row = $obj->loadSelected($where);
        ?>
          <form method="post" action="actionTopic.php" onSubmit="return validate();">
            <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row["ID_TEMA"]; ?>">
            <div class="form-group">
              <label for="nombre">Nombre:</label>
              <input type="text" class="form-control" id="nmTopic" name="nmTopic" value="<?php echo $row["NOMBRE"]; ?>">
            </div>
            <div class="form-group">
              <label for="desc">Descripci&oacute;n:</label>
              <textarea class="form-control" rows="4" id="descTopic" name="descTopic" ><?php echo $row["DESCRIPCION"]; ?></textarea>
            </div>
            <button type="submit" name="edit" class="btn btn-info">Actualizar</button>
          </form> 
        <?php
            }
          } else {
        ?>
        <form method="post" action="actionTopic.php" onSubmit="return validate();">
          <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" class="form-control" id="nmTopic" name="nmTopic">
          </div>
          <div class="form-group">
            <label for="desc">Descripci&oacute;n:</label>
            <textarea class="form-control" rows="4" id="descTopic" name="descTopic"></textarea>
          </div>
          <button type="submit" name="insert" class="btn btn-info">Guardar</button>
        </form>
        <?php } ?>
        <br>
        <div class="table-responsive">
          <table class='table table-striped table-hover' style='width: 100%;'>
          <?php 
            $rows = $obj->loadAll();
            
            if($rows){ ?>
            <thead>
              <tr style='height: 40px;'>
                <th style='width: 21%;'>Tema</th>
                <th style='width: 71%;'>Descripci&oacute;n</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            <?php
                foreach ($rows as $row){ ?>
                <tr style='height: 40px;'>
                  <td style='width: 20%;'><?php echo $row["NOMBRE"]; ?></td>
                  <td style='width: 71%;'><?php echo $row["DESCRIPCION"]; ?></td>
                  <td><a href="topics.php?update=1&id=<?php echo $row["ID_TEMA"]; ?>"><i class="fas fa-edit text-info"></i></a></td>
                  <td><a href="javascript:confirmDelete('<?php echo $row["ID_TEMA"]; ?>');"><i class='fas fa-trash-alt text-info'></i></a></td>
                </tr>
              <?php 
                } 
              } else { ?>
                <thead>
                  <tr style='height: 40px;'>
                    <th style='width: 100%; text-align: center;'>No hay ning&uacute;n tema cargado.</th>
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
