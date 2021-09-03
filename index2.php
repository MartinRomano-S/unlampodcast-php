<?php include "./admin/action.php";
$passEncrypt = "emese345";?>
<!doctype html>
<html >
<head>
  <?php include 'head.php'; ?>
  <script type="text/javascript">
    function checkOffset() {
    if($('#ultEpis').offset().top + $('#ultEpis').height() 
                                           >= $('#footer').offset().top - 10)
        $('#ultEpis').removeClass('fixed-bottom');
    if($(document).scrollTop() + window.innerHeight < $('#footer').offset().top)
        $('#ultEpis').addClass('fixed-bottom');
    }
    $(document).scroll(function() {
        checkOffset();
    });

    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    function getMaxHeight(el){
      var ini = 0;
      var maxHeight = 0;

      while(document.getElementById(el + ini)){

        var alto = document.getElementById(el + ini).clientHeight;

        if(alto > maxHeight){
          maxHeight = alto;
        }

        ini++;
      }

      return maxHeight;
    }

    function equalHeight(){

      var maxHeight = getMaxHeight("podCard");
      var maxContentHeight = getMaxHeight("podContent");
      var maxFooterHeight = getMaxHeight("podFooter");
      var maxTitleHeight = getMaxHeight("podEpTitle");

      console.log("Altura maxima carta: " + maxHeight);
      console.log("Altura maxima content: " + maxContentHeight);
      console.log("Altura maxima footer: " + maxFooterHeight);
      console.log("Altura maxima title: " + maxTitleHeight);

      //recorremos y cambiamos la altura de esas cartas para que queden todas iguales
      //y cambiamos el content con la cuenta maxHeight - FooterHeight para no romper
      //el estilo
      var ini = 0;
      var footerTotalHeight = maxFooterHeight + maxTitleHeight;

      while(document.getElementById("podCard" + ini)){

        //document.getElementById("podCard" + ini).style.height = maxHeight + "px";
        document.getElementById("podFooter" + ini).style.height = maxFooterHeight + "px";
        
        document.getElementById("podEpTitle" + ini).style.height = maxTitleHeight + "px";
        document.getElementById("podContent" + ini).style.height = maxContentHeight + "px";
        ini++;
      }
    }

    function incRep(p,e) {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("POST", "incRep.php?p=" + p + "&e=" + e, true);
      xmlhttp.send();
    }

    window.onload = function() {
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("POST", "incRep.php?v=1", true);
      xmlhttp.send();
    };
  </script>
	<style>
	/* Make the image fully responsive */
	html, body, p, a, div, table, h2, h3, h4, h5 {
		font-family: 'Oswald', sans-serif !important;
		font-weight: 200 !important;
	}

	h1 {
		font-weight: 500 !important;
	}

	.carousel-inner img {
		width: 100%;
		height: 100%;
	}

	.a-info {
		color: #059473 !important;
	}

	.a-info:hover {
		color: #04383e !important;
		text-decoration: none;
	}

	.bg-info {
		background-color: #059473 !important;
	}

	.text-info {
		color: #059473 !important;
	}

	.jumbotron {
		border-radius: 0;
	}

	.card-deck .card {
		border-radius: 0;
		max-width: 348px;
	}

	.badge {
		font-weight: 400;
	}

	@media screen and (max-width: 767px) {

		.card-deck {
			margin-top: 0;
		}

		.card-deck .card {
			margin-left: 15px;
			margin-right: 15px;
		}

		#body {
			background-image: url('./img/bgtest.jpg') !important;
		}
	}
	</style>
</head>
<!--<body style="background: linear-gradient(#073a3e, #ffffff); background-repeat: no-repeat; background-position: 0 0;">-->
	<body style=" background-image: url('./img/bgtest.jpg'); background-repeat: repeat;">
  <div id="body" class="container" style="padding: 0px; width: 100%; background-image: url('./img/bgtest_old.jpg');">
    <a href="./index.php" title="Inicio">
      <img src="./img/p1.png" alt="UNLaM Podcast" style="max-width: 100%; height: auto; display: block; margin-left: auto; margin-right: auto;">
    </a>
    <?php include "nav.php"; ?>
    <!-- Modal Streams -->
    <!--<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-circle text-danger"></i>&nbsp;Live: Continuo Sonoro</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <a href="./stream.php">
              <img src="./img/stream.png" alt="Stream" style="max-width: 100%;">
            </a>
          </div>
          <div class="modal-footer d-block text-center">
            <button type="button" class="btn btn-info" onclick="location.href='./stream.php';">Escuchanos acá</button>
          </div>
        </div>
      </div>
    </div>-->
    <div class="jumbotron bg-light" style='margin: 20px 15px 0 15px; padding: 1rem 2rem;'>
      <h1 class="text-info" style="">UNLaM Podcast</h1>
      <p>Creado por alumnos de la Lic. en Comunicación Social de la Universidad Nacional de la Matanza para la especialización en radio del Taller de Administración y Producción en Medios.</p>
      <p>Seguinos en nuestras redes sociales:</p>
      <div class="content" style="width: 100%;">
        <?php 
          $sql = "SELECT A.TEXTO_LINK,A.LINK,B.ID_IMAGEN FROM pu_redes_podcast A INNER JOIN pu_red_social B ON A.ID_RED_SOCIAL = B.ID_RED_SOCIAL";
          $stmt = $obj->conn->prepare($sql);
          $stmt->execute();
          $resultRES = $stmt->get_result();

          while($rowRS = $resultRES->fetch_assoc()){
        ?>
        <a class="zoomManuals" href="<?php echo $rowRS["LINK"] ?>" target="_blank" title="<?php echo $rowRS["TEXTO_LINK"] ?>">
          <i class="fab <?php echo $rowRS["ID_IMAGEN"] ?>" style="font-size: 24px;"></i>
          &nbsp;<?php echo $rowRS["TEXTO_LINK"] ?>&nbsp;&nbsp;
        </a>
      <?php } ?>
      </div>
    </div>
    <?php 
      //precargados en nav.php
      $result->data_seek(0);
      $row = $result->fetch_assoc();
      $cont = 0;

      if($row){
        echo "<div class='card-deck' style='margin: 20px 0;'>"; 
        $number = 0;

        while($row){ 
    ?>
        <div id="podCard<?php echo $number; ?>" class="card in-left">
          <div id="podContent<?php echo $number; ?>" class="content">
          	<div class="zoom text-center w-100" style="padding-top: 15px;">
        		<a style="color: inherit;" href="./podcastGen.php?id=<?php echo rawurlencode(openssl_encrypt($row["ID_PODCAST"],"AES-128-ECB",$passEncrypt)); ?>">
        			<img style="max-width: 128px;" class="img-thumbnail"
        			src="./images/podcasts/episodio/<?php if($row["ID_IMG_EPISODIO"] == null){echo "placeholder.png";}else{ echo $row["ID_PODCAST"].".".$row["ID_IMG_EPISODIO"];} ?>" 
        			alt="Carátula Podcast">
        		</a>
        	</div>
        	<div class="text-center text-info" style="margin-top: 1rem;">
        		<p id="p<?php echo $row["ID_PODCAST"]; ?>" style="display: none;">Escuchá nuestro podcast en @UNLaMPodcast desde: http://unlampodcast.com/podcastGen.php?id=<?php echo rawurlencode(openssl_encrypt($row["ID_PODCAST"],"AES-128-ECB",$passEncrypt)); ?></p>
        		<h4 class="zoom">
        			<a style="color: inherit;" href="./podcastGen.php?id=<?php echo rawurlencode(openssl_encrypt($row["ID_PODCAST"],"AES-128-ECB",$passEncrypt)); ?>"><?php echo $row["NOMBRE"]; ?></a>
        		</h4>
        		<hr style="margin: 0 2rem 0 2rem;">
        		<p style="font-size: 18px;color: #99989a; margin-bottom: 0;">
        			<?php 
                    $sql = "SELECT B.NOMBRE, B.DESCRIPCION, B.COLOR FROM pu_temas_podcast A INNER JOIN pu_temas B ON A.ID_TEMA = B.ID_TEMA WHERE A.ID_PODCAST = ?";
                    $stmt = $obj->conn->prepare($sql);
                    $stmt->bind_param("d", $row["ID_PODCAST"]);
                    $stmt->execute();
                    $resultTema = $stmt->get_result();

                    while($rowTema = $resultTema->fetch_assoc()){
                  ?>
                    <span class="badge" style="background-color: <?php echo $rowTema["COLOR"] ?>;color: white;" data-toggle="tooltip" data-placement="top" title="<?php echo $rowTema["DESCRIPCION"] ?>"><?php echo $rowTema["NOMBRE"] ?></span>
                  <?php } ?>
        		</p>
        		<p style="font-size: 25px; margin-bottom: 1rem;">
        			<a class="a-info" target="_blank" href="<?php echo $row["LINK_SPOTIFY"]; ?>">
                        &nbsp;<i class="fab fa-spotify"></i>
                      </a>
                      <a id="share<?php echo $row["ID_PODCAST"] ?>" class="a-info" href="javascript:copyToClipboard('#p<?php echo $row["ID_PODCAST"]; ?>');">
                        <i class="fas fa-share-alt-square"></i>
                      </a>
                      <div class="toast hide" id="toast<?php echo $row["ID_PODCAST"] ?>">
                        <div class="toast-body" style="display: inline;">
                          ¡Enlace copiado!
                        </div>
                      </div>
					<script>
						$(document).ready(function(){
							$("#share<?php echo $row["ID_PODCAST"] ?>").click(function(){
								$('#toast<?php echo $row["ID_PODCAST"] ?>').toast('show');
							});
						});
					</script>
        		</p>
        	</div>
          </div>
          <div id="podFooter<?php echo $number; ?>" class="card-footer" style="text-align: center; padding: .5rem .5rem;">
            <?php
              $sql = "SELECT * FROM pu_episodios A WHERE ID_PODCAST = ? AND FECHA_PUBLICACION <= DATE_ADD(NOW(),INTERVAL 4 HOUR) ORDER BY FECHA_PUBLICACION DESC LIMIT 1";
              $stmt = $obj->conn->prepare($sql);
              $stmt->bind_param("d", $row["ID_PODCAST"]);
              $stmt->execute();
              $resultUE = $stmt->get_result();

              if($rowUE = $resultUE->fetch_assoc()){
            ?>
            <div id="podEpTitle<?php echo $number; ?>" class="text-info" style="font-size: 20px; margin-bottom: 5px;">
                Episodio <?php echo $rowUE["ID_EPISODIO"]; ?>: <?php echo $rowUE["TITULO"]; ?>
            </div>
            <audio controls controlsList="nodownload" style="width: 100%;" onplay="javascript:incRep(<?php echo $rowUE["ID_PODCAST"]; ?>,<?php echo $rowUE["ID_EPISODIO"]; ?>)">
              <source src="./episodes/<?php echo $rowUE["ID_AUDIO"] ?>" type="audio/ogg">
              <source src="./episodes/<?php echo $rowUE["ID_AUDIO"] ?>" type="audio/mpeg">
              Tu navegador no soporta la reproducci&oacute;n de audios.
            </audio>
            <?php } else { ?>
            	<div class="text-info" style="font-size: 20px; margin-bottom: 5px;">
                Aún no se han publicado episodios.
            	</div>
            	<audio controls controlsList="nodownload" style="width: 100%;" onplay="javascript:incRep(<?php echo $rowUE["ID_PODCAST"]; ?>,<?php echo $rowUE["ID_EPISODIO"]; ?>)">
	              <source src="#" type="audio/ogg">
	              <source src="#" type="audio/mpeg">
	              Tu navegador no soporta la reproducci&oacute;n de audios.
	            </audio>
            <?php } ?>
          </div>
        </div>
    <?php
          $row = $result->fetch_assoc();
          $cont++;
          $number++;
          if($cont == 3){
            $cont = 0;
            echo "</div><div class='card-deck' style='margin: 20px 0;'>";
          }
        }
      
        echo "</div>";
      }
    ?>
    <?php 
      $sql = "SELECT * FROM pu_episodios A INNER JOIN pu_podcasts B ON A.ID_PODCAST = B.ID_PODCAST WHERE FECHA_PUBLICACION <= DATE_ADD(NOW(),INTERVAL 4 HOUR) ORDER BY FECHA_PUBLICACION DESC LIMIT 1";
      $stmt = $obj->conn->prepare($sql);
      $stmt->execute();
      $resEpisode = $stmt->get_result();

      if($rowEpisode = $resEpisode->fetch_assoc()){
    ?>
    <div id="ultEpis" name="ultEpis" class="container jumbotron bg-info text-white fixed-bottom" style='margin: 20px 1% 0% 0%; padding: 1rem 2rem;right:auto;left:auto;'>
      <h6><i class="fas fa-headphones"></i>&nbsp;Último Episodio Subido: <?php echo $rowEpisode["NOMBRE"] ?>&nbsp;-&nbsp;Episodio <?php echo $rowEpisode["ID_EPISODIO"]; ?>: <?php echo $rowEpisode["TITULO"] ?></h6>
      <audio controls controlsList="nodownload" style="width: 100%;" onplay="javascript:incRep(<?php echo $rowEpisode["ID_PODCAST"]; ?>,<?php echo $rowEpisode["ID_EPISODIO"]; ?>)">
        <source src="./episodes/<?php echo $rowEpisode["ID_AUDIO"] ?>" type="audio/ogg">
        <source src="./episodes/<?php echo $rowEpisode["ID_AUDIO"] ?>" type="audio/mpeg">
        Tu navegador no soporta la reproducci&oacute;n de audios.
      </audio>
    </div>
    <?php } ?>
  <?php include "./footer.php"; ?>
  </div>
  <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
  </script>
  <!--Modal Streams-->
  <!--<script type="text/javascript">$('#exampleModal').modal();</script>-->
  <script type="text/javascript">
    $(document).ready(function(){
      equalHeight();
    });
  </script>
</body>
</html>
