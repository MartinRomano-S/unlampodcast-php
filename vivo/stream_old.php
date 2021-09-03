<?php 
include "../admin/action.php";
$passEncrypt = "emese345";
require_once('../admin/dao/dao_programa.php');
require_once('../admin/obj/programa.php');
require_once('../admin/obj/horario.php');
require_once('../admin/dao/dao_horarios.php');
$programaDAO =new DAOPrograma();
$horarioDAO =new DAOHorario();
$programa = new Programa();
$horario = new Horario();
$listaPrograma = $programaDAO->mostrar();
?>
<!doctype html>
<html >
<head>
  <?php include 'head.php'; ?>
  <script type="text/javascript">
    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }
  </script>
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
<body>
  <div id="body" class="container">
    <a href="./stream.php" title="Inicio">
      <img id="portada" src="../img/2.png" alt="UNLaM Podcast">
    </a>
    <?php include "nav.php"; ?>
    <div id="jumbo-header" class="jumbotron bg-light">
      <span class="text-info logo-medium">UNLaM </span>
      <span class="logo-light"><i>vivo</i></span>
      <!--<p>
        Pandemmials es un programa de radio ¡online y en vivo!  Nos adaptamos a los tiempos de cuarentena y hacemos radio por streamming. 
        Con nuestros conductores Lucas y Mailen recorremos la actualidad y hacemos intercambios de saber con columnas de e-commerce, deportes, estilo y tendencias, mundo geek y espiritualidad.
      </p>-->
      <hr>
      <!--
      <script>
        var tag = document.createElement('script');

        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        var player;
        function onYouTubeIframeAPIReady() {
          player = new YT.Player('player', {
            height: '720px',
            width: '100%',
            videoId: 'M7lc1UVf-VE',
            events: {
              'onReady': onPlayerReady
            }
          });
        }

        function onPlayerReady(event) {
          event.target.playVideo();
        }

        function stopVideo() {
          player.stopVideo();
        }
      </script>-->
      <!--<iframe width="100%" height="720px" src="https://www.youtube.com/embed/live_stream?channel=UCRZYiq3IklJAXd3_eeWvQPg" frameborder="0" allowfullscreen></iframe>-->
      <iframe class="mi-iframe" width="100%" src="https://www.youtube.com/embed/-5nwgrGOMQE" frameborder="0" allowfullscreen></iframe>
      <!--<iframe width="100%" height="720px" src="https://www.youtube.com/embed/live_stream?channel=UCSJ4gkVC6NrvII8umztf0Ow" frameborder="0" allowfullscreen></iframe>-->
      <hr>
      <!--<span class="text-info logo-medium">Programaci&oacute;n </span>-->
      <!--<br>-->
        <?php 
        $i = 0;
        $j = 0;
        
        foreach ($listaPrograma as $programa) {
            
            $listaHorarios = $horarioDAO->mostrarPorPrograma($programa->getId());
        ?>
        
        <?php if(!empty($listaHorarios)) { 
               echo "<span class='text-info logo-medium'>".$programa->getNombre()."</span>";
        ?>
            
            <table class="table table-striped table-hover text-center">
            <thead>
              <tr>
                <th>
                  D&iacute;a
                </th>
                <th>
                  Horario
                </th>
                <th>
                  -
                </th>
              </tr>
            </thead>
            <tbody>
              <?php 
                foreach ($listaHorarios as $horario) {
                  
              ?>
              <tr>
                <td>
                <?php
                  switch ($horario->getDia()) {
                    case '1':
                      echo "Lunes";
                      break;
                    case '2':
                      echo "Martes";
                      break;
                    case '3':
                      echo "Mi&eacute;rcoles";
                      break;
                    case '4':
                      echo "Jueves";
                      break;
                    case '5':
                      echo "Viernes";
                      break;
                    case '6':
                      echo "S&aacute;bado";
                      break;
                    
                    default:
                      echo "Domingo";
                      break;
                  }
                ?></td>
                <td><?php echo $horario->getHorario(); ?></td>
                <td class="text-info">
                <?php 
                $formato = 'H:i:s';
                $fecha = DateTime::createFromFormat($formato, $horario->getHorario());
                if(strtotime($horario->getHorario()) >=strtotime('05:00:00') &&
                  strtotime($horario->getHorario()) <strtotime('19:00:00')) {
                  echo "<i class='fas fa-sun'></i>";
                } else {
                  echo "<i class='fas fa-moon'></i>";
                }
                ?>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          <?php } ?>
        <?php } ?>
    </div>
    <?php include "../footer.php"; ?>
  </div>
</body>
</html>
