(function($) {

    $(function() {
        var $fileInput = $('#file-input');
        var $dropBox = $('#drop-box');
        var $uploadForm = $('#upload-form');
        var $uploadRows = $('#upload-rows');
        var $clearBtn = $('#clear-btn');
        var $sendBtn = $('#send-btn');
        var $previewsChecker = $('#previews-checker');
        var previewsOn = true;

        ///// Uploader init
        $fileInput.damnUploader({
            // URL of server-side upload handler
            url: '/admin/addPage',
            // File POST field name (for ex., it will be used as key in $_FILES array, if you using PHP)
            fieldName:  'my-file',
            // Container for handling drag&drops (not required)
            dropBox: $dropBox,
            // Limiting queued files count (if not defined [or false] - queue will be unlimited)
            limit: 10,
            // Expected response type ('text' or 'json')
            dataType: 'json'
        });


        ///// Misc funcs

        var isTextFile = function(file) {
            return file.type == 'text/plain';
        };

        var isImgFile = function(file) {
            return file.type.match(/image.*/);
        };

        // Creates queue table row with file information and upload status
        var createRowFromUploadItem = function(ui) {
            var $row = $('<tr/>').prependTo($uploadRows);
            var $progressBar = $('<div/>').addClass('progress-bar').css('width', '0%');
            var $pbWrapper = $('<div/>').addClass('progress').append($progressBar);

            // Defining cancel button & its handler
            var $cancelBtn = $('<a/>').attr('href', 'javascript:').append(
                $('<span/>').addClass('glyphicon glyphicon-remove')
            ).on('click', function() {
                var $statusCell =  $pbWrapper.parent();
                $statusCell.empty().html('<i>cancelled</i>');
                ui.cancel();
                log((ui.file.name || "[custom-data]") + " canceled");
            });

            // Generating preview
            var $preview;
            if (previewsOn) {
                if (isImgFile(ui.file)) {
                    // image preview (note: might work slow with large images)
                    $preview = $('<img/>').attr('width', 120);
                    ui.readAs('DataURL', function(e) {
                        $preview.attr('src', e.target.result);
                    });
                } else {
                    // plain text preview
                    $preview = $('<i/>');
                    ui.readAs('Text', function(e) {
                        $preview.text(e.target.result.substr(0, 15) + '...');
                    });
                }
            } else {
                $preview = $('<i>no preview</i>');
            }

            var drop = '<option selected disabled>Выберете...</option>';
            all_albums.forEach(function(item){drop += '<option value="'+item.id+'">'+item.name+'</option>'});

            // Appending cells to row
            $('<td/>').append($preview).appendTo($row); // Preview
            $('<td/>').text(ui.file.name).appendTo($row); // Filename
            $('<td/>').text(Math.round(ui.file.size / 1024) + ' KB').appendTo($row); // Size in KB
            $('<td/>').html('<input id="photo_title_'+ui._id+'" class="form-control" name="title" placeholder="Title">').appendTo($row);
            $('<td/>').html('<input id="photo_desc_'+ui._id+'" class="form-control" name="description" placeholder="Description">').appendTo($row);
            $('<td/>').html('<select id="photo_album_'+ui._id+'" class="form-control" name="id_album">'+drop+'</select>').appendTo($row);
            ui.statusRow = $('<td/>').append($pbWrapper).appendTo($row); // Status
            $('<td/>').append($cancelBtn).appendTo($row); // Cancel button

            
            ui.addPostData('photo_title', "");
            ui.addPostData('photo_desc', "");
            ui.addPostData('photo_album', "");

            $('#photo_title_'+ui._id).change(function() {
                ui._post[0].value = $(this).val();
            });
            $('#photo_desc_'+ui._id).change(function() {
                ui._post[1].value = $(this).val();
            });
            $('#photo_album_'+ui._id).change(function() {
                ui._post[2].value = $(this).val();
            });

            return $progressBar;
        };

        // File adding handler       функция отображения картинок в таблице
        var fileAddHandler = function(e) {
            // e.uploadItem represents uploader task as special object,
            // that allows us to define complete & progress callbacks as well as some another parameters
            // for every single upload
            var ui = e.uploadItem;
            var filename = ui.file.name || ""; // Filename property may be absent when adding custom data
            
            // We can call preventDefault() method of event to cancel adding
            if (!isImgFile(ui.file)) {
                notify(filename + ": не картинка.", "danger");
                e.preventDefault();
                return ;
            }

            if((ui.file.size / 1024) > 10000) {
                notify(filename + ": очень большой размер.", "danger");
                e.preventDefault();
                return ;
            }

            // We can replace original filename if needed
            if (!filename.length) {
                ui.replaceName = "custom-data";
            } else if (filename.length > 14) {
                ui.replaceName = filename.substr(0, 10) + "_" + filename.substr(filename.lastIndexOf('.'));
            }
            // We can add some data to POST in upload request

            //ui.addPostData($uploadForm.serializeArray()); // from array
            //ui.addPostData('original-filename', filename); // .. or as field/value pair

            // Show info and response when upload completed
            var $progressBar = createRowFromUploadItem(ui);
            ui.completeCallback = function(success, data, errorCode) {
                log('******');
                log((this.file.name || "[custom-data]") + " completed");
                if (success) {
                    log('recieved data:', data);
                } else {
                    log('uploading failed. Response code is:', errorCode);
                }
            };

            // Updating progress bar value in progress callback
            ui.progressCallback = function(percent) {
                $progressBar.css('width', Math.round(percent) + '%');
            };
        };


        ///// Setting up events handlers

        // Uploader events    после выбора картинок назначили событиям обработчики
        $fileInput.on({
            'du.add' : fileAddHandler,

            'du.limit' : function() {
                log("File upload limit exceeded!");
            },

            'du.completed' : function() {
                log('******');
                log("All uploads completed!");
            }
        });

        // Clear button
        $clearBtn.on('click', function() {
            $fileInput.duCancelAll();
            $uploadRows.empty();
            log('******');
            log("All uploads canceled :(");
        });

        // Previews generating switcher
        $previewsChecker.on('change', function() {
            previewsOn = $previewsChecker.prop('checked');
        });

        // Form submit
        $uploadForm.on('submit', function(e) {
            // Sending files by HTML5 File API if available, else - form will be submitted on fallback handler
            if ($.support.fileSending) {
                e.preventDefault(); // функция для отмены действия браузера (действие при клике на кнопке submit в форме)
                $fileInput.duStart();
            }
        });

    });

})(window.jQuery);


// File API support info
if(!$.support.fileSelecting) {
    log("[-] Your browser doesn't support File API (uploads may be performed only by default form submitting)");
} else {
    log("[√] Your browser supports multiple file selecting" + ($.support.fileSending ? " and sending" : ""));
    if(!$.support.fileReading) {
        log("[-] Your browser doesn't support file reading on client side");
    }
    if(!$.support.uploadControl) {
        log("[-] Your browser can't retrieve upload progress information (progress bars will be disabled)");
    }
    if(!$.support.fileSending) {
        log("[-] Your browser doesn't support FormData object (files will be send by default form submitting)");
    }
    log("Now select some files to see what happen ...");
}
