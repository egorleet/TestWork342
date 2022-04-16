jQuery('body').on('submit', '#create-product', function (e) {
    e.preventDefault();

    if (typeof ajaxurl !== 'undefined') {
        ajaxurl_temp = ajaxurl;
    } else {
        ajaxurl_temp = woocommerce_params.ajax_url;
    }

    var form = jQuery(this);
    var formData = new FormData(form[0]);
    formData.append('file', jQuery('input[type=file]')[0].files[0]);

    jQuery.ajax({
        type: form.attr('method'),
        url: ajaxurl_temp,
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(xhr) {
            form.append('<span class="loading">Creating</span>');
        },
        success: function (data) {
            result = JSON.parse(data);
            jQuery('.loading').html('Uploaded, link to product - <a href="'+result.url+'">'+result.url+'</a>');
        }
    });

});