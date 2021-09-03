<?php
include("loginserv.php");
?>
<!doctype html>
<html>
<head>
	<?php include 'head.php'; ?>
</head>
<body class="text-center">
	<br/><br/><br/><br/>
	<div class="container" >
		<div class="card" style="max-width: 800px; margin: auto;">
			<div class="card-header">
				<a class="zoomManuals" href="./index.php" title="Inicio">
					<div class="zoom">
	      		<img src="./img/tooltip.png" alt="UNLaM Podcast" style="height: 64px;"><h2 class="text-info">UNLaM Podcast</h2>
	      	</div>
	    	</a>
			</div>
			<h3 class="card-title"><br/>Inicie Sesi&oacute;n para continuar...</h3>
			<div class="card-body">
			<form action="" method="post">
			  <div class="input-group">
			    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
			    <input class="form-control" type="text" id="user" name="user" placeholder="Usuario">
			  </div>
			  <br/>
			  <div class="input-group">
			    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
			    <input class="form-control" type="password" id="pass" name="pass" placeholder="Contraseña">
			  </div>
			</div>
			<div class="card-footer">
			  <input class="btn btn-info" type="submit" value="Acceder" name="submit"><br><br>
			</form>
			<span><?php echo $error; ?></span>
			</div>
		</div>
	</div>
	<br/><br/><br/>
</body>
</html>