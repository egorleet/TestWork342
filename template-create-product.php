<?php
/* Template Name: CREATE PRODUCT */

get_header(); ?>
<?php
wp_enqueue_style( 'woocommerce_inputs.css', get_template_directory_uri() . '/css/woocommerce_form.css' );
wp_enqueue_script( 'woocommerce_inputs.js', get_template_directory_uri() . '/js/woocommerce_form.js' );
?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post" id="create-product"
                  class="create-product">
                <div class="form-input-wrapper">
                    <label for="form_title" class="form-input-wrapper__label">Название товара</label>
                    <input id="form_title" type="text" name="title" class="form-input-wrapper__input">
                </div>
                <div class="form-input-wrapper">
                    <label for="form_price" class="form-input-wrapper__label">Цена</label>
                    <input id="form_price" type="number" name="price" class="form-input-wrapper__input">
                </div>
                <div class="form-input-wrapper">
                    <label for="form_image" class="form-input-wrapper__label">Изображение</label>
                    <input id="form_image" type="file" name="image" accept="image/*"
                           class="form-input-wrapper__input input-text">
                </div>
                <div class="form-input-wrapper">
                    <label for="form_date" class="form-input-wrapper__label">Дата</label>
                    <input id="form_date" type="date" name="date" class="form-input-wrapper__input input-text">
                </div>
                <div class="form-input-wrapper">
                    <label for="product_type" class="form-input-wrapper__label">Тип товара</label>
                    <select id="product_type" name="product_type" class="select short input-text">
                        <option value="none" selected="selected">---</option>
                        <option value="rare">rare</option>
                        <option value="frequent">frequent</option>
                        <option value="unusual">unusual</option>
                    </select>
                </div>
                <input type="text" name="action" value="ajax_create_product" class="hidden">
                <input type="text" name="type" value="form" class="hidden">
                <input type="submit" value="Отправить">
            </form>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
