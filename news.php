<?php
    include "./admin/action.php";
    $passEncrypt = "emese345";
?>
<!doctype html>
<html>
<head>
    <?php include 'head.php'; ?>
    <style>
        .text-ellipsis {
            text-overflow: ellipsis; 
            white-space: nowrap; 
            overflow: hidden;
        }
        
        .text-bajada {
            word-wrap: break-word; 
            max-height: 3.6em; 
            line-height: 1.8em; 
            font-size: 18px;
        }
        
        /*.filter div button {
            border-radius: 1.5rem;
        }*/
        
        .no-decor {
            color: black;
            text-decoration: none;
        }
        
        .no-decor:hover {
            color: black;
            text-decoration: none;
            background-color: #f8f9fa;
        }
        
        .no-decor div:hover {
            background-color: #edf0f2 !important;
        }
        
        /*.jumbo-post:hover {
            background-color: rgba(0,0,0,.075);
        }*/
    
    </style>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.es-es.js" type="text/javascript"></script>
</head>
<body>
  <div id="body" class="container">
    <a href="./index.php" title="Inicio">
      <img id="portada" src="./img/p1_low_2.png" alt="UNLaM Podcasts">
    </a>
    <?php include "nav.php"; ?>
    <div class="jumbo-header jumbotron bg-light h-200 pl-0 pr-0">
        <div class="container justify-content-center">
            <div class="row">
                <div class="col text-center">
                    <h2 class="text-info text-left text-ellipsis"><b>Extra Data</b></h2>
                    <hr>
                </div>
            </div>
            <!--<div class="row filter" style='display:none;'>
                <div class="col text-center justify-content-center">
                    <button type="button" class="btn btn-outline-primary">Política</button>
                    <button type="button" class="btn btn-outline-secondary">Deportes</button>
                    <button type="button" class="btn btn-outline-success">Sociedad</button>
                    <button type="button" class="btn btn-outline-danger">Ciencia</button>
                    <button type="button" class="btn btn-outline-warning">Tecnología</button>
                    <button type="button" class="btn btn-outline-info">Economía</button>
                    <button type="button" class="btn btn-outline-dark">Entretenimiento</button>
                </div>
            </div>-->
            <div class="row filter">
                <div class='col'>
                    <label for="title-filter"><b>Título</b></label>
                    <div class="custom-file">
                        <input class='form-control' id="title-filter" name="title-filter" type="text" />
                    </div>
                </div>
                <div class='col'>
                    <label for="date-filter"><b>Fecha de Publicaci&oacute;n (hasta): (mm/dd/aaaa)</b></label>
                    <div class="custom-file">
                        <input id="date-filter" name="date-filter" width="276" type="text" readonly />
                    </div>
                </div>
            </div>
            <div class="row filter mt-2">
                <div class="col form-group">
                    <button type="button" class="btn btn-send search">Buscar</button>
                </div>
            </div>
        </div>
    </div>
    <div id='publishedNewsList'></div>
    <br>
  <?php include "./footer.php"; ?>
  </div>
    <script src="../../js/news-filter.js" type="text/javascript"></script>
</body>
</html>