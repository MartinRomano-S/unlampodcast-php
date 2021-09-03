<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<!doctype html>
<html>
<head>
  <?php include 'header.php'; ?>
</head>
<body>
  <div class="container" style="padding: 0px; background-image: url('../img/bgtest.jpg');">
    <?php include 'navAdmin.php'; ?>
    <div class="card" style="width: 98%; margin: 10px 1% 10px 1%;">
        <div class="card-body">
          <h4 class="card-title"><i class="far fa-newspaper text-info"></i>&nbsp;Novedades disponibles desde el 08/10/2019</h4><hr>
          <b>Al tocar play en un episodio se registra la reproducción del mismo</b>
          <br>
          Esto sucede todas las páginas donde se encuentre el episodio disponible para escuchar
          <hr>
          <b>Nueva página de "Estadísticas" para consultar las reproducciones</b>
          <br>
          Se accede desde el panel de administrador en la opción "Estadísticas". Se muestran las reproducciones por episodio y abajo un total y un promedio de las mismas.
          <br>
          Al haber incorporado el contador a partir de esta actualización el mismo empezará en 0, ya que no tenemos forma de saber las reproducciones que hubo anteriormente :(
          <br><br>
          <div class="table-responsive">
            <table class="table">
              <tr>
                <td>
                  <img src="./news/news4.png" alt="Novedad">
                </td>
              </tr>
            </table>
          </div>
          <hr>
          <!--
          <b>Ya no es necesario indicar en el título del Episodio "Episodio X", ahora se autocompleta</b>
          <br>
          Esto sucede tanto en la página principal como en la página específica de cada podcast
          <br><br>
          <div class="table-responsive">
            <table class="table">
              <tr>
                <td>
                  <img src="./news/news3_1.png" alt="Novedad">
                </td>
                <td>
                  <img src="./news/news3_2.png" alt="Novedad">
                </td>
              </tr>
            </table>
          </div>
          <hr>
          <b>Arreglo de errores menores</b>
          <br>
          <ul>
            <li>Ya no se generan advertencias por consola al ingresar a podcastGen.</li>
            <li>Se registra que usuario inició y cerró sesión con su fecha y hora.</li>
            <li>Eficiencia mejorada.</li>
          </ul>-->
        </div>
    </div>
    <?php include "../footer.php"; ?>
  </div>
</body>
</html>
