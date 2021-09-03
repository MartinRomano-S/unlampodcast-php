<?php
    include "./admin/action.php";
    $passEncrypt = "emese345";
?>
<!doctype html>
<html>
<head>
    <?php include 'head.php'; ?>
</head>
<body>
  <div id="body" class="container">
    <a href="./index.php" title="Inicio">
      <img id="portada" src="./img/p1_low_2.png" alt="UNLaM Podcasts">
    </a>
    <?php include "nav.php"; ?>
    <div id='publishedNews'></div>
    <br>
  <?php include "./footer.php"; ?>
  </div>
    <script type="text/javascript">
        $(document).ready(() => {
            $('#publishedNews').load('/admin/news/getPublicNews.php', <?php echo "{ n : ".(isset($_GET['n']) ? $_GET['n'] : -1)."}" ?>);
        });
        
        function copyToClipboard(element) {
            let $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }
    </script>
</body>
</html>