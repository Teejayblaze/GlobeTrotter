$(document).ready(function(){

    if ($('.staff-table')) {
        $('.show-staff-table').on('click', function(e) {
            e.preventDefault();
            $stafftable = $(this).closest('li').next('.staff-table');
            if ($stafftable.hasClass('hide-staff-table')) $stafftable.removeClass('hide-staff-table');
            else $stafftable.addClass('hide-staff-table');
        });
    }

    function preview_before_upload() {
        $('body').on('change', '#asset_image', function(evt) { // ensure a preview of the selected file from user (system HD)
            evt.preventDefault();

            let self = $(this);
            let preview = $('.img-placeholder');

            let files = self.get(0).files;

            if (files.length > 0) {
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('<img>')
                        .attr('src', e.target.result)
                        .css({width: '200px',})
                        .appendTo(preview);
                    }
                    reader.readAsDataURL(file);
                }
            }
        });


        $('body').on('change', '#asset_video', function(evt) { 
            evt.preventDefault();

            $self = $(this);
            $videoPlayer = $("#video-player").get(0);
            
            $files = $self.get(0).files[0];
            console.log("$files = ", $files);
            $('#file-name').html($files.name);
            if($files.size > (3 * 1024 * 1204)) {
                alert("You can not upload a file larger than 3MB");
                $self.val("");
                return ;
            }

            $fileReader = new FileReader();

            $fileReader.onload = function(evt){
                $videoPlayer.src = evt.target.result;
                $videoPlayer.load();
            }
            $fileReader.readAsDataURL($files);
        });
    }

    preview_before_upload();
});