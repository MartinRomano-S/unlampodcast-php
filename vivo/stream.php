<?php 
    include "../admin/action.php";
?>
<!doctype html>
<html>
<head>
  <?php include 'head.php'; ?>
  <script>
    $(document).ready(() => {
        $('#authorsTable').load('/admin/authors/reloadAuthorsTable.php');
    });

  </script>
</head>
<body>
  <div id="body" class="container">
    <a href="./stream.php" title="Inicio">
      <img id="portada" src="../img/2.png" alt="UNLaM Podcast">
    </a>
    <?php include "../nav.php"; ?>
    <div id="jumbo-header" class="jumbotron bg-light">
        <span class="text-info logo-medium">UNLaM </span>
        <span class="logo-light"><i>vivo</i></span>
        <table id="authorsTable" class="table table-striped table-hover text-center"></table>
    </div>
    <br>
    <?php include "../footer.php"; ?>
  </div>
</body>
</html>
