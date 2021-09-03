<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php
  include "actionSocialNetwork.php";
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
			content: '¿Está seguro que desea eliminar esta red social?',
			//type: 'blue',
			columnClass: 'small',
			buttons: {
				Ok: {
					btnClass: 'btn-info',
					action:function () {
						document.location.href = 'actionSocialNetwork.php?delete=1&id=' + ordinal;
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

      if($("#txtLink").val() == "" || $("#txtLink").val() == null){
        msgError += "Debe ingresar un texto para el enlace;";
        cantError++;
      }

      if($("#link").val() == "" || $("#link").val() == null){
        msgError += "Debe ingresar un enlace;";
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
        <h4 class="card-title"><i class="fas fa-project-diagram text-info"></i>&nbsp;Redes Sociales</h4><hr>
        <?php
          if(isset($_GET["update"])){

            if(isset($_GET["id"])){
              $where = array(
                "ORDINAL"=>$_GET["id"]
              );

              $row = $obj->loadSelected($where);
        ?>
          <form method="post" action="actionSocialNetwork.php">
          	<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row["ORDINAL"]; ?>">
            <div class="form-group">
              <label for="sel1">Red Social:</label>
              <select class="form-control" id="sel1" name="sel1">
                <option value="<?php echo $row["ID_RED_SOCIAL"]; ?>" selected><?php echo $row["NOMBRE"]; ?></option>
                <?php 
                    $where = array(
                      "ID_RED_SOCIAL"=>$row["ID_RED_SOCIAL"]
                    );
                    $rowsRS = $obj->loadAllRSFiltered($where);
                
                      foreach ($rowsRS as $RS){ 
                ?>
                      <option value="<?php echo $RS["ID_RED_SOCIAL"]; ?>"><?php echo $RS["NOMBRE"]; ?></option>
                <?php 
                      }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="txtLink">Texto del enlace:</label>
              <input type="text" class="form-control" id="txtLink" name="txtLink" value="<?php echo $row["TEXTO_LINK"]; ?>">
            </div>
            <div class="form-group">
              <label for="link">Enlace:</label>
              <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-link"></i></span></div>
                <input type="text" class="form-control" id="link" name="link" value="<?php echo $row["LINK"]; ?>">
              </div>
            </div>
            <button type="submit" name="edit" class="btn btn-info">Actualizar</button>
          </form> 
          <form method="post" id="formDelete" name="formDelete">

          </form>
        <?php
            }
          } else {
        ?>
        <form method="post" action="actionSocialNetwork.php" onsubmit="return validate();">
          <div class="form-group">
            <label for="sel1">Red Social:</label>
            <select class="form-control" id="sel1" name="sel1">
        <?php 
            $rows = $obj->loadAllRS();
            
            if($rows){ 
        
              foreach ($rows as $row){ 
        ?>
              <option value="<?php echo $row["ID_RED_SOCIAL"]; ?>"><?php echo $row["NOMBRE"]; ?></option>
        <?php 
              }
            } 
        ?>
            </select>
          </div>
          <div class="form-group">
            <label for="txtLink">Texto del enlace:</label>
            <input type="text" class="form-control" id="txtLink" name="txtLink">
          </div>
          <div class="form-group">
            <label for="link">Enlace:</label>
            <div class="input-group">
              <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-link"></i></span></div>
              <input type="text" class="form-control" id="link" name="link">
            </div>
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
                <th style='width: 15%;'>Red Social</th>
                <th style='width: 25%;'>Texto del enlace</th>
                <th style='width: 52%;'>Enlace</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            <?php
                foreach ($rows as $row){ ?>
                <tr style='height: 40px;'>
                  <td style='width: 15%;'><?php echo $row["NOMBRE"]; ?></td>
                  <td style='width: 20%;'><?php echo $row["TEXTO_LINK"]; ?></td>
                  <td style='width: 52%;'><a title="Enlace <?php echo $row["NOMBRE"]; ?>" target="_blank" href="<?php echo $row["LINK"]; ?>"><?php echo $row["LINK"]; ?></a></td>
                  <td><a href="socialNetworks.php?update=1&id=<?php echo $row["ORDINAL"]; ?>"><i class="fas fa-edit text-info"></i></a></td>
                  <!--<td><a href="actionSocialNetwork.php?delete=1&id=<?php echo $row["ORDINAL"]; ?>"><i class='fas fa-trash-alt text-info'></i></a></td>-->
                  <td><a href="javascript:confirmDelete('<?php echo $row["ORDINAL"]; ?>');"><i class='fas fa-trash-alt text-info'></i></a></td>
                </tr>
            <?php 
              } 
            } else { ?>
                <thead>
                  <tr style='height: 40px;'>
                    <th style='width: 100%; text-align: center;'>No hay ninguna red social cargada.</th>
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
