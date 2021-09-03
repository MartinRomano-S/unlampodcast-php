function _(el){
  return document.getElementById(el);
}
function uploadFile(){
  var file = _("episodeAudio").files[0];
  //alert(file.name+" | "+file.size+" | "+file.type);
  var formdata = new FormData();
  formdata.append("episodeAudio", file);
  var ajax = new XMLHttpRequest();
  ajax.upload.addEventListener("progress", progressHandler, false);
  ajax.addEventListener("load", completeHandler, false);
  ajax.addEventListener("error", errorHandler, false);;
  ajax.addEventListener("abort", abortHandler, false);
  ajax.open("POST", "./actionEpisode.php");
  ajax.send(formdata);
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
  console.log("ERROR" + JSON.stringify(event));
  $('.progress-bar').append("Subida de archivo fallida");
}
function abortHandler(event){
  $('.progress-bar').append("Interrumpida");
}