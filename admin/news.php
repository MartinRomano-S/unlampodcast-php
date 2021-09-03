<?php
  if(!isset($_SESSION['user'])){
      header("Location: ../login.php");
  }
?>
<!doctype html>
<html>
<head>
    <?php include 'header.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.es-es.js" type="text/javascript"></script>
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
</head>
<body>
  <?php include "modal.php" ?>
  <div id="body" class="container">
    <?php include 'navAdmin.php'; ?>
    <div class="card" style="width: 98%; margin: 10px 1% 10px 1%;">
      <div class="card-body">
        <h4 class="card-title">
            <i class="far fa-newspaper text-info"></i>&nbsp;Extra Data
            <button type="button" class="btn btn-info add-news float-right" data-toggle="modal" data-target="#newPost"><i class="fas fa-plus"></i>&nbsp;Nueva</button>
        </h4>
        <form id="finsert" name="finsert" method="post" enctype="multipart/form-data" style="display: none;">
            <input type="hidden" class="form-control" id="isInsert" name="isInsert" value="1">
            <div class="form-group">
                <label for="nombre">T&iacutetulo:</label>
                <input type="text" class="form-control " id="title" name="title">
            </div>
            <div class="form-group">
                <label for="desc">Bajada:</label>
                <textarea class="form-control " rows="4" id="bajada" name="bajada"></textarea>
            </div>
            <div class="form-group">
                <label for="cuerpo">Cuerpo:</label>
                <textarea id="cuerpo" name="cuerpo"></textarea>
            </div>
            <div class="row">
                <div id="newsImageContainer" class="col-sm-6 form-group">
                    <label for="divImg">Im&aacute;gen:</label>
                    <div class="possible-file-extensions">
                        <span class="badge badge-info">png, jpg, jpeg</span>
                    </div>
                    <div id="divImg" class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image">
                        <label class="custom-file-label" for="image" data-browse="Elegir">Seleccionar Archivo</label>
                    </div>
                    <div id="newsImageInformationContainer" style="display:none;" class="form-group">
                        <p id='status1' class="status"></p>
                        <div id="progressImg" class="progress">
                            <div id="progressBarImg" class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div id="newsExistentImage" class="form-group mt-1" style="display:none;">
                        Imágen actual:&nbsp;<a class="badge badge-info text-light" target='_blank' data-toggle="tooltip" data-placement="top" title="Click para ver"></a>
                        <a class='text-danger' style='cursor: pointer;'>
                            <i class="fas fa-trash-alt fa-lg"></i>
                        </a>
                    </div>
                </div>
                <div id="newsAudioContainer" class="col-sm-6 form-group">
                    <label for="divAudio">Audio:</label>
                    <div class="possible-file-extensions">
                        <span class="badge badge-info">mp3, wav</span>
                    </div>
                    <div id="divAudio" class="custom-file">
                        <input type="file" class="custom-file-input " id="audio" name="audio">
                        <label class="custom-file-label" for="audio" data-browse="Elegir">Seleccionar Archivo</label>
                    </div>
                    <div id="newsAudioInformationContainer" style="display:none;" class="form-group">
                        <p id='status2' class="status"></p>
                        <div id="progressAudio" class="progress">
                            <div id="progressBarAudio" class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div id="newsExistentAudio" class="form-group mt-1" style="display:none;">
                        Audio actual:&nbsp;<a class="badge badge-info text-light" target='_blank' data-toggle="tooltip" data-placement="top" title="Click para ver"></a>
                        <a class='text-danger' style='cursor: pointer;'>
                            <i class="fas fa-trash-alt fa-lg"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label for="datepicker">Fecha de Publicaci&oacute;n: (mm/dd/aaaa)</label>
                    <div class="custom-file ">
                        <input id="datepicker" name="datepicker" width="276" type="text" readonly />
                    </div>  
                </div>
                <div class="col-sm-6 form-group">
                    <label class='w-100' for="autores">Autores:
                        <i class="fas fa-question-circle fa-lg float-right text-warning" data-toggle="tooltip" data-placement="top" title="Sólo se permiten seleccionar autores del año actual"></i>
                    </label>
                    <select class="form-control selectpicker w-80" multiple id="autores" name="autores[]" title="Seleccionar autores...">
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <div id="draftOptions">
                        <button type="button" class="btn btn-info save-draft">Guardar borrador</button>
                        <button type="button" class="btn btn-danger delete-draft"><i class="fas fa-trash-alt"></i>&nbsp;Descartar</button>
                    </div>
                    <button type="button" class="btn btn-send de-publish"><i class="fas fa-file"></i>&nbsp;Mover a borrador</button>
                </div>
                <div class="col-sm-6 form-group text-right">
                    <button type="button" class="btn text-light cancel"><i class="fas fa-arrow-left"></i>&nbsp;Atrás</button>
                    <button type="button" class="btn btn-send publish"><i class="fas fa-paper-plane"></i>&nbsp;Publicar</button>
                    <button type="button" class="btn btn-info save-publish"><i class="fas fa-save"></i>&nbsp;Guardar cambios</button>
                </div>
            </div>
        </form>
        <div class="table-responsive mt-3">
            <table id="newsTable" class='table table-striped table-hover' style='width: 100%;'></table>
        </div>
       </div>
    </div>
    <!-- Modal crear noticia -->
    <div class="modal fade" id="newPost" tabindex="-1" role="dialog" aria-labelledby="newPostLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="newPostLabel"><i class="far fa-newspaper text-info"></i>&nbsp;Nueva noticia</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info" role="alert">
              <b>Nota: </b>al tocar en "Crear" se creará un borrador de la noticia y podrá editarla.
            </div>
            <form id="newPostForm" method="post">
                <div class="form-group">
                    <label for="title">T&iacutetulo:</label>
                    <input type="text" class="form-control " id="postTitle" name="postTitle">
                    <div class="text-danger" style="display: none;">
                        El título de la noticia no puede quedar vacío.
                    </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info add">Crear</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal errores -->
    <div class="modal fade" tabindex="-1" role="dialog" id="statusModal" name="statusModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
        </div>
      </div>
    </div>
  </div>
    <?php include "../footer.php"; ?>
  </div>
    <script src="../../js/news.js" type="text/javascript"></script>
</body>
</html>
