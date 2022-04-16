<?php

/**
 * Подключим css для админ-панели
 */
if ( ! function_exists( 'admin_area_css' ) ) {
	function admin_area_css() {
		wp_enqueue_style( 'woocommerce_inputs.css', get_template_directory_uri() . '/css/woocommerce_inputs.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'admin_area_css' );

/**
 * Подключаем js для админ-панели
 */
if ( ! function_exists( 'admin_area_js' ) ) {
	function admin_area_js() {
		wp_enqueue_script( 'woocommerce_inputs.js', get_template_directory_uri() . '/js/woocommerce_inputs.js' );
	}
}
add_filter( 'admin_head', 'admin_area_js' );

if ( ! function_exists( 'woocommerce_image_upload_wrapper' ) ) {
	/**
	 * Изображение, вывод верстки
	 */
	function woocommerce_image_upload_wrapper( $post ) {

		$result = '<p class=" form-field">';
		$result .= '<label for="image">Upload image</label>';
		$result .= '<span class="image_set">';

		$url_image = get_post_meta( $post->ID, 'url_image', true );
		if ( $url_image ) {
			$result .= '<span id="image_uploaded" style="background: url(' . $url_image . ');"></span>';
			$result .= '<label for="imageset_input" class="btn">Upload new image</label>';
			$result .= '<input id="imageset_input" type="file" name="image" accept="image/*" class="hidden">';
		} else {
			$result .= '<input type="file" name="image" accept="image/*">';
		}
		$result .= '</span>';
		$result .= '<span class="remove_image">Remove</span>';
		$result .= '</p>';
		echo $result;
	}
}

if ( ! function_exists( 'ajax_remove_image' ) ) {
	add_action( 'wp_ajax_ajax_remove_image', 'ajax_remove_image' );
	add_action( 'wp_ajax_nopriv_ajax_remove_image', 'ajax_remove_image' );
	/**
	 * Удаляем изображение по ajax
	 */
	function ajax_remove_image() {
		$post_id = $_POST['post_id'];
		delete_post_meta( $post_id, 'url_image' );
	}
}

if ( ! function_exists( 'ajax_create_product' ) ) {
	add_action( 'wp_ajax_ajax_create_product', 'ajax_create_product' );
	add_action( 'wp_ajax_nopriv_ajax_create_product', 'ajax_create_product' );
	/**
	 * Загрузка изображение по ajax
	 */
	function ajax_create_product() {
		$post_id = intval( $_POST['post_id'] );
		if ( $post_id == 0 ) {
			$type    = 'form';
			$post_id = wp_insert_post( [
					'post_title'  => $_POST['title'],
					'post_type'   => 'product',
					'post_status' => 'publish'
				]
			);
			wp_set_object_terms( $post_id, 'simple', 'product_type' );
			update_post_meta( $post_id, '_regular_price', $_POST['price'] );
			update_post_meta( $post_id, 'product_type', $_POST['product_type'] );
			update_post_meta( $post_id, 'date', $_POST['date'] );
		} else {
			$type = 'admin';
		}
		$arr_img_ext = [ 'image/png', 'image/jpeg', 'image/jpg', 'image/gif' ];
		if ( in_array( $_FILES['file']['type'], $arr_img_ext ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			$attachment_id = media_handle_upload( 'file', 0 );

			if ( is_wp_error( $attachment_id ) ) {
				$result = [
					'status'  => 'error',
					'post_id' => $post_id,
					'url'     => get_the_permalink( $post_id )
				];
			} else {
				$file_url   = wp_get_attachment_url( $attachment_id );
				$thumb_url  = wp_get_attachment_thumb_url( $attachment_id );
				$medium_url = wp_get_attachment_image_url( $attachment_id, 'medium' );

				update_post_meta( $post_id, 'url_image', $file_url );
				set_post_thumbnail( $post_id, $attachment_id );

				$result = [
					'status'     => 'ok',
					'file_id'    => $attachment_id,
					'file_url'   => $file_url,
					'thumb_url'  => $thumb_url,
					'medium_url' => $medium_url,
					'post_id'    => $post_id,
					'url'        => get_the_permalink( $post_id )
				];

			}
			echo json_encode( $result );
		} else {
			if ( $type == 'form' ) {
				$result = [
					'status'  => 'error',
					'post_id' => $post_id,
					'url'     => get_the_permalink( $post_id )
				];
			} else {
				$result = [ 'status' => 'error' ];
			}

			echo json_encode( $result );
		}
		die;
	}
}

if ( ! function_exists( 'woocommerce_url_image' ) ) {
	/**
	 * Ссылка на изображение, поле
	 */
	function woocommerce_url_image() {
		woocommerce_wp_text_input( [
			'id'          => 'url_image',
			'label'       => __( 'Ссылка изображение', 'woocommerce' ),
			'placeholder' => 'Ссылка',
			'desc_tip'    => 'true',
			'data_type'   => 'url',
			'description' => __( 'Ссылка изображение', 'woocommerce' ),
		] );
	}
}

if ( ! function_exists( 'woocommerce_select' ) ) {
	/**
	 * Селект, поле
	 */
	function woocommerce_select() {
		woocommerce_wp_select( [
			'id'      => 'product_type',
			'label'   => 'Product type',
			'options' => [
				'none'     => __( '---', 'woocommerce' ),
				'rare'     => __( 'rare', 'woocommerce' ),
				'frequent' => __( 'frequent', 'woocommerce' ),
				'unusual'  => __( 'unusual', 'woocommerce' ),
			],
		] );
	}
}

if ( ! function_exists( 'woocommerce_date_wrapper' ) ) {
	/**
	 * Дата, вывод верстки
	 *
	 * @param int $post
	 */
	function woocommerce_date_wrapper( $post ) {
		$result = '<p class=" form-field">';
		$result .= '<label for="product_type">Date</label>';
		$date   = get_post_meta( $post->ID, 'date', true );
		if ( $date ) {
			$result .= '<input type="date" id="date_woocommerce" name="date_woocommerce" value="' . $date . '">';
		} else {
			$result .= '<input type="date" id="date_woocommerce" name="date_woocommerce">';
		}

		$result .= '</p>';
		echo $result;
	}
}

if ( ! function_exists( 'woocommerce_date_input' ) ) {
	/**
	 * Дата, поле
	 */
	function woocommerce_date_input() {
		woocommerce_wp_text_input( [
			'id'          => 'date',
			'label'       => __( 'Дата', 'woocommerce' ),
			'placeholder' => 'Ссылка',
			'desc_tip'    => 'true',
			'data_type'   => 'text',
			'description' => __( 'Дата', 'woocommerce' ),
		] );
	}
}

if ( ! function_exists( 'woocommerce_submit_reset' ) ) {
	/**
	 * Кнопка для обновления
	 */
	function woocommerce_submit_reset() {
		echo '<div class="btns_wrapper"><span class="clear_btn">Reset data</span><span class="update_btn">Update post</span></div>';
	}
}


add_action( 'woocommerce_product_options_general_product_data', 'add_custom_fields' );
if ( ! function_exists( 'add_custom_fields' ) ) {
	/**
	 * Добавление доп-полей
	 */
	function add_custom_fields() {
		global $product, $post;

		echo '<div class="options_group additional_fields">';
		//Форма загрузки изображения
		woocommerce_image_upload_wrapper( $post );
		//Скрываем поле
		echo '<div class="hidden woocommerce_url_image">';
		//Выводим ссылку на загруженное изображение
		woocommerce_url_image();
		echo '</div>';
		//Выводим дату
		woocommerce_date_wrapper( $post );
		echo '<div class="hidden woocommerce_date_input">';
		woocommerce_date_input();
		echo '</div>';
		//Выводим селектор
		woocommerce_select();
		//Сохранение и сброс
		woocommerce_submit_reset();
		echo '</div>';
	}
}

if ( ! function_exists( 'save_fields' ) ) {
	/**
	 * Сохраняем поля
	 *
	 * @param int $post_id
	 */
	function save_fields( $post_id ) {
		$product_type_select = $_POST['product_type'];
		if ( ! empty( $product_type_select ) ) {
			update_post_meta( $post_id, 'product_type', esc_attr( $product_type_select ) );
		}
		$woocommerce_date = $_POST['date'];
		if ( ! empty( $woocommerce_date ) ) {
			update_post_meta( $post_id, 'date', esc_attr( $woocommerce_date ) );
		}
		$woocommerce__image = $_POST['url_image'];
		if ( ! empty( $woocommerce__image ) ) {
			update_post_meta( $post_id, 'url_image', esc_attr( $woocommerce__image ) );
		}
	}
}
add_action( 'woocommerce_process_product_meta', 'save_fields', 10 );


if ( ! function_exists( 'create_product' ) ) {
	add_action( 'wp_ajax_create_product', 'create_product' );
	add_action( 'wp_ajax_nopriv_create_product', 'create_product' );
	/**
	 * Добавляем новый продукт
	 */
	function create_product() {
		$result = $_POST;
		echo json_encode( $result );
		die;
	}
}