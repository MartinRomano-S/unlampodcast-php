<nav class="navbar navbar-expand-md bg-info navbar-dark sticky-top text-center">
<!-- Links -->
<div class="zoom" style="width: 50%;">
  <a class="navbar-brand mx-auto" href="../index.php"><img src="/img/tooltip.png" class="rounded-circle" style="width: 32px;" alt="UNLaM Podcast"> <b>UNLaM</b> Podcast
  </a>
</div>
<div class="zoom" style="width: 50%;">
  <a class="navbar-brand mx-auto" href="../vivo/stream.php"><img src="/img/tooltip.png" class="rounded-circle" style="width: 32px;" alt="UNLaM Podcast"> <b>UNLaM</b> <i>vivo</i>
  </a>
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
</nav>
