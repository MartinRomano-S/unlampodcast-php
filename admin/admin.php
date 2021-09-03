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
  <div id="body" class="container">
    <?php include 'navAdmin.php'; ?>
    <?php include 'updates.php'; ?>
      <div class='card-deck'>
      <?php 
      if($_SESSION['type']==1){
      ?>
        <div class="card bg-white in-left">
          <div class="card-body text-center">
            <h4 class="card-title"><i class="fas fa-user-friends text-info"></i>&nbsp;Usuarios</h4><hr>
            <p>Gestión de Usuarios.<br/>Super-Usuarios, usuarios bloqueados y contraseñas.</p><hr>
            <p class="text-info">
              <a class="a-info" href="../manuals/Gestion de Usuarios-v1.0.pdf" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;Abrir&nbsp;&nbsp;</a>
              <a class="a-info" href="../manuals/Gestion de Usuarios-v1.0.pdf" download><i class="fas fa-download"></i>&nbsp;Descargar</a>
            </p>
          </div>
        </div>
      <?php } ?>
        <div class="card bg-white in-left">
          <div class="card-body text-center">
            <h4 class="card-title"><i class="fas fa-microphone-alt text-info"></i>&nbsp;Podcasts</h4><hr>
            <p>Gestión de Podcasts.<br>Temas, Autores, Usuarios, Carátulas y Portadas.</p><hr>
            <p class="text-info">
              <a class="a-info" href="../manuals/Gestion de Podcasts-v1.0.pdf" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;Abrir&nbsp;&nbsp;</a>
              <a class="a-info" href="../manuals/Gestion de Podcasts-v1.0.pdf" download><i class="fas fa-download"></i>&nbsp;Descargar</a>
            </p>
          </div>
        </div>
        <div class="card bg-white in-left">
          <div class="card-body text-center">
            <h4 class="card-title"><i class="fas fa-headphones text-info"></i>&nbsp;Episodios</h4><hr>
            <p>Gestión de Episodios.<br/>Extensiones permitidas, tamaño máximo, fecha de publicación y últimos Episodios.</p><hr>
            <p class="text-info">
              <a class="a-info" href="../manuals/Gestion de Episodios-v1.0.pdf" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;Abrir&nbsp;&nbsp;</a>
              <a class="a-info" href="../manuals/Gestion de Episodios-v1.0.pdf" download><i class="fas fa-download"></i>&nbsp;Descargar</a>
            </p>
          </div>
        </div>
      <?php 
        if($_SESSION['type']==1){
      ?>
      </div>
      <div class='card-deck'>
      <?php } ?>
        <div class="card bg-white in-left">
          <div class="card-body text-center">
            <h4 class="card-title"><i class="fas fa-tags text-info"></i>&nbsp;Temas</h4><hr>
            <p>Gestión de Temas.</p><hr>
            <p class="text-info">
              <a class="a-info" href="../manuals/Gestion de Temas-v1.0.pdf" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;Abrir&nbsp;&nbsp;</a>
              <a class="a-info" href="../manuals/Gestion de Temas-v1.0.pdf" download><i class="fas fa-download"></i>&nbsp;Descargar</a>
            </p>
          </div>
        </div>
      <?php 
        if($_SESSION['type']==0){
      ?>
      </div>
      <div class='card-deck'>
      <?php } ?>
        <div class="card bg-white in-left">
          <div class="card-body text-center">
            <h4 class="card-title"><i class="fas fa-user-edit text-info"></i>&nbsp;Autores</h4><hr>
            <p>Gestión de Autores.</p><hr>
            <p class="text-info">
                <a class="a-info" href="../manuals/Gestion de Autores-v1.0.pdf" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;Abrir&nbsp;&nbsp;</a>
                <a class="a-info" href="../manuals/Gestion de Autores-v1.0.pdf" download><i class="fas fa-download"></i>&nbsp;Descargar</a>
              </p>
          </div>
        </div>
        <div class="card bg-white in-left">
          <div class="card-body text-center">
            <h4 class="card-title"><i class="fas fa-project-diagram text-info"></i>&nbsp;Redes Sociales</h4><hr>
            <p>Gestión de Redes Sociales.</p><hr>
            <p class="text-info">
              <a class="a-info" href="../manuals/Gestion de Redes Sociales-v1.0.pdf" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;Abrir&nbsp;&nbsp;</a>
              <a class="a-info" href="../manuals/Gestion de Redes Sociales-v1.0.pdf" download><i class="fas fa-download"></i>&nbsp;Descargar</a>
            </p>
          </div>
        </div>
      </div>
      <div class='card-deck'>
          <div class="card bg-white in-left">
              <div class="card-body text-center">
                <h4 class="card-title"><i class="fas fa-newspaper text-info"></i>&nbsp;Extra Data</h4><hr>
                <p>Gestión de Extra Data.</p><hr>
                <p class="text-info">
                  <a class="a-info" href="../manuals/Gestion de Extra Data-v1.0.pdf" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;Abrir&nbsp;&nbsp;</a>
                  <a class="a-info" href="../manuals/Gestion de Extra Data-v1.0.pdf" download><i class="fas fa-download"></i>&nbsp;Descargar</a>
                </p>
              </div>
            </div>
          <div class="card bg-white">
            <div class="card-body text-center">
              <h4 class="card-title"><i class="fas fa-book text-info"></i>&nbsp;Manual Completo</h4><hr>
              <p><br/>Manual completo, posee todas las guías individuales juntas en un solo archivo.<br/><br/><br/></p><hr>
              <p class="text-info">
                  <?php 
                    if($_SESSION['type']==1){
                  ?>
                  <a class="a-info" href="../manuals/Manual Completo-vs1.0.pdf" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;Abrir&nbsp;&nbsp;</a>
                  <a class="a-info" href="../manuals/Manual Completo-vs1.0.pdf" download><i class="fas fa-download"></i>&nbsp;Descargar</a>
                  <?php } else { ?>
                  <a class="a-info" href="../manuals/Manual Completo-v1.0.pdf" target="_blank"><i class="fas fa-external-link-alt"></i>&nbsp;Abrir&nbsp;&nbsp;</a>
                  <a class="a-info" href="../manuals/Manual Completo-v1.0.pdf" download><i class="fas fa-download"></i>&nbsp;Descargar</a>
                  <?php } ?>
                </p>
            </div>
          </div>
          <div class="card bg-white">
            <div class="card-body text-justify">
              <h4 class="card-title text-center"><i class="fas fa-heart text-info"></i>&nbsp;Agradecimiento</h4><hr>
              <p>Queríamos agradecerles, en este pequeño párrafo, a todos aquellos que confiaron en nosotros para la realización de este sistema. Les deseamos de corazón muchos éxitos en todo lo que realicen y sepan que este desarrollo nos permitió adentrarnos en nuevas tecnologías que nos dejaron muchas enseñanzas para nuestra carrera profesional. Les agradecemos nuevamente y sepan que UNLaM Podcast será siempre parte de nosotros.</p><hr>
              <p class="text-info text-center">
                Martín Pablo Romano & Julián Andrés Sanchez
              </p>
            </div>
          </div>
      </div>
      <br/>
    <?php include "../footer.php"; ?>
  </div>
</body>
</html>
