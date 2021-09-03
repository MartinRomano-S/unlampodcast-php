<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<a href="./admin.php" title="Inicio">
      <img id="portada" src="../img/p1_low_2.png" alt="UNLaM Podcasts">
    </a>
<nav class="navbar navbar-expand-md bg-info navbar-dark sticky-top">
  <!-- Para pantallas pequeñas -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="zoom">
    <a class="navbar-brand" href="./admin.php"><img src="../img/tooltip.png" class="rounded-circle" style="width: 32px;" alt="UNLaM Podcasts"></a>
  </div>
  <!-- Links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav" style="width: 100%;">
      <?php 
      $widthItem = 16;

      if($_SESSION['type']==0){
        $widthItem = 20;
      } else {
      ?>
      <div class="zoom" style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <li class="nav-item">
          <a class="navbar-brand" href="./users.php">Usuarios</a>
        </li>
      </div>
    <?php } ?>
      <div class="zoom" style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <li class="nav-item">
          <a class="navbar-brand" href="./podcasts.php">Podcasts</a>
        </li>
      </div>
      <!--
      <div class="zoom" style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <li class="nav-item">
          <a class="navbar-brand" href="./topics.php">Temas</a>
        </li>
      </div>
     
      <div class="zoom" style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <li class="nav-item">
          <a class="navbar-brand" href="./authors.php">Autores</a>
        </li>
      </div>-->
      <div class="zoom" style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <li class="nav-item" >
          <a class="navbar-brand" href="./episodes.php">Episodios</a>
        </li>
      </div>
      <!--
      <div class="zoom" style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <li class="nav-item">
          <a class="navbar-brand" href="./socialNetworks.php">Redes Sociales</a>
        </li>
      </div>
      -->
      <div style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <div class="dropdown">
          <span class="navbar-brand dropdown-toggle zoom" style="cursor: pointer;" data-toggle="dropdown">
            Info. del Podcast
          </span>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="./topics.php">Temas</a>
            <a class="dropdown-item" href="./authors.php">Autores</a>
            <a class="dropdown-item" href="./socialNetworks.php">Redes Sociales</a>
          </div>
        </div>
      </div>
      <div class="zoom" style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <li class="nav-item" >
          <a class="navbar-brand" href="./news.php">Extra Data</a>
        </li>
      </div>
      <div class="zoom" style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <li class="nav-item" >
          <a class="navbar-brand" href="./graphics.php">Estad&iacute;sticas</a>
        </li>
      </div>
      <!--
      <div style="width: <?php echo $widthItem; ?>%; text-align: center;">
        <div class="dropdown">
          <span class="navbar-brand dropdown-toggle zoom" data-toggle="dropdown">
            Radio
          </span>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="./programs.php">Programas</a>
            <a class="dropdown-item" href="./horarios.php">Horarios</a>
          </div>
        </div>
      </div>
      -->
    </ul>
  </div>
  <div class="zoom">
    <a class="navbar-brand" href="../logout.php" style="font-size: 20px;"><i class="fas fa-sign-out-alt"></i></a>
  </div>
</nav>