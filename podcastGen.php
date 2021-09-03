<?php
 include "./admin/action.php";
  $passEncrypt = "emese345";
?>
<!doctype html>
<html >
<head>
  <?php include 'head.php'; ?>
  <script type="text/javascript">
    function copiarEnlace() {

      var copyText = document.getElementById("enlace");
      copyText.select();
      document.execCommand("copy");
    }
    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    function incRep(p,e) {
      var xmlhttp = new XMLHttpRequest();
      /*xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
              document.getElementById("txtHint").innerHTML = this.responseText;
          }
      };*/
      xmlhttp.open("POST", "incRep.php?p=" + p + "&e=" + e, true);
      xmlhttp.send();
    }
  </script>
</head>
<body>
  <p id="p1" style="display: none;">Escuchá nuestro podcast en @UNLaMPodcast desde: http://unlampodcast.com/podcastGen.php?id=<?php echo rawurlencode(rawurldecode($_GET["id"])); ?></p>
  <?php 
          $sql = "SELECT * FROM pu_podcasts WHERE ID_PODCAST = ?";
          $stmt = $obj->conn->prepare($sql);
          $auxDecrypt = openssl_decrypt($_GET["id"],"AES-128-ECB",$passEncrypt);
          $auxEncode = rawurldecode($auxDecrypt);
          $stmt->bind_param("d", $auxEncode);
          $stmt->execute();
          $result = $stmt->get_result();
          $rowPc = $result->fetch_assoc();
      ?>
  <div id="body" class="container">
    <a href="./index.php" title="Inicio">
    <?php if(1==0 && !empty($rowPc["ID_IMG_PORTADA"])){ ?>
      <img id="portada" src="./images/podcasts/portada/<?php echo $rowPc["ID_PODCAST"]; ?>.<?php echo $rowPc["ID_IMG_PORTADA"]; ?>" alt="Portada Podcast"">
  <?php } else { ?>
      <img id="portada" src="./img/p1_low_2.png" alt="UNLaM Podcasts">
  <?php } ?>
    </a>
    <?php include "nav.php"; ?>
    
    <div id="jumbo-header" class="jumbotron bg-light">
      <div class="container justify-content-center">
      <div class="row">
        <div class="col-sm-3 text-center p-1">
          <img class="img-thumbnail" src="./images/podcasts/episodio/<?php if($rowPc["ID_IMG_EPISODIO"] == null){echo "placeholder.png";}else{ echo $rowPc["ID_PODCAST"].".".$rowPc["ID_IMG_EPISODIO"];} ?>" alt="Carátula Podcast">
        </div>
        <div class="col-sm-9 p-2 text-center">
          <h1 class="text-info"><?php echo $rowPc["NOMBRE"]; ?></h1>
          <hr>
          <h1 class="text-info" style="font-size: 28px;">
            <div class="a-share">
            <a class="a-info" target="_blank" href="<?php echo $rowPc["LINK_SPOTIFY"]; ?>">
                &nbsp;<i class="fab fa-spotify"></i>
              </a>
            <a class="a-info" id="share" href="javascript:copyToClipboard('#p1');">
              &nbsp;<i class="fas fa-share-alt-square"></i>
            </a>
            <div class="toast hide">
              <div class="toast-body">
                ¡Enlace copiado!
              </div>
            </div>
          </div>
          </h1>
          &nbsp;
          <?php 
            $sql = "SELECT B.NOMBRE, B.DESCRIPCION, B.COLOR FROM pu_temas_podcast A INNER JOIN pu_temas B ON A.ID_TEMA = B.ID_TEMA WHERE A.ID_PODCAST = ?";
            $stmt = $obj->conn->prepare($sql);
            $auxDecrypt = openssl_decrypt($_GET["id"],"AES-128-ECB",$passEncrypt);
            $auxEncode = rawurldecode($auxDecrypt);
            $stmt->bind_param("d", $auxEncode);
            $stmt->execute();
            $resultTema = $stmt->get_result();

            while($rowTema = $resultTema->fetch_assoc()){
          ?>
            <span class="badge" style="background-color: <?php echo $rowTema["COLOR"] ?>;color: white;" data-toggle="tooltip" data-placement="top" title="<?php echo $rowTema["DESCRIPCION"] ?>"><?php echo $rowTema["NOMBRE"] ?></span>
          <?php } ?>
          <p><?php echo $rowPc["DESCRIPCION"] ?></p>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <p>Creado por:
          <?php 
            $sql = "SELECT B.NOMBRE, B.APELLIDO FROM pu_autores_podcast A INNER JOIN pu_autores B ON A.ID_AUTOR = B.ID_AUTOR WHERE A.ID_PODCAST = ?";
            $stmt = $obj->conn->prepare($sql);
            $auxDecrypt = openssl_decrypt($_GET["id"],"AES-128-ECB",$passEncrypt);
            $auxEncode = rawurldecode($auxDecrypt);
            $stmt->bind_param("d",$auxEncode);
            $stmt->execute();
            $resultAut = $stmt->get_result();

            while($rowAut = $resultAut->fetch_assoc()){
          ?>
            <b>&nbsp;<?php echo $rowAut["NOMBRE"] ?>&nbsp;<?php echo $rowAut["APELLIDO"] ?>&nbsp;-</b>
          <?php } ?>
          </p>
        </div>
      </div>
    </div>
    </div>
    <?php 
          $sql = "SELECT * FROM pu_episodios WHERE ID_PODCAST = ? AND FECHA_PUBLICACION <= DATE_ADD(NOW(),INTERVAL 4 HOUR)";
          $stmt = $obj->conn->prepare($sql);
          $auxDecrypt = openssl_decrypt($_GET["id"],"AES-128-ECB",$passEncrypt);
          $auxEncode = rawurldecode($auxDecrypt);
          $stmt->bind_param("d",$auxEncode);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          $cont = 0;

      if($row){
        echo "<div class='card-deck'>"; 

        while($row){
      ?>
        <div class="card in-left shadow">
          <div class="content justify-content-center">
              <div class="text-info ep-desc">Episodio <?php echo $row["ID_EPISODIO"]; ?>: <?php echo $row["TITULO"]; ?>
              </div>
              <hr class="hr-card">
              <p class="overflow-auto text-justify h-150 p-4">
                <?php echo $row["DESCRIPCION"]; ?>
              </p>
              <audio controls controlsList="nodownload" style="width: 100%;" onplay="javascript:incRep(<?php echo $row["ID_PODCAST"]; ?>,<?php echo $row["ID_EPISODIO"]; ?>)">
                <source src="./episodes/<?php echo $row["ID_AUDIO"] ?>" type="audio/ogg">
                <source src="./episodes/<?php echo $row["ID_AUDIO"] ?>" type="audio/mpeg">
                Tu navegador no soporta la reproducci&oacute;n de audios.
              </audio>
            </div>
        </div>
    <?php 
          $row = $result->fetch_assoc();
          $cont++;

          if($cont == 3){
            $cont = 0;
            echo "</div><div class='card-deck'>";
          }
        }

        echo "</div>"; 
      } else { 
    ?>
        <div class="card" style="margin: 10px 2% 10px 2%; text-align: center;">
          <div class="card-header">No hay ning&uacute;n episodio cargado.</div>
        </div><br/>
    <?php    
      }
    ?>
    <script>
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });
  </script>
  <script>
    $(document).ready(function(){
      $("#share").click(function(){
        $('.toast').toast('show');
      });
    });
  </script>
  <br/>
  <?php include "./footer.php"; ?>
  </div>

</body>
</html>