"use strict";
var max_file_size = 10 * 1024 * 1024; //10M
var max_file_uploads = 5;

(function($){
    $(document).ready(function() {
        var options = { 
            dataType: 'JSON',
            beforeSend: function() { 
                $("#progress").width(0).removeClass('bar-success', 'bar-danger').parent('div').addClass('active');
            },
            uploadProgress: function(event, position, total, percentComplete) { 
                $("#progress").width(percentComplete+'%');
            },
            success: function(response) {
                if(response.rejected.length == 0) {
                    $("#progress").width('100%').addClass('bar-success');
                } else {
                    $("#progress").width('100%').addClass('bar-danger');
                }
                $.each(response.rejected, function() {
                    $('#upload_list p:contains('+this+')').addClass('text-error');
                });
                $.each(response.accepted, function() {
                    $('#upload_list p:contains('+this+')').addClass('text-success');
                });
            },
            error: function() {
                $("#progress").width('100%').addClass('bar-danger');
            },
            complete: function(response) {
                $("#progress").parent('div').removeClass('active');
                $('#upload_form button[type=submit]').attr('disabled','disabled');
            }
        }; 
        $('#upload_proxy').on('click', function(){
            $('#upload').click();
        })
        $('#upload').on('change', function(){
            $('#upload_errors').text('');
            var error, upload_list;

            if($(this).get(0).files.length > 5) {
                error = 'You can upload a maximum of 5 photo at a time';
            } else {
                upload_list = [];
                $.each($(this).get(0).files, function(){
                    if(this.size > max_file_size) {
                        error = 'Photo: '+this.name+' exceeds maximum file size ('+(this.size/(1024*1024)).toFixed(2)+'Mb > '+(max_file_size/(1024*1024)).toFixed(2)+'Mb)'
                        return false; //stop iterating;
                    } else {
                        upload_list.push(this.name);
                    }
                });
            }

            if (error !== undefined) {
                var email = $('#upload_form input[name=email]').val();
                var album = $('#upload_form input[name=album]').val();
                $('#upload_form').resetForm()
                $('#upload_form input[name=email]').val(email);
                $('#upload_form input[name=album]').val(album);

                $('#upload_errors').text(error);
                $('#upload_form button[type=submit]').attr('disabled','disabled');
                $('#upload_list').html('');
            } else {
                $('#upload_form button[type=submit]').removeAttr('disabled');
                $('#upload_list').html(upload_list.map(function(e){return '<p class="mute">'+e+'</p>';}).join(''));
            }
        })
        $("#upload_form").ajaxForm(options);
    });
})(jQuery);


