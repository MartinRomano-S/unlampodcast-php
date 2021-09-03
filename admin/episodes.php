<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<?php
  include "actionEpisode.php";
?>
<!doctype html>
<html>
<head>
  <?php include 'header.php'; ?>
  <script src="http://malsup.github.com/jquery.form.js"></script>
  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
  <script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.es-es.js" type="text/javascript"></script>
  <script type="text/javascript" src="./upload.js"></script>
  <script type="text/javascript" src="../js/modal.js"></script>
  <script type="text/javascript">
    function reload(){
      var e = document.getElementById("selPodcast");
      var strUser = e.options[e.selectedIndex].value;

      window.location = "./episodes.php?pc=" + strUser;
    }
  </script>
  <script type="text/javascript">
  
    $(document).ready(function () {

        $("#finsert").submit(function (e) {
            $("#nmTitle").attr('readonly','readonly');
            $("#selPodcast").attr('onchange','');
            $("#selPodcast").css('pointer-events','none');
            $("#descEpisode").attr('readonly','readonly');
            $("#divEpisodeAudio").css('display','none');
            $("#datepicker").attr('readonly','readonly');
            $("#timepicker").attr('readonly','readonly')
            $("#insert").attr("disabled", 'disabled');
            $("#alertUpload").removeAttr('style');
            return true;

        });
        
         $("#fedit").submit(function (e) {
            $("#nmTitle").attr('readonly','readonly');
            $("#descEpisode").attr('readonly','readonly');
            $("#datepicker").attr('readonly','readonly');
            $("#timepicker").attr('readonly','readonly')
            $("#edit").attr("disabled", 'disabled');
            return true;

        });
    });
    function lockAll(){
         /*$("#insert").attr('disabled','disabled');
         $("#nmTitle").attr('readonly','readonly');
         $("#selPodcast").attr('type','hidden');
         $("#descEpisode").attr('readonly','readonly');
         $("#episodeAudio").attr('type','hidden');
         $("#datepicker").attr('readonly','readonly');
         $("#timepicker").attr('readonly','readonly');*/
    }
        
    function confirmDelete(ordinalPodcast,ordinalEp){
  		$.confirm({
			title: '',
			content: '¿Está seguro que desea eliminar este episodio?',
			//type: 'blue',
			columnClass: 'small',
			buttons: {
				Aceptar: {
					btnClass: 'btn-info',
					action:function () {
						document.location.href = 'actionEpisode.php?delete=1&id=' + ordinalPodcast + "&idep=" + ordinalEp;
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
          if($("#nmTitle").val() == "" || $("#nmTitle").val() == null){
            msgError += "El título no puede quedar vacío;";
            cantError++;
          }

          <?php if(!isset($_GET["update"])){ ?>
          if($("#selPodcast").val() == "" || $("#selPodcast").val() == null){
            msgError += "Debe seleccionar un Podcast;";
            cantError++;
          }

        <?php } ?>

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
        <h4 class="card-title"><i class="fas fa-headphones text-info"></i>&nbsp;Episodios</h4><hr>
        <?php
          if(isset($_GET["update"])){

            if(isset($_GET["id"]) && isset($_GET["idep"])){
              $where = array(
                "ID_PODCAST"=>$_GET["id"],
                "ID_EPISODIO"=>$_GET["idep"]
              );

              $row = $obj->loadSelected($where);

              $dtField = $row["FECHA_PUBLICACION"];

              $dtArray = explode(" ", $dtField);
              $dt = $dtArray[0];
              $hr = $dtArray[1];
              
              $dtArray = explode("-",$dt);

              $day = $dtArray[2];
              $month = $dtArray[1];
              $year = $dtArray[0];

              $hr = substr($hr, 0, -3);

              $fullDt = $month."/".$day."/".$year;
        ?>
          <form id="fedit" name="fedit" method="post" action="actionEpisode.php" onsubmit="return validate();">
          	<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row["ID_PODCAST"]; ?>">
            <input type="hidden" class="form-control" id="ide" name="ide" value="<?php echo $row["ID_EPISODIO"]; ?>">
            <input type="hidden" class="form-control" id="isEdit" name="isEdit" value="1">
            <div class="form-group">
              <label for="nombre">T&iacutetulo:</label>
              <input type="text" class="form-control " id="nmTitle" name="nmTitle" value="<?php echo $row["TITULO"]; ?>">
            </div>
            <div class="form-group">
              <label for="desc">Descripci&oacute;n:</label>
              <textarea class="form-control" rows="4" id="descEpisode" name="descEpisode"><?php echo $row["DESCRIPCION"]; ?></textarea>
            </div>
            <div class="form-group">
              <label for="datepicker">Fecha de Publicaci&oacute;n: (mm/dd/aaaa)</label>
              <div class="custom-file">
              <input id="datepicker" name="datepicker" width="276" type="text" readonly value="<?php echo $fullDt; ?>" />
            </div>  
          </div>
          <div class="form-group">
              <label for="timepicker">Hora de Publicaci&oacute;n:</label>
              <div class="custom-file">
              <input id="timepicker" name="timepicker" width="276" type="text" readonly value="<?php echo $hr; ?>"/>
            </div>  
          </div>
            <button id="edit" name="edit" class="btn btn-info" >Actualizar</button>
          </form> 
        <?php
            }
          } else {
        ?>
        <form id="finsert" name="finsert" method="post" action="actionEpisode.php" enctype="multipart/form-data">
            <input type="hidden" class="form-control" id="isInsert" name="isInsert" value="1">
          <div class="form-group">
            <label for="selPodcast">Podcast:</label>
              <select class="form-control" id="selPodcast" name="selPodcast" onchange="reload();">
                <option value="">--- Seleccionar Podcast ---</option>
            <?php 
              if($_SESSION['type']==1){
                $sql = "SELECT ID_PODCAST, NOMBRE FROM pu_podcasts";
                $stmt = $obj->conn->prepare($sql);
                $stmt->execute();
              } else {
                $sql = "SELECT B.ID_PODCAST, B.NOMBRE FROM pu_usuarios_podcast A INNER JOIN pu_podcasts B ON A.ID_PODCAST = B.ID_PODCAST WHERE A.ID_USUARIO = ?";
                $stmt = $obj->conn->prepare($sql);
                $stmt->bind_param("s", $_SESSION['user']);
                $stmt->execute();
              }
              
              $resultPC = $stmt->get_result();

              while($rowPC = $resultPC->fetch_assoc()){
                if(isset($_GET["pc"]) && $rowPC["ID_PODCAST"]==$_GET["pc"]){ ?>
                  <option value="<?php echo $rowPC["ID_PODCAST"]; ?>" selected><?php echo $rowPC["NOMBRE"]; ?></option>
            <?php } else { ?>
                  <option value="<?php echo $rowPC["ID_PODCAST"]; ?>"><?php echo $rowPC["NOMBRE"]; ?></option>
            <?php
                }
            ?>
                
            <?php } ?>
              </select>
          </div>
          <div class="form-group">
            <label for="nombre">T&iacutetulo:</label>
            <input type="text" class="form-control " id="nmTitle" name="nmTitle">
          </div>
          <div class="form-group">
            <label for="desc">Descripci&oacute;n:</label>
            <textarea class="form-control " rows="4" id="descEpisode" name="descEpisode"></textarea>
          </div>
          <div class="form-group">
              <label for="episodeAudio">Audio:</label>
              <div id="divEpisodeAudio" name="divEpisodeAudio" class="custom-file">
                <input type="file" class="custom-file-input " id="episodeAudio" name="episodeAudio">
                <label class="custom-file-label" for="episodeAudio" data-browse="Elegir">Seleccionar Archivo</label>
              </div>
          </div>
          <div id="alertUpload" name="alertUpload" style="display:none;" class="form-group">
                <p><b>Subiendo archivo... por favor espere.</b></p>
          </div>
          
           <div id="progress" name="progress" class="progress">
            <div id="progressBar" name="progressBar" width="50%" class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div><br>
          <div class="form-group">
              <label for="datepicker">Fecha de Publicaci&oacute;n: (mm/dd/aaaa)</label>
              <div class="custom-file ">
              <input id="datepicker" name="datepicker" width="276" type="text" readonly />
            </div>  
          </div>
          <div class="form-group">
              <label for="timepicker">Hora de Publicaci&oacute;n:</label>
              <div class="custom-file ">
              <input id="timepicker" name="timepicker" width="276" type="text" readonly />
            </div>  
          </div>
          <button id="insert" name="insert" onclick="uploadFile();" class="btn btn-info">Guardar</button>
        </form>
        <?php } ?>
        <br>
        <?php if(isset($_GET["pc"])){ ?>
        <div class="table-responsive">
          <table class='table table-striped table-hover' style='width: 100%;'>
          <?php 
            if(isset($_GET["pc"])){
              $rows = $obj->loadAll($_GET["pc"]);
              
              if($rows){ ?>
              <thead>
                <tr style='height: 40px;'>
                  <th style='width: 20%;'>N°</th>
                  <th style='width: 40%;'>Titulo</th>
                  <th style='width: 32%;'>Fecha de Publicaci&oacute;n</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
              <?php
                  foreach ($rows as $row){ ?>
                  <tr style='height: 40px;'>
                    <td style='width: 20%;'><?php echo $row["ID_EPISODIO"]; ?></td>
                    <td style='width: 40%;'><?php echo $row["TITULO"]; ?></td>
                    <td style='width: 32%;'>
                      <?php 
                        if($row["FECHA_PUBLICACION"] != '1800-01-01 00:00:00' and $row["FECHA_PUBLICACION"] != '0000-00-00 00:00:00'){
                          echo $row["FECHA_PUBLICACION"]; 
                        }
                        
                      ?>
                    </td>
                    <td><a href="episodes.php?update=1&id=<?php echo $row["ID_PODCAST"]; ?>&idep=<?php echo $row["ID_EPISODIO"]; ?>"><i class="fas fa-edit text-info"></i></a></td>
                    <td><a href="javascript:confirmDelete('<?php echo $row["ID_PODCAST"]; ?>','<?php echo $row["ID_EPISODIO"]; ?>');"><i class='fas fa-trash-alt text-info'></i></a></td>
                  </tr>
                <?php 
                  } 
                } else { ?>
                  <thead>
                    <tr style='height: 40px;'>
                      <th style='width: 100%; text-align: center;'>No hay ning&uacute;n episodio cargado.</th>
                    </tr>
                  </thead>
              <?php } 
            }?>
            </tbody>
          </table>
        </div>
        <?php } ?>
       </div>
    </div>
    <?php include "../footer.php"; ?>
  </div>
  <script>
        $('.custom-file-input').on('change',function(){
            //get the file name
            var fileName = $(this).val().split('\\').pop(); 
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(fileName);
        })
    </script>

    <script>
        $('#datepicker').datepicker({
            locale: 'es-es',
            uiLibrary: 'bootstrap4'
        });
        $('#timepicker').timepicker({
            locale: 'es-es',
            uiLibrary: 'bootstrap4', 
            modal: true, 
            footer: true,
            mode: '24hr'
        });
    </script>
</body>
</html>
