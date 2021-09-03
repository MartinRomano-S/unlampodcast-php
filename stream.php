<?php include "./admin/action.php";
$passEncrypt = "emese345";?>
<!doctype html>
<html>
<head>
  <?php include 'head.php'; ?>
  <style>
    .mi-iframe {
      width: 100%;
      height: 700px;
    }

    /*@media (min-width: 320px) {

      .mi-iframe {
        height: 350px;
      } 

    }

    @media (min-width: 768px) {

      .mi-iframe {
        height: 700px;
      } 

    }*/
  </style>
</head>
<body style="background-position: 0 0;">
  <div class="container" style="padding: 0px; background-image: url('../img/bgtest.jpg'); height: 100%;">
    <a href="./index.php" title="Inicio">
      <img src="./img/portadaJornada.jpg" alt="UNLaM Podcast" style="max-width: 100%; height: auto; display: block; margin-left: auto; margin-right: auto;">
    </a>
    <nav class="navbar navbar-expand-md bg-info navbar-dark sticky-top">
		<!--<nav class="navbar navbar-expand-md bg-dark navbar-dark sticky-top">-->
		<!-- Para pantallas pequeñas -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		  <span class="navbar-toggler-icon"></span>
		</button>
		<!-- Links -->
		<div class="zoom">
		  <a class="navbar-brand" href="./index.php"><img src="./img/tooltip.png" class="rounded-circle" style="width: 32px;" alt="UNLaM Podcasts"></a>
		</div>
		<?php 
		    $passEncrypt = "emese345";
		    $sql = "SELECT * FROM pu_podcasts";
		    $stmt = $obj->conn->prepare($sql);
		    $stmt->execute();
		    $result = $stmt->get_result();
		    $count = mysqli_num_rows($result);

		    if($count > 0){
		      $width = 100/$count;
		    }
		?>
		<div class="collapse navbar-collapse" id="collapsibleNavbar">
		    <ul class="navbar-nav" style="width: 100%;">
		  <?php
		      while($row = $result->fetch_assoc()){ ?>
		    <li class="nav-item" style="width: <?php echo $width; ?>%; text-align: center; padding-top: auto; padding-right: auto;">
		      <div class="zoom">
		        <a class="nav-link active" href="./podcastGen.php?id=<?php echo rawurlencode(openssl_encrypt($row["ID_PODCAST"],"AES-128-ECB",$passEncrypt)) ?>"><?php echo "#".str_replace(" ", "",$row["NOMBRE"]); ?></a>
		      </div>
		    </li>
		  <?php } ?>
		  </ul>
		</div>
	</nav>
    <div class="card" style="width: 98%; margin: 10px 1% 10px 1%;">
      <div class="card-body">
        <h4 class="card-title"><span class="inoutloop"><i class="fas fa-circle text-danger"></i></span>&nbsp;Live: Continuo Sonoro</h4><hr>
        Primera jornada radial de la Universidad Nacional de La Matanza realizada y producida íntegramente por alumnos de la carrera Comunicación Social, que se encuentran cursando los talleres de radio.<br><br>El evento se realizará el viernes 8 de noviembre desde las 10:00 hasta las 18:00.<br><br>
        <a href="https://www.facebook.com/continuosonorounlam/" title="Continuo Sonoro - Jornadas de Radio">
        	<span class="text-info" style="font-size: 20px;"><i class="fab fa-facebook-square"></i></span> Continuo Sonoro - Jornadas de Radio
        </a>
        <a href="https://www.instagram.com/continuosonorounlam/" title="@continuosonorounlam">
        	<br><span class="text-info" style="font-size: 20px;"><i class="fab fa-instagram"></i></span> @continuosonorounlam
        </a>
        <a href="https://twitter.com/SonoroUnlam" title="@sonorounlam">
        	<br><span class="text-info" style="font-size: 20px;"><i class="fab fa-twitter-square"></i></span> @sonorounlam
        </a>
        <br><hr>
        <?php
			$date1 = new DateTime(); 
			$date2 = new DateTime("2019-11-08"); 
			  
			if ($date1 >= $date2) {
		?>
        <iframe
          class="mi-iframe"
          src="http://www.unirock.com.ar/"
          frameborder="0">
        </iframe>
    	<?php } else { ?>
    	<div class="text-center" style="width: 100%;">
    		<span class="text-info" style="font-size: 40px;"><i class="fas fa-broadcast-tower"></i></span><br>
    		<span style="font-size: 20px;"><b>La trasmisión aún no ha comenzado</b></span>
    	</div>
    	<?php } ?>
       </div>
    </div>
    <script type="text/javascript">$('#exampleModal').modal();</script>
    <?php include "./footer.php"; ?>
  </div>
</body>
</html>

