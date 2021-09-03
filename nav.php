<nav class="navbar navbar-expand-md bg-info navbar-dark sticky-top text-center">
    <!-- Para pantallas pequeñas -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Links -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav" style="width: 100%;">
            <div class="zoom" style="width: 33%;">
                <li class="nav-item">
                        <a class="navbar-brand mx-auto" href="/index.php">
                            <img src="/img/tooltip.png" class="rounded-circle" style="width: 32px;" alt="UNLaM Podcast"> <b>UNLaM</b> Podcast
                        </a>
                </li>
            </div>
            <div class="zoom" style="width: 33%;">
                <li class="nav-item">
                <a class="navbar-brand mx-auto" href="/vivo/stream.php">
                    <img src="/img/tooltip.png" class="rounded-circle" style="width: 32px;" alt="UNLaM Podcast"> <b>UNLaM</b> <i>vivo</i>
                </a>
                </li>
            </div>
            <div class="zoom" style="width: 33%;">
                <li class="nav-item">
                <a class="navbar-brand mx-auto" href="/news.php">
                    <img src="/img/tooltip.png" class="rounded-circle" style="width: 32px;" alt="UNLaM Podcast">
                    <b>Extra</b> <i>Data</i>
                </a>
                </li>
            </div>
        </ul>
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
