function showError(msgError){
  var vectorMsg = msgError.split(";");
  $(".modal-title").empty();
  $(".modal-body").empty();
  $(".modal-footer").empty();
  $(".modal-title").append("Se han detectado los siguientes errores:");
  $(".modal-body").append("<div class='alert alert-danger' role='alert'><ul id='ulModal' name='ulModal'></ul></div>");
  for(i=0;i<vectorMsg.length;i++){
    $("#ulModal").append("<li>" + vectorMsg[i] + "</li>");
  }
  $("#modal").modal('show');
}

function showNoError(msg){
  $(".modal-title").append("UNLaM Podcast:");
  $(".modal-body").append("<div class='alert alert-success' role='alert'>"+ msg + "</div>");
  $("#modal").modal('show');
}

function showConfirm(msg,param){
  $(".modal-title").empty();
  $(".modal-body").empty();
  $(".modal-footer").empty();
  $(".modal-title").append("Aviso");
  $(".modal-body").append(msg);
  $(".modal-footer").append("<button type='button' class='btn btn-danger' data-dismiss='modal'>Cancelar</button>");
  $(".modal-footer").append("<button type='button' class='btn btn-info' onclick='location.href='actionSocialNetwork.php?delete=1&id=" + param + ">Aceptar</button>");
  $("#modal").modal('show');
}