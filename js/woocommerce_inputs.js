jQuery('body').on('change', 'input[name="image"]', function () {
    var file_data = jQuery(this).prop('files')[0];
    ajax_upload_image(file_data);
    console.log(file_data);
});

jQuery('body').on('change', 'input[name="date_woocommerce"]', function () {
    jQuery('input[name="date"]').val(jQuery(this).val())
});

function ajax_upload_image(file_data) {
    if (typeof ajaxurl !== 'undefined') {
        ajaxurl_temp = ajaxurl;
    } else {
        ajaxurl_temp = woocommerce_params.ajax_url;
    }
    form_data = new FormData();
    form_data.append('file', file_data);
    form_data.append('action', 'ajax_create_product');
    form_data.append('post_id', woocommerce_admin_meta_boxes_variations.post_id);
    jQuery.ajax({
        url: ajaxurl_temp,
        type: 'POST',
        contentType: false,
        processData: false,
        data: form_data,
        beforeSend: function (xhr) {
            jQuery('.image_set').append('<span class="loading">Loading...</span>');
        },
        complete: function () {
        },
        success: function (data) {
            jQuery('.loading').remove();
            result = JSON.parse(data);
            if (result.status === 'ok') {
                jQuery('.image_set').empty();
                jQuery('.image_set').append('<span id="image_uploaded"></span><label for="imageset_input" class="btn">Upload new image</label><input id="imageset_input" type="file" name="image" accept="image/*" class="hidden">');
                jQuery('input[name="url_image"]').val(result.file_url);
                jQuery('#image_uploaded').css('background', 'url(' + result.file_url + ')');
                jQuery('#_thumbnail_id').attr('value', result.file_id);
            }
        }
    });
}

jQuery('body').on('click', '.remove_image', function () {
    jQuery('.image_set').empty();
    jQuery('.image_set').append('<input type="file" name="image" accept="image/*">');
    jQuery('input[id="url_image"]').val('');
    jQuery('input[id="url_image"]').attr('value', '');
    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: 'action=ajax_remove_image&post_id=' + woocommerce_admin_meta_boxes_variations.post_id,
        beforeSend: function (xhr) {
            jQuery('.image_set').append('<span class="loading">Removing...</span>');
        },
        complete: function () {
        },
        success: function (data) {
            jQuery('.loading').remove();
            jQuery('#remove-post-thumbnail').click();
        }
    });

});


jQuery('body').on('click', '.update_btn', function () {
    jQuery('#publish').click();
});

jQuery('body').on('click', '.clear_btn', function () {
    jQuery('.additional_fields input').val('');
    jQuery('.additional_fields input').attr('value', '');
    jQuery("#product_type").val("none");
    jQuery('.image_set').empty();
    jQuery('.image_set').append('<input type="file" name="image" accept="image/*">');
    jQuery('#remove-post-thumbnail').click();
    jQuery(".additional_fields input, .additional_fields select").each(function (index) {
        name = jQuery(this).attr('name');
        console.log(name);
        jQuery('#postcustom td.left > input').each(function (index) {
            if (jQuery(this).attr('value') == name) {
                jQuery(this).next().find('input[name*="deletemeta"]').click();
            }
        });
    });
});