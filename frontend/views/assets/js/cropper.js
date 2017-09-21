jQuery(function () {
    'use strict';
    var $image = jQuery('#image');
    var $dataX = jQuery('#x');
    var $dataY = jQuery('#y');
    var $dataHeight = jQuery('#height');
    var $dataWidth = jQuery('#width');
    var options = {
        viewMode: 1,
        dragMode: 'move',
        autoCropArea: 1,
        restore: false,
        highlight: false,
        cropBoxMovable: false,
        cropBoxResizable: false,
        aspectRatio: 1 / 1,
        preview: '.img-preview',
        crop: function (e) {
            $dataX.val(Math.round(e.x));
            $dataY.val(Math.round(e.y));
            $dataHeight.val(Math.round(e.height));
            $dataWidth.val(Math.round(e.width));
            if(!jQuery('#inputImage').val()) {
                jQuery('.btn-group button').attr('disabled', true);
                jQuery('.cropper-view-box').hide();
                jQuery('.cropper-wrap-box').hide();
            }
        }
    };

    // Cropper
    $image.cropper(options);

    // Methods
    jQuery('.docs-buttons').on('click', '[data-method]', function () {
        var $this = jQuery(this);
        var data = $this.data();
        var $target;
        var result;

        if ($this.prop('disabled') || $this.hasClass('disabled')) {
            return;
        }

        if ($image.data('cropper') && data.method) {
            data = jQuery.extend({}, data); // Clone a new one
            if (typeof data.target !== 'undefined') {
                $target = jQuery(data.target);
                if (typeof data.option === 'undefined') {
                    try {
                        data.option = JSON.parse($target.val());
                    } catch (e) {
                        console.log(e.message);
                    }
                }
            }
            result = $image.cropper(data.method, data.option, data.secondOption);
            if ($.isPlainObject(result) && $target) {
                try {
                    $target.val(JSON.stringify(result));
                } catch (e) {
                    console.log(e.message);
                }
            }
        }
        return false;
    });


    // Keyboard
    jQuery(document.body).on('keydown', function (e) {
        if (!$image.data('cropper') || this.scrollTop > 300) {
            return;
        }

        switch (e.which) {
            case 37:
                e.preventDefault();
                $image.cropper('move', -1, 0);
                break;

            case 38:
                e.preventDefault();
                $image.cropper('move', 0, -1);
                break;

            case 39:
                e.preventDefault();
                $image.cropper('move', 1, 0);
                break;

            case 40:
                e.preventDefault();
                $image.cropper('move', 0, 1);
                break;
        }
    });


    // Import image
    var $inputImage = jQuery('#inputImage');
    var URL = window.URL || window.webkitURL;
    var blobURL;

    if (URL) {
        $inputImage.change(function () {
            var files = this.files;
            var file;
            if (!$image.data('cropper')) {
                return;
            }
            if (files && files.length) {
                file = files[0];
                if (/^image\/\w+$/.test(file.type)) {
                    blobURL = URL.createObjectURL(file);
                    $image.one('built.cropper', function () {
                        // Revoke when load complete
                        URL.revokeObjectURL(blobURL);
                    }).cropper('reset').cropper('replace', blobURL);
                } else {
                    window.alert('Please choose an image file.');
                }
            }
            jQuery('.btn-group button').removeAttr('disabled');
        });
    } else {
        $inputImage.prop('disabled', true).parent().addClass('disabled');
    }
});