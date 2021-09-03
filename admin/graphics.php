<?php
  if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
  }
?>
<?php
  include "actionGraphics.php";
?>
<!doctype html>
<html>
<head>
  <?php include 'header.php'; ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
  <script type="text/javascript">
    function reload(){
      var e = document.getElementById("selPodcast");
      var strUser = e.options[e.selectedIndex].value;

      window.location = "./graphics.php?pc=" + strUser;
    }
  </script>
</head>
  <body>
    <div id="body" class="container">
      <?php include 'navAdmin.php'; ?>
      <div class="card" style="width: 98%; margin: 10px 1% 10px 1%;">
        <div class="card-body">
          <h4 class="card-title"><i class="far fa-chart-bar text-info"></i>&nbsp;Estad&iacute;sticas</h4><hr>
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
            <?php if(isset($_GET["pc"])) { ?>
            <!--<div class="form-group">
            	<div class="row">
            		<div class="col-sm-6">
            			<label for="desde">Episodio Desde:</label>
              			<input type="text" class="form-control " id="desde" name="desde">
            		</div>
            		<div class="col-sm-6">
            			<label for="desde">Episodio Hasta:</label>
              			<input type="text" class="form-control " id="hasta" name="hasta">
            		</div>
            	</div>
              
            </div>-->
        	<?php } ?>
          </form>
          <?php if(isset($_GET["pc"])) {

            $sql = "SELECT * FROM pu_episodios WHERE ID_PODCAST = ?";
            $stmt = $obj->conn->prepare($sql);
            $stmt->bind_param("s", $_GET["pc"]);
            $stmt->execute();

            $resultEP = $stmt->get_result();
            $labelsEP = "";
            $dataEP = "";

            while($rowEP = $resultEP->fetch_assoc()){
            
              $labelsEP.=",'Episodio ".$rowEP["ID_EPISODIO"]."'";
              $dataEP.=",".$rowEP["REPRODUCCIONES"];
            }

            $labelsEP = ltrim($labelsEP, ',');
            $dataEP = ltrim($dataEP, ',');
            //error_log($labelsEP, 0);
            //error_log($dataEP, 0);
          ?>
          <canvas id="myChart"></canvas>
          <div class="table-responsive">
          <table class='table table-striped table-hover text-center' style='width: 100%;'>
          <?php 
            if(isset($_GET["pc"])){
              	$sql = "SELECT SUM(REPRODUCCIONES) as TOTAL, AVG(REPRODUCCIONES) as PROM FROM pu_episodios WHERE ID_PODCAST = ?";
	            $stmt = $obj->conn->prepare($sql);
	            $stmt->bind_param("s", $_GET["pc"]);
	            $stmt->execute();
	            $resultStats = $stmt->get_result();
	            $total = "-";
	            $prom = "-";

	            if($rowStats = $resultStats->fetch_assoc()){
	            	$total = $rowStats["TOTAL"];
	            	$prom = $rowStats["PROM"];
	            }

	            $sql = "SELECT SUM(REPRODUCCIONES) as TOTAL, AVG(REPRODUCCIONES) as PROM FROM pu_episodios WHERE ID_PODCAST = ? AND ID_EPISODIO BETWEEN 1 and 3";
	            $stmt = $obj->conn->prepare($sql);
	            $stmt->bind_param("s", $_GET["pc"]);
	            $stmt->execute();
	            $resultStats = $stmt->get_result();
	            $totalRNG = "-";
	            $promRNG = "-";

	            if($rowStats = $resultStats->fetch_assoc()){
	            	$totalRNG = $rowStats["TOTAL"];
	            	$promRNG = $rowStats["PROM"];
	            }
            ?>
              <thead>
                <tr style='height: 40px;'>
                  <!--<th style='width: 25%;'>Total Rango</th>-->
                  <th style='width: 50%;'>Total</th>
                  <!--<th style='width: 25%;'>Promedio Rango</th>-->
                  <th style='width: 50%;'>Promedio</th>
                </tr>
              </thead>
              <tbody>
                  <tr style='height: 40px;'>
                    <!--<td style='width: 25%;'><?php echo $totalRNG; ?></td>-->
                    <td style='width: 50%;'><?php echo $total; ?></td>
                    <!--<td style='width: 25%;'><?php echo $promRNG; ?></td>-->
                    <td style='width: 50%;'><?php echo $prom; ?></td>
                  </tr>
            <?php 
              } else { ?>
                  <thead>
                    <tr style='height: 40px;'>
                      <th style='width: 100%; text-align: center;'>No se seleccionó un podcast.</th>
                    </tr>
                  </thead>
            <?php 
        		} 
            ?>
            </tbody>
          </table>
        </div>
          <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var chart = new Chart(ctx, {
            // The type of chart we want to create
              type: 'bar',

              // The data for our dataset
              data: {
                  labels: [<?php echo $labelsEP; ?>],
                  datasets: [{
                      label: 'Reproducciones',
                      backgroundColor: '#fd7e14',
                      borderColor: '#000',
                      data: [<?php echo $dataEP; ?>]
                  }]
              },

              // Configuration options go here
              options: {
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero: true
                          }
                      }]
                  }
              }
            });
          </script>
        <?php } ?>
        </div>
      </div>
      <?php include "../footer.php"; ?>
    </div>
  </body>
</html>
