var imageGallery = function (item) {

    $("#pluploadUploader").pluploadQueue({

        // General settings
        runtimes: 'gears,browserplus,html5',
        url: Routing.generate('hotel_stock_item_image', {id: item}),
        max_file_size: '18mb',
        chunk_size: '3mb',
        unique_names: true,
        sortable: true,
        // Resize images on clientside if we can
        resize: {width: 1024, height: 1024, quality: 90},
        // Specify what files to browse for
        filters: [
            {title: "Image files", extensions: "jpeg,jpg,gif,png"},
            {title: "Zip files", extensions: "zip"}
        ],

        // Flash settings
        flash_swf_url: 'theme/scripts/plupload/js/plupload.flash.swf',

        // Silverlight settings
        silverlight_xap_url: 'theme/scripts/plupload/js/plupload.silverlight.xap',
        init: {
            FilesAdded: function (up, files) {
                var maxfiles = 6;
                if (up.files.length > maxfiles) {
                    up.splice(maxfiles);
                    alert('no more than ' + maxfiles + ' file(s)');
                }
                if (up.files.length === maxfiles) {
                    $('#pluploadUploader').hide("slow"); // provided there is only one #uploader_browse on page
                }
            }
        }

    });
    var count = 0;
    $('.addmore').click(function () {

        var $el = $(this);
        var $cloneBlock = $('#clone-block');
        var $clone = $cloneBlock.find('.clone:eq(0)').clone();
        $clone.find('[id]').each(function () {
            this.id += 'someotherpart'
        });
        $clone.find(':text,textarea').val("");
        $clone.attr('id', "added" + (++count));
        $clone.find('.remove').removeClass('hidden');
        $cloneBlock.append($clone);
        $('.numeric').numeric();

    });

    $('#clone-block').on('click', '.remove', function () {
        $(this).closest('.clone').remove();
    });


    $(document).on("click", ".delete", function() {
        var id = $(this).attr("data-id");
        var url = $(this).attr("data-url");
        $('#confirm-content').confirmModal({
            topOffset: 0,
            top: '25%',
            onOkBut: function(event, el) {
                $.get(url, function( data ) {
                    $('#remove-'+id).remove();
                });
            }
        });
    });


}

