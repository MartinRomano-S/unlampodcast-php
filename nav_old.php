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
    $sql = "SELECT * FROM pu_podcasts ORDER BY FEC_CREACION DESC";
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
    <!--<li class="nav-item" style="width: <?php echo $width; ?>%; text-align: center; padding-top: auto; padding-right: auto;">-->
    <li class="nav-item" style="text-align: center; padding-top: auto; padding-right: auto;">
      <div class="zoom">
        <a class="nav-link active" href="./podcastGen.php?id=<?php echo rawurlencode(openssl_encrypt($row["ID_PODCAST"],"AES-128-ECB",$passEncrypt)) ?>"><?php echo "#".str_replace(" ", "",$row["NOMBRE"]); ?></a>
      </div>
    </li>
  <?php } ?>
  </ul>
</div>
</nav>
