<?php
  if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
  }
?>
<?php
  require_once('./dao/dao_programa.php');
  require_once('./obj/programa.php');
  require_once('./dao/dao_horarios.php');
  require_once('./obj/horario.php');
  $programaDAO =new DAOPrograma();
  $horarioDAO = new DAOHorario();
  $programa = new Programa();
  $horario = new Horario();
  $listaPrograma = $programaDAO->mostrar();
  $selected = "1";
?>
<!doctype html>
<html>
<head>
  <?php include 'header.php'; ?>
  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
  <script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.es-es.js" type="text/javascript"></script>
  <script type="text/javascript" src="../js/modal.js"></script>
  <style type="text/css">
    .btn-outline-secondary {
      border-color: #ced4da;
    }
  </style>
  
  <script type="text/javascript">
    function reload(){
      var e = document.getElementById("programa");
      var strUser = e.options[e.selectedIndex].value;

      window.location = "./horarios.php?pc=" + strUser;
    }
  </script>
</head>
  <body>
    <?php include "modal.php" ?>
    <div id="body" class="container">
      <?php include 'navAdmin.php'; ?>
      <div class="card" style="width: 98%; margin: 10px 1% 10px 1%;">
        <div class="card-body">
          <h4 class="card-title"><i class="fas fa-clock text-info"></i>&nbsp;Horarios</h4><hr>
            <form method="post" action="actionHorario.php">
            <div class="form-row">
              <div class="form-group col-sm-12 col-md-6">
                <label for="programa">Programa:</label>
                <select class="form-control" id="programa" name="programa" onchange="reload();">
                <?php 
                  foreach ($listaPrograma as $programa) {
                  
                  if(isset($_GET["pc"]) && $programa->getId()==$_GET["pc"]){ 
                    $selected = $programa->getId();
                  ?>
                          <option value="<?php echo $programa->getId(); ?>" selected><?php echo $programa->getNombre(); ?></option>
                    <?php } else { ?>
                          <option value="<?php echo $programa->getId(); ?>"><?php echo $programa->getNombre(); ?></option>
                    <?php
                        }
                    ?>
                <?php
                  }
                ?>
                </select>
              </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="dia">D&iacute;a:</label>
                    <?php include 'dias.php'; ?>
                </div>
            </div>
              <div class="form-row">
                <div class="form-group col-sm-12 col-md-6">
                    <label for="timepickerI">Hora de Inicio:</label>
                    <div class="custom-file">
                    <input id="timepickerI" name="timepickerI" type="text" readonly/>
                  </div> 
                </div>
                <div class="form-group col-sm-12 col-md-6">
                    <label for="timepickerF">Hora de Fin:</label>
                    <div class="custom-file">
                    <input id="timepickerF" name="timepickerF" type="text" readonly/>
                  </div>  
                </div>
              </div>
              <button type="submit" id="insert" name="insert" class="btn btn-info">Guardar</button>
            </form>
            <br>
            <div class="table-responsive">
              <table class='table table-striped table-hover' style='width: 100%;'>
                <?php
                $listaHorarios = $horarioDAO->mostrarPorPrograma($selected);
                if($listaHorarios){ ?>
                <thead>
                  <tr style='height: 40px;'>
                    <th style='width: 48%;'>D&iacute;a</th>
                    <th style='width: 48%;'>Horario</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($listaHorarios as $horario){ ?>
                    <tr style='height: 40px;'>
                      <td><?php echo $horario->getNombreDia(); ?></td>
                      <td><?php echo $horario->getHorario(); ?></td>
                      <td><a href="actionHorario.php?delete=1&id=<?php echo $horario->getId(); ?>&idp=<?php echo $horario->getIdPrograma(); ?>"><i class='fas fa-trash-alt text-info'></i></a></td>
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
  <script type="text/javascript">
    $('#timepickerI').timepicker({
        locale: 'es-es',
        uiLibrary: 'bootstrap4', 
        modal: true, 
        footer: true,
        mode: '24hr'
    });
    $('#timepickerF').timepicker({
        locale: 'es-es',
        uiLibrary: 'bootstrap4', 
        modal: true, 
        footer: true,
        mode: '24hr'
    });
  </script>
</html>
