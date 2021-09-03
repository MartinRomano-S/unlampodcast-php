function _(el){
  return document.getElementById(el);
}
function uploadFile(){
  var file = _("episodeAudio").files[0];
  alert(file.name+" | "+file.size+" | "+file.type);
  var formdata = new FormData();
  formdata.append("episodeAudio", file);
  var ajax = new XMLHttpRequest();
  //alert("XMLHttpRequest");
  ajax.upload.addEventListener("progress", progressHandler, false);
  //alert("progress");
  ajax.addEventListener("load", completeHandler, false);
  //alert("load");
  ajax.addEventListener("error", errorHandler, false);
  //alert("error");
  ajax.addEventListener("abort", abortHandler, false);
 // alert("abort");
  ajax.open("POST", "../admin/actionEpisode.php");
  //alert("POST");
  ajax.send(formdata);
 // alert("send");
}
function progressHandler(event){
  var percent = Math.round((event.loaded / event.total) * 100);
    
  $('.progress-bar').css("width",percent + "%");
  $('.progress-bar').html(percent + "%");
}
function completeHandler(event){
  _("progressBar").value = 0;
}
function errorHandler(event){
  console.log("AJAX error in request: " + JSON.stringify(event, ["message", "arguments", "type", "name"]));
  $('.progress-bar').append("Subida de archivo fallida");
}
function abortHandler(event){
  $('.progress-bar').append("Interrumpida");
}