$(document).ready(function() {
    
    $('[data-toggle="tooltip"]').tooltip();
    $('.fa-question-circle').tooltip();
    
    $('#datepicker').datepicker({
        locale: 'es-es',
        uiLibrary: 'bootstrap4'
    });
    
    reloadNewsTable();
    
    $('#cuerpo').summernote({
        height: 300,
        minHeight: null,
        maxHeight: null,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol']]
        ]
    });
    
    //get author list
    $.get('/admin/authors/get.php', function(data) {
        
        console.log(data);
        
        if(data) {
            data = JSON.parse(data);
            for(let i=0; i < data.length; i ++)
                $('#autores').append(`<option value='${data[i].id}'>${data[i].apellido}, ${data[i].nombre}</option>`);
        }
    });
    
    $('#image').change(() => {
        uploadFile($('#newsImageContainer'), $('#newsImageInformationContainer'), 'img', $('#title').data('id'));
    });
    
    $('#audio').change(() => {
        uploadFile($('#newsAudioContainer'), $('#newsAudioInformationContainer'), 'audio', $('#title').data('id'));
    });
    
    $('.cancel').off('click').click(() => {
        toggleEditNewsContainer('');
    });
    
    $('.delete-draft').off('click').click(() => {
        confirmDelete(undefined, true); 
    });
    
    $('.save-draft').off('click').click(() => {
        saveNews(true);
    });
    
    $('.de-publish').off('click').click(() => {
        $.confirm({
    		title: '',
    		content: `¿Está seguro que desea mover esta noticia publicada a borrador?<br><br>
    		            <div class="alert alert-info" role="alert">La noticia ya no estará visible para el público.
    		            </div>`,
    		columnClass: 'small',
    		buttons: {
    			Aceptar: {
    				btnClass: 'btn-info',
    				action: () => {
                        saveNews(true);
    				}
    			},
    			Cancelar: {
    				btnClass: 'btn-danger'
    			}
    		}
    	});
    });
    
    $('.save-publish').off('click').click(() => {
        saveNews(false);
    });
    
    $('.publish').off('click').click(() => {
        
        $.confirm({
    		title: '',
    		content: `¿Está seguro que desea publicar esta noticia?<br><br> 
    		            <div class="alert alert-info" role="alert">La noticia estará visible para el público a partir de la fecha de publicación. 
    		            Si no hay una fecha especificada, la noticia estará visible a partir de ahora.
    		            </div>`,
    		columnClass: 'small',
    		buttons: {
    			Aceptar: {
    				btnClass: 'btn-info',
    				action: () => {
                        saveNews(false);
    				}
    			},
    			Cancelar: {
    				btnClass: 'btn-danger'
    			}
    		}
    	});
    });
    
    $('.add-news').click(() => {
       $('#postTitle').val('');
    });
    
    $('.add', $('#newPost')).off('click').click(() => {
        let container = $('#newPost');
        let title = $("#postTitle", container).val();
        $('.text-danger', container).hide();

        if(title === "" || title === null){
            $('.text-danger', container).show();
            return;
        }
        
        $.ajax({
            url: '/admin/news/create.php',
            type: 'POST',
            data: {
                titulo : title
            },
            success: (data) => {
                console.log(data);
                data = JSON.parse(data);
                if(data.success) {
                    showNoError(data.general_message);
                    cleanEditNewsContainer();
                    toggleEditNewsContainer(title, data.id);
                    reloadNewsTable();
                }
            }
        });
    });
});

function toggleEditNewsContainer(title, newsId, row) {
    
    let newsForm = $('#finsert');
    let titleContainer = $('#title', newsForm);

    $('.de-publish').hide();
    $('.save-publish').hide();
    $('.status').hide();
    
    if(title !== undefined && title !== '') {
        $('.table-responsive').fadeOut();
        $('#newPost').modal('hide');
        titleContainer.val(title);
        titleContainer.data('id', newsId);
        titleContainer.data('newsData', undefined);
        $('.add-news').hide();
        $('#finsert').fadeIn();
    } else {
        $('#finsert').fadeOut();
        $('#postTitle').val('');
        titleContainer.val('');
        
        if(row) {
            let newsData = row.data('newsData');
            if(newsData) {
                titleContainer.data('id', row.data('id'));
                titleContainer.val(newsData.titulo);
                $('#bajada', newsForm).val(newsData.bajada);
                $('#cuerpo').summernote('code', newsData.cuerpo);

                let baseURL = 'http://unlampodcast.com/news';
                let currentImgContainer = $('.badge-info', '#newsExistentImage');
                let currentAudioContainer = $('.badge-info', '#newsExistentAudio');
                
                if(newsData.imgPath)
                    showCurrentFileContainer('#newsExistentImage', 'img', newsData.imgPath);
                else
                    hideCurrentFileContainer('#newsExistentImage');
                
                if(newsData.audioPath)
                    showCurrentFileContainer('#newsExistentAudio', 'audio', newsData.audioPath);
                else
                    hideCurrentFileContainer('#newsExistentAudio');
                
                $('#datepicker').datepicker().value(newsData.pubDate);

                let autores = newsData.autores;
                
                if(autores) {
                        $('#autores').selectpicker('val', autores);
                    //for(let j = 0; j < autores.length; j ++)
                        //$('#autores option[value=' + autores[j] + ']').attr('selected','selected');
                }
                
                let isBorrador = newsData.borrador;
                
                if(isBorrador) {
                    $('#draftOptions').show();
                    $('.de-publish').hide();
                    $('.publish').show();
                    $('.save-publish').hide();
                } else {
                    $('#draftOptions').hide();
                    $('.de-publish').show();
                    $('.publish').hide();
                    $('.save-publish').show();
                }
                    
                $('.table-responsive').fadeOut();
                $('#newPost').modal('hide');
                $('.add-news').hide();
                $('#finsert').fadeIn();
            }
        } else {
            titleContainer.data('id', null);
            
            $('.add-news').show();
            $('.table-responsive').fadeIn();
        }
    }
}

function uploadFile(container, infoContainer, type, extraData) {
    let fd = new FormData();
    let fileInput = $('.custom-file-input', container);
    let attachedFile = fileInput[0].files;
    $('.custom-file-label', container).html(fileInput.val().split('\\').pop());
    
    hideCurrentFileContainer(type == 'audio' ? '#newsExistentAudio' : '#newsExistentImage');
    
    if(attachedFile.length > 0 ) {
        let infoDiv = $(infoContainer, container);
        fd.append('file', attachedFile[0]);
        fd.append('extraData', extraData);
        fd.append('type', type);
        
        let url = '/admin/news/uploadNewsImage.php';
        
        if(type == 'audio')
            url = '/admin/news/uploadNewsAudio.php';
    
        $.ajax({
            url: url,
            type: 'POST',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: () => {
                infoDiv.show();
                $('.status', infoDiv).html('<b>Subiendo archivo... por favor espere.</b>');
                $('.status').show();
                $('.progress-bar', infoDiv).css('width', '0%').attr('aria-valuenow', 0).attr('aria-valuemax', 0); 
                $('.progress', infoDiv).show();
            },
            xhr: () => {
                let myXhr = $.ajaxSettings.xhr();
                
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', (e) => {
                        if (e.lengthComputable)
                            $('.progress-bar', infoDiv).css('width', ((e.loaded / e.total)*100)+'%').attr('aria-valuenow', e.loaded).attr('aria-valuemax', e.total);
                    }, false);
                }
                return myXhr;
            },
            success: (data) => {
                
                let response = JSON.parse(data);

                if(response.success) {
                    $('.status', infoDiv).html(`<i class="fas fa-check-circle text-info"></i><b>&nbsp;${response.general_message}</b>`);
                    showCurrentFileContainer(type == 'audio' ? '#newsExistentAudio' : '#newsExistentImage', type, response.destination_path);
                }
                else
                    $('.status', infoDiv).html(`<i class="fas fa-exclamation-circle text-danger"></i><b>&nbsp;${response.general_message}</b>`);
                    
                $('.status').show();
            },
            error: () => {
                $('.status', infoDiv).html('<b>No se pudo subir el archivo.&nbsp;<i class="fas fa-exclamation-circle text-danger"></i></b>');
                $('.status').show();
            },
            complete: () => {
                $('.progress', infoDiv).hide();
            }
        });
    }
}

function saveNews(isDraft) {
    let data = {};
    let newsForm = $('#finsert');
    let titleContainer = $('#title', newsForm);
        
    data.id = titleContainer.data('id');
    data.newsData = {
        titulo : titleContainer.val(),
        bajada : $('#bajada', newsForm).val(),
        cuerpo : $('#cuerpo').summernote('code'),
        datepicker : $('#datepicker').val(),
        borrador : isDraft,
        autores : $('#autores').val()
    };
    
    $.ajax({
        url: '/admin/news/update.php',
        type: 'POST',
        data: data,
        success: (resp) => {
            console.log(resp);
            resp = JSON.parse(resp);
            if(resp.success) {
                showNoError(resp.general_message);
                cleanEditNewsContainer();
                toggleEditNewsContainer('');
                reloadNewsTable();
            } else {
                showError(resp.general_message);
            }
        }
    });
}

function cleanEditNewsContainer() {
    let newsForm = $('#finsert');
    let titleContainer = $('#title', newsForm);
    titleContainer.data('id', '');
    titleContainer.val('');
    $('#bajada', newsForm).val('');
    $('#cuerpo').summernote('code', '');
    $('#image', newsForm).val('');
    $('#audio', newsForm).val('');
    $('#datepicker').datepicker().value(null);
    $('#autores', newsForm).val('');
}

function showNoError(msg){
    let statusModal = $('#statusModal');
    $(".modal-title", statusModal).empty();
    $(".modal-body", statusModal).empty();
    $(".modal-title", statusModal).append("UNLaM Podcast:");
    $(".modal-body", statusModal).append("<div class='alert alert-success' role='alert'>"+ msg + "</div>");
    statusModal.modal('show');
}

function showError(msgError) {
    let vectorMsg = msgError.split(";");
    let errorModal = $('#statusModal');
    $(".modal-title", errorModal).empty();
    $(".modal-body", errorModal).empty();
    $(".modal-footer", errorModal).empty();
    $(".modal-title", errorModal).append("Se han detectado los siguientes errores:");
    $(".modal-body", errorModal).append("<div class='alert alert-danger' role='alert'><ul id='ulModal' name='ulModal'></ul></div>");
    for(i=0;i<vectorMsg.length;i++)
        $("#ulModal", errorModal).append("<li>" + vectorMsg[i] + "</li>");
    errorModal.modal('show');
}

function confirmDelete(pos, isDraft){
    
    let row, isBorrador;
    let id;
    
    if(!isDraft) {
        row = $('#newsTableReg' + pos);
        isBorrador = $(row).data('borrador');
        id = $(row).data('id');
    } else {
        isBorrador = true;
        id = $('#title').data('id');
    }
    
    if(id === undefined) return;
    
  	$.confirm({
		title: '',
		content: `¿Está seguro que desea eliminar ${isBorrador ? 'este borrador' : 'esta noticia publicada'}?`,
		columnClass: 'small',
		buttons: {
			Aceptar: {
				btnClass: 'btn-info',
				action: () => {
				    $.ajax({
                        url: '/admin/news/delete.php',
                        type: 'POST',
                        data: {
                            id : id
                        },
                        success: (data) => {
                            console.log(data);
                            data = JSON.parse(data);
                            if(data.success) {
                                reloadNewsTable();
                                showNoError(data.general_message);
                                
                                if(isDraft)
                                    toggleEditNewsContainer('');
                            } else {
                                showError(data.general_message);
                            }
                        }
                    });
				}
			},
			Cancelar: {
				btnClass: 'btn-danger'
			}
		}
	});
}

function deleteAttachedFile(type) {
    
    let container =  $('#newsExistentAudio');
    
    if(type === 'img')
        container = $('#newsExistentImage');
    
    $.confirm({
		title: '',
		content: `¿Está seguro que desea eliminar este archivo?`,
		columnClass: 'small',
		buttons: {
			Aceptar: {
				btnClass: 'btn-info',
				action: () => {
                    $.ajax({
                        url: '/admin/news/deleteFile.php',
                        type: 'POST',
                        data: {
                            id : $('#title', '#finsert').data('id'),
                            type : type
                        },
                        success: (data) => {
                            data = JSON.parse(data);
                            
                            if(data.success) {
                                $('.badge-info', container).text('');
                                $('.badge-info', container).attr("href", '');
                                $('.status').hide();
                                container.hide();
                                showNoError(data.general_message);
                            }
                            else
                                showError(data.general_message);
                        }
                    });
				}
			},
			Cancelar: {
				btnClass: 'btn-danger'
			}
		}
	});
}

function showCurrentFileContainer(container, type, path) {
    let baseURL = 'http://unlampodcast.com/news';
    let currentFile = $('.badge-info', container);
    
    currentFile.text('imagen_' + path);
    currentFile.attr("href", baseURL + "/" + type + "/" + path);
    
    $('.text-danger', container).off('click').click(() => {
        deleteAttachedFile(type);
    });
    
    $(container).show();
}

function hideCurrentFileContainer(container) {
    let currentFile = $('.badge-info', container);
    
    $('.status').hide();
    currentFile.text('');
    currentFile.attr("href", '');
    $(container).hide();
}


function reloadNewsTable() {
    $('#newsTable').load('/admin/news/reloadTable.php');
}

