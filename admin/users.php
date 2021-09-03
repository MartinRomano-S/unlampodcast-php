<?php
  if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
  }

  if(isset($_SESSION['type'])){
    if($_SESSION['type']==0){
      header("Location: ./admin.php");
    }
  }
?>
<?php
  include "actionUser.php";
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
			content: '¿Está seguro que desea eliminar este usuario?',
			//type: 'blue',
			columnClass: 'small',
			buttons: {
				Aceptar: {
					btnClass: 'btn-info',
					action:function () {
						document.location.href = 'actionUser.php?delete=1&id=' + ordinal;
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
      document.getElementById("flagS").value=0;

      if(document.getElementById("chkSuper").checked){
        document.getElementById("flagS").value=1;
      }
      document.getElementById("flagB").value=0;

      if(document.getElementById("chkBlocked").checked){
        document.getElementById("flagB").value=1;
      }
    }
  </script>
  <script type="text/javascript">
    function validatePWD(){
      var msgError = "";
      var cantError = 0;

      if($("#pwd").val() == "" || $("#pwd").val() == null){
        msgError += "La contraseña no puede quedar vacía;";
        cantError++;
      }

      if($("#rppwd").val() == "" || $("#rppwd").val() == null){
        msgError += "Debe repetir la contraseña para continuar;";
        cantError++;
      }

      if($("#idUser").val() == "" || $("#idUser").val() == null){
        msgError += "El nombre de usuario no puede quedar vacío;";
        cantError++;
      }

      if($("#rppwd").val() != $("#pwd").val()){
        msgError += "Las contraseñas no coinciden;";
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

    function validateGuardar(){
      var msgError = "";
      var cantError = 0;
      document.getElementById("flagS").value=0;

      if(document.getElementById("chkSuper").checked){
        document.getElementById("flagS").value=1;
      }
      document.getElementById("flagB").value=0;

      if(document.getElementById("chkBlocked").checked){
        document.getElementById("flagB").value=1;
      }

      if($("#pwd").val() == "" || $("#pwd").val() == null){
        msgError += "La contraseña no puede quedar vacía;";
        cantError++;
      }

      if($("#rppwd").val() == "" || $("#rppwd").val() == null){
        msgError += "Debe repetir la contraseña para continuar;";
        cantError++;
      }

      if($("#idUser").val() == "" || $("#idUser").val() == null){
        msgError += "El nombre de usuario no puede quedar vacío;";
        cantError++;
      }

      if($("#rppwd").val() != $("#pwd").val()){
        msgError += "Las contraseñas no coinciden;";
        cantError++;
      }

      if($("#idUser").val().length > 25){
        msgError += "El nombre de usuario no puede superar los 25 caracteres;";
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
          <h4 class="card-title"><i class="fas fa-user-friends text-info"></i>&nbsp;Usuarios</h4><hr>
          <?php
          if(isset($_GET["update"])){

            if(isset($_GET["id"])){
              $where = array(
                "ID_USUARIO"=>$_GET["id"]
              );

              $row = $obj->loadSelected($where);
        ?>
          <form method="post" action="actionUser.php">
            <input type="hidden" id="idUserOld" name="idUserOld" value="<?php echo $row["ID_USUARIO"]; ?>">
            <input type="hidden" id="flagS" name="flagS" value="0">
            <input type="hidden" id="flagB" name="flagB" value="0">
            <div class="form-group">
              <label for="nombre">Usuario:</label>
              <input type="text" class="form-control" id="idUser" name="idUser" readonly value="<?php echo $row["ID_USUARIO"]; ?>">
            </div>
            <div class="form-group">
              <label for="nombre">Mail:</label>
              <input type="mail" class="form-control" id="mail" name="mail" value="<?php echo $row["MAIL"]; ?>">
            </div>
            <div class="form-group form-check">
              <label class="form-check-label" style="width: 50%;">
                <?php if($row["SUPER"]==1) {?>
                  <input class="form-check-input" type="checkbox" id="chkSuper" name="chkSuper" checked> Super-Usuario
                <?php } else { ?>
                  <input class="form-check-input" type="checkbox" id="chkSuper" name="chkSuper"> Super-Usuario
                <?php } ?>
              </label>
              <label class="form-check-label">
                <?php if($row["BLOQUEADO"]==1) {?>
                  <input class="form-check-input" type="checkbox" id="chkBlocked" name="chkBlocked" checked> Bloqueado
                <?php } else { ?>
                  <input class="form-check-input" type="checkbox" id="chkBlocked" name="chkBlocked"> Bloqueado
                <?php } ?>
              </label>
            </div><br/>
            <button type="submit" name="edit" class="btn btn-info" onclick="javascript:validate()">Actualizar</button>
          </form> 
        
        <?php
            }
          } else {
            if(isset($_GET["change"])){
              if(isset($_GET["id"])){
              $where = array(
                "ID_USUARIO"=>$_GET["id"]
              );

              $row = $obj->loadSelected($where);
        ?>
            <form method="post" action="actionUser.php" onsubmit="return validatePWD();">
              <div class="form-group">
                <label for="nombre">Usuario:</label>
                <input type="text" class="form-control" id="idUser" name="idUser" readonly value="<?php echo $row["ID_USUARIO"]; ?>">
              </div>
              <div class="form-group">
                <label for="nombre">Contraseña:</label>
                <input type="password" class="form-control" id="pwd" name="pwd">
              </div>
              <div class="form-group">
                <label for="nombre">Repetir Contraseña:</label>
                <input type="password" class="form-control" id="rppwd" name="rppwd">
              </div>
              <button type="submit" name="change" class="btn btn-info">Cambiar contraseña</button>
            </form>
        <?php
            }
          } else {
        ?>
            <form method="post" action="actionUser.php" onsubmit="return validateGuardar();">
              <input type="hidden" id="flagS" name="flagS" value="0">
              <input type="hidden" id="flagB" name="flagB" value="0">
              <div class="form-group">
                <label for="nombre">Usuario:</label>
                <input type="text" class="form-control" id="idUser" name="idUser">
              </div>
              <div class="form-group">
                <label for="nombre">Mail:</label>
                <input type="mail" class="form-control" id="mail" name="mail">
              </div>
              <div class="form-group">
                <label for="nombre">Contraseña:</label>
                <input type="password" class="form-control" id="pwd" name="pwd">
              </div>
              <div class="form-group">
                <label for="nombre">Repetir Contraseña:</label>
                <input type="password" class="form-control" id="rppwd" name="rppwd">
              </div>
              <div class="form-group form-check">
                <label class="form-check-label" style="width: 50%;">
                  <input class="form-check-input" type="checkbox" id="chkSuper" name="chkSuper"> Super-Usuario
                </label>
                <label class="form-check-label">
                  <input class="form-check-input" type="checkbox" id="chkBlocked" name="chkBlocked"> Bloqueado
                </label>
              </div>
              <button type="submit" name="insert" class="btn btn-info">Guardar</button>
            </form>
        <?php }
        }
         ?>
        <br>
        <div class="table-responsive">
          <table class='table table-striped table-hover' style='width: 100%;'>
          <?php 
            $rows = $obj->loadAll();
            
            if($rows){ ?>
            <thead>
              <tr style='height: 40px;'>
                <th style='width: 23%;'>Usuario</th>
                <th style='width: 23%;'>Mail</th>
                <th style='width: 21%;'>Super-Usuario</th>
                <th style='width: 21%;'>Bloqueado</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
            <?php
                foreach ($rows as $row){ ?>
                <tr style='height: 40px;'>
                  <td><?php echo $row["ID_USUARIO"]; ?></td>
                  <td><?php echo $row["MAIL"]; ?></td>
                  <td>
                    <?php
                    if($row["SUPER"]==1){
                      echo "Si";
                    } else {
                      echo "No";
                    }
                    ?>
                  </td>
                  <td>
                    <?php
                    if($row["BLOQUEADO"]==1){
                      echo "Si";
                    } else {
                      echo "No";
                    }
                    ?>
                  </td>
                  <td><a href="users.php?change=1&id=<?php echo $row["ID_USUARIO"]; ?>"><i class="fas fa-key text-info"></i></td>
                  <td><a href="users.php?update=1&id=<?php echo $row["ID_USUARIO"]; ?>"><i class="fas fa-edit text-info"></i></a></td>
                  <td><a href="javascript:confirmDelete('<?php echo $row["ID_USUARIO"]; ?>');"><i class='fas fa-trash-alt text-info'></i></a></td>
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
