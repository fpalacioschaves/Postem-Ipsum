<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class PostemIpsum_Admin {

	public function __construct() {

		add_action( 'init', array( $this, 'postem_ipsum_enqueue_admin_scripts' ), 10000 );

		add_action(
			'wp_ajax_postem_ipsum_get_taxonomies', array(
				$this,
				'postem_ipsum_get_taxonomies',
			)
		);

		add_action(
			'wp_ajax_postem_ipsum_get_terms', array(
				$this,
				'postem_ipsum_get_terms',
			)
		);

		add_action(
			'wp_ajax_postem_ipsum_generate_posts', array(
				$this,
				'postem_ipsum_generate_posts',
			)
		);

		add_action(
			'wp_ajax_postem_ipsum_remove_posts', array(
				$this,
				'postem_ipsum_remove_posts',
			)
		);


		add_action(
			'admin_menu', array(
				$this,
				'postem_ipsum_setup_menu',
			)
		);

		add_action(
			'wp_ajax_postem_ipsum_generate_products', array(
				$this,
				'postem_ipsum_generate_products',
			)
		);

		add_action(
			'wp_ajax_postem_ipsum_remove_products', array(
				$this,
				'postem_ipsum_remove_products',
			)
		);
	}

	public function postem_ipsum_general_settings() {
		$args_post_types = array(
			'public' => true,
		);
		$operator        = 'and';
		$post_types      = get_post_types( $args_post_types, 'objects', $operator );
		require plugin_dir_path( __FILE__ ) . 'views/postem_ipsum_settings.php';
	}

	public function postem_ipsum_woocommerce_settings() {
		require plugin_dir_path( __FILE__ ) . 'views/postem_ipsum_woo_settings.php';
	}

	public function postem_ipsum_setup_menu() {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		add_menu_page(
			"Postem Ipsum"
			, "Postem Ipsum"
			, 'nosuchcapability'
			, 'postem-ipsum'
		);

		add_submenu_page(
			POSTEM_IPSUM_TEXT_DOMAIN
			, __( "General", POSTEM_IPSUM_TEXT_DOMAIN )
			, __( "General", POSTEM_IPSUM_TEXT_DOMAIN )
			, 'manage_options'
			, 'postem-ipsum-general-settings'
			, array( $this, 'postem_ipsum_general_settings' )
		);

		// Check if Woocommerce is active
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_submenu_page(
				POSTEM_IPSUM_TEXT_DOMAIN
				, "Woocmmwerce"
				, "Woocommerce"
				, 'manage_options'
				, 'postem-ipsum-woocommerce-settings'
				, array( $this, 'postem_ipsum_woocommerce_settings' )
			);
		}
	}

	public function postem_ipsum_enqueue_admin_scripts() {
		if ( ! isset( $_GET["page"] ) ) {
			return;
		}
		if ( isset( $_GET["page"] ) && $_GET["page"] == "postem-ipsum-general-settings" || $_GET['page'] == "postem-ipsum-woocommerce-settings" ) {
			// Add the color picker css file
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'postem-ipsum-loadingModal-jquery-js', plugins_url( 'assets/js/jquery.loadingModal.js', __FILE__ ), array( 'jquery' ), null, false );
			wp_enqueue_script( 'postem-ipsum-slider-jquery-js', plugins_url( 'assets/js/nouislider.js', __FILE__ ), array( 'jquery' ), null, false );

			//wp_enqueue_script( 'postem-ipsum-admin-jquery-js', plugins_url( 'assets/js/postem_ipsum_admin_main.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			wp_enqueue_script( 'postem-ipsum-admin-jquery-js', plugins_url( 'assets/js/postem_ipsum_admin_main.min.js', __FILE__ ), array( 'wp-color-picker' ), false, true );

			wp_enqueue_style( 'postem-ipsum-slider-style', plugins_url( 'assets/css/nouislider.css', __FILE__ ) );
			wp_enqueue_style( 'postem-ipsum-loadingModal-style', plugins_url( 'assets/css/jquery.loadingModal.css', __FILE__ ) );
			wp_enqueue_style( 'postem-ipsum-admin-style', plugins_url( 'assets/css/postem_ipsum_admin_style.css', __FILE__ ) );
		}
	}

	////////////////////////////////// CUSTOM POSTS ///////////////////////////////////
	public function postem_ipsum_get_taxonomies() {
		if ( isset( $_POST['post_type'] ) ) {
			$post_type               = $_POST['post_type'];
			$postem_ipsum_taxonomies = get_object_taxonomies( $post_type, 'object' );
			require plugin_dir_path( __FILE__ ) . 'views/postem_ipsum_get_taxonomies.php';
		}
		die();
	}

	public function postem_ipsum_get_terms() {
		if ( isset( $_POST['taxonomy'] ) ) {
			$taxonomy = $_POST['taxonomy'];
			if ( $taxonomy != "category" ) {
				$postem_ipsum_terms = get_terms( $taxonomy, array( 'hide_empty' => false ) );
			} else {
				$postem_ipsum_terms = get_categories( array( 'hide_empty' => 0 ) );
			}
			require plugin_dir_path( __FILE__ ) . 'views/postem_ipsum_get_terms.php';
		}
		die();
	}

	public function postem_ipsum_generate_posts() {

		if ( isset( $_POST['variables'] ) ) {

			$variables = $_POST['variables'];

			$background_color   = isset( $_POST['bg_color'] ) ? sanitize_text_field( $_POST['bg_color'] ) : "#000";
			$bg_color           = substr( $background_color, 1 );
			$bg_random          = isset( $_POST["bg_random"] ) ? sanitize_text_field( $_POST["bg_random"] ) : "";
			$post_type          = isset( $variables['postem_ipsum_post_type'] ) ? sanitize_text_field( $variables['postem_ipsum_post_type'] ) : "post";
			$taxonomy           = isset( $variables['postem_ipsum_taxonomy'] ) ? sanitize_text_field( $variables['postem_ipsum_taxonomy'] ) : "category";
			$term               = isset( $variables['postem_ipsum_term'] ) ? intval( $variables['postem_ipsum_term'] ) : 1;
			$post_number        = isset( $variables['postem_ipsum_post_number'] ) ? intval( $variables['postem_ipsum_post_number'] ) : 5;
			$paragraph_number   = isset( $variables['postem_ipsum_paragraphs'] ) ? intval( $variables['postem_ipsum_paragraphs'] ) . "/" : "5/";
			$paragraph_length   = isset( $variables['postem_ipsum_paragraph_length'] ) ? sanitize_text_field( $variables['postem_ipsum_paragraph_length'] ) . "/" : "short/";
			$paragraph_decorate = isset( $variables['postem_ipsum_paragraph_decorate'] ) ? ( $variables['postem_ipsum_paragraph_decorate'] == "yes" ? "decorate/" : "" ) : "";
			$paragraph_links    = isset( $variables['postem_ipsum_paragraph_links'] ) ? ( $variables['postem_ipsum_paragraph_links'] == "yes" ? "link/" : "" ) : "";
			$paragraph_ul       = isset( $variables['postem_ipsum_paragraph_ul'] ) ? ( $variables['postem_ipsum_paragraph_ul'] == "yes" ? "ul/" : "" ) : "";
			$paragraph_ol       = isset( $variables['postem_ipsum_paragraph_ol'] ) ? ( $variables['postem_ipsum_paragraph_ol'] == "yes" ? "ol/" : "" ) : "";
			$paragraph_dl       = isset( $variables['postem_ipsum_paragraph_dl'] ) ? ( $variables['postem_ipsum_paragraph_dl'] == "yes" ? "dl/" : "" ) : "";
			$paragraph_bq       = isset( $variables['postem_ipsum_paragraph_bq'] ) ? ( $variables['postem_ipsum_paragraph_bq'] == "yes" ? "bq/" : "" ) : "";
			$paragraph_code     = isset( $variables['postem_ipsum_paragraph_code'] ) ? ( $variables['postem_ipsum_paragraph_code'] == "yes" ? "code/" : "" ) : "";
			$paragraph_headers  = isset( $variables['postem_ipsum_paragraph_header'] ) ? ( $variables['postem_ipsum_paragraph_header'] == "yes" ? "headers/" : "" ) : "";

			for ( $contador = 0; $contador < $post_number; $contador ++ ) {

				// The content
				$content_url = 'http://loripsum.net/api/' . $paragraph_number . $paragraph_length . $paragraph_decorate . $paragraph_links . $paragraph_ul . $paragraph_ol . $paragraph_dl . $paragraph_bq . $paragraph_code . $paragraph_headers;
				$response    = wp_remote_get( $content_url );
				if ( is_array( $response ) ) {
					$header = $response['headers']; // array of http header lines
					$data   = $response['body']; // use the content
				}

				// The title
				$response_title = wp_remote_get( 'http://loripsum.net/api/1/short' );
				if ( is_array( $response_title ) ) {
					$header     = $response_title['headers']; // array of http header lines
					$title_text = $response_title['body']; // use the content
				}

				$title                 = $this->postem_ipsum_truncate_words( $title_text, 15 );
				$short_description_url = 'http://loripsum.net/api/1/short';

				$response_desc = wp_remote_get( $short_description_url );
				if ( is_array( $response_desc ) ) {
					$header            = $response_desc['headers']; // array of http header lines
					$short_description = $response_desc['body']; // use the content

					// SI HAY RESPUESTA
					// Creamos el post type
					$post_id = wp_insert_post( array(
						'post_type'    => $post_type,
						'post_title'   => sanitize_text_field( $title ),
						'post_content' => $data,
						'post_status'  => 'publish',
						'post_excerpt' => sanitize_textarea_field( $short_description ),
					) );

					// Le asignamos taxonomia y term
					wp_set_post_terms( $post_id, $term, $taxonomy );

					// le añadimos un meta flag para poder borrarlos cuando lo crea necesario el usuario
					add_post_meta( $post_id, 'postem_ipsum_flag', "yes" );

					$image = $variables['postem_ipsum_featured_image'];

					if ( $image == "yes" ) {

						$image_w = intval( $variables['postem_ipsum_image_w'] );
						$image_h = intval( $variables['postem_ipsum_image_h'] );

						if ( $bg_random == "1" ) {
							$bg_color = $this->postem_ipsum_rand_color();
						}

						// Add Featured Image to Post
						$image_url  = 'https://dummyimage.com/' . $image_w . 'x' . $image_w . '/' . $bg_color . '/fff/&text=Postem+Ipsum+rules';
						$image_name = $title . '.png';
						$upload_dir = wp_upload_dir(); // Set upload folder

						$image_data_conection = wp_remote_get( $image_url );
						if ( is_array( $image_data_conection ) ) {
							$header     = $image_data_conection['headers']; // array of http header lines
							$image_data = $image_data_conection['body']; // use the content
						}

						$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
						$filename         = basename( $unique_file_name ); // Create image file name

						// Check folder permission and define file location
						if ( wp_mkdir_p( $upload_dir['path'] ) ) {
							$file = $upload_dir['path'] . '/' . $filename;
						} else {
							$file = $upload_dir['basedir'] . '/' . $filename;
						}

						// Create the image  file on the server
						file_put_contents( $file, $image_data );

						// Check image file type
						$wp_filetype = wp_check_filetype( $filename, null );

						// Set attachment data
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => sanitize_file_name( $filename ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						// Create the attachment
						$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

						// Include image.php
						require_once( ABSPATH . 'wp-admin/includes/image.php' );

						// Define attachment metadata
						$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

						// Assign metadata to attachment
						wp_update_attachment_metadata( $attach_id, $attach_data );

						// And finally assign featured image to post
						set_post_thumbnail( $post_id, $attach_id );

					}
				}
			}
		}
		die();
	}

	public function postem_ipsum_remove_posts() {

		global $wpdb;
		$result = $wpdb->get_results( "SELECT ID  FROM wp_posts INNER JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID WHERE (wp_postmeta.meta_key = 'postem_ipsum_flag' AND wp_postmeta.meta_value IS NOT NULL);" );
		foreach ( $result as $post ) {
			$post_id = $post->ID;
			// remove image
			$this->postem_ipsum_remove_attachment_with_post( $post_id );
			// remove post
			wp_delete_post( $post_id );
		}
		die();
	}

////////////////////////////////// WOO ///////////////////////////////////
	public function postem_ipsum_generate_products() {
		if ( isset( $_POST['variables'] ) ) {
			$variables = $_POST['variables'];

			// Cogemos todas las categorias por si queremos coger una aleatoria
			$args               = array(
				'taxonomy'   => "product_cat",
				'hide_empty' => 0,
			);
			$product_categories = get_terms( $args );
			$categories_number  = sizeof( $product_categories );
			$price_min          = isset( $_POST['price_min'] ) ? intval( $_POST['price_min'] ) : 0;
			$price_max          = isset( $_POST['price_max'] ) ? intval( $_POST['price_max'] ) : 1000;
			$cat_random         = isset( $_POST["cat_random"] ) ? sanitize_text_field( $_POST["cat_random"] ) : "0";
			$background_color   = isset( $_POST['bg_color'] ) ? sanitize_text_field( $_POST['bg_color'] ) : "#000";
			$bg_color           = $str = substr( $background_color, 1 );
			$bg_random          = isset( $_POST["bg_random"] ) ? sanitize_text_field( $_POST["bg_random"] ) : "";
			$post_type          = "product";
			$taxonomy           = "product_cat";
			$term               = $variables['cat'];
			$post_number        = isset( $variables['postem_ipsum_woo_products_number'] ) ? intval( $variables['postem_ipsum_woo_products_number'] ) : 5;
			$paragraph_number   = isset( $variables['postem_ipsum_woo_product_paragraphs'] ) ? intval( $variables['postem_ipsum_woo_product_paragraphs'] ) . "/" : "1/";
			$paragraph_length   = isset( $variables['postem_ipsum_woo_product_paragraph_length'] ) ? sanitize_text_field( $variables['postem_ipsum_woo_product_paragraph_length'] ) . "/decorate/" : "short/decorate/";

			for ( $contador = 0; $contador < $post_number; $contador ++ ) {

				// The content
				$content_url = 'http://loripsum.net/api/' . $paragraph_number . $paragraph_length;
				$response    = wp_remote_get( $content_url );
				if ( is_array( $response ) ) {
					$header = $response['headers']; // array of http header lines
					$data   = $response['body']; // use the content

					// Short description
					$response_desc = wp_remote_get( 'http://loripsum.net/api/1/short' );
					if ( is_array( $response_desc ) ) {
						$header            = $response_desc['headers']; // array of http header lines
						$short_description = $response['body']; // use the content
					}

					// The title
					$response_title = wp_remote_get( 'http://loripsum.net/api/1/short' );
					if ( is_array( $response_title ) ) {
						$header     = $response_title['headers']; // array of http header lines
						$title_text = $response_title['body']; // use the content
					}

					$title = $this->postem_ipsum_truncate_words( $title_text, 15 );

					// Creamos el post type
					$post_id = wp_insert_post( array(
						'post_type'    => $post_type,
						'post_title'   => sanitize_text_field( $title ),
						'post_content' => $data,
						'post_status'  => 'publish',
						'post_excerpt' => sanitize_textarea_field( $short_description ),
					) );

					// Le asignamos taxonomia y term

					if ( $cat_random == "1" ) {
						$term = $product_categories[ rand( 0, $categories_number - 1 ) ]->term_id;
					}

					wp_set_post_terms( $post_id, $term, $taxonomy );

					$price = wc_format_decimal( floatval( rand( $price_min, $price_max ) ) );
					$sku   = $this->postem_ipsum_generateRandomString( 15 );
					$stock = rand( 0, 500 );

					// meta values de producto
					wp_set_object_terms( $post_id, 'simple', 'product_type' );
					update_post_meta( $post_id, '_visibility', 'visible' );
					update_post_meta( $post_id, '_stock_status', 'instock' );
					update_post_meta( $post_id, 'total_sales', '0' );
					update_post_meta( $post_id, '_downloadable', 'no' );
					update_post_meta( $post_id, '_virtual', 'no' );
					update_post_meta( $post_id, '_regular_price', $price );
					update_post_meta( $post_id, '_sale_price', $price );
					update_post_meta( $post_id, '_purchase_note', '' );
					update_post_meta( $post_id, '_featured', 'no' );
					update_post_meta( $post_id, '_weight', '' );
					update_post_meta( $post_id, '_length', '' );
					update_post_meta( $post_id, '_width', '' );
					update_post_meta( $post_id, '_height', '' );
					update_post_meta( $post_id, '_sku', $sku );
					update_post_meta( $post_id, '_product_attributes', array() );
					update_post_meta( $post_id, '_sale_price_dates_from', '' );
					update_post_meta( $post_id, '_sale_price_dates_to', '' );
					update_post_meta( $post_id, '_price', $price );
					update_post_meta( $post_id, '_sold_individually', '' );
					update_post_meta( $post_id, '_manage_stock', 'yes' );
					update_post_meta( $post_id, '_backorders', 'no' );
					update_post_meta( $post_id, '_stock', $stock );

					// le añadimos un meta flag para poder borrarlos cuando lo crea necesario el usuario
					add_post_meta( $post_id, 'postem_ipsum_woo_flag', "yes" );

					$image   = $variables['postem_ipsum_woo_product_image'];
					$image_w = $variables['postem_ipsum_woo_product_image_w'];
					$image_h = $variables['postem_ipsum_woo_product_image_h'];

					if ( $image == "yes" ) {
						if ( $bg_random == "1" ) {
							$bg_color = $this->postem_ipsum_rand_color();
						}

						// Add Featured Image to Post
						$image_url = 'https://dummyimage.com/' . $image_w . 'x' . $image_w . '/' . $bg_color . '/fff/&text=Postem+Ipsum+rules';

						$image_name = $title . '.png';
						$upload_dir = wp_upload_dir(); // Set upload folder

						$image_data_conection = wp_remote_get( $image_url );
						if ( is_array( $image_data_conection ) ) {
							$header     = $image_data_conection['headers']; // array of http header lines
							$image_data = $image_data_conection['body']; // use the content
						}

						$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
						$filename         = basename( $unique_file_name ); // Create image file name

						// Check folder permission and define file location
						if ( wp_mkdir_p( $upload_dir['path'] ) ) {
							$file = $upload_dir['path'] . '/' . $filename;
						} else {
							$file = $upload_dir['basedir'] . '/' . $filename;
						}

						// Create the image  file on the server
						file_put_contents( $file, $image_data );

						// Check image file type
						$wp_filetype = wp_check_filetype( $filename, null );

						// Set attachment data
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => sanitize_file_name( $filename ),
							'post_content'   => '',
							'post_status'    => 'inherit'
						);

						// Create the attachment
						$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

						// Include image.php
						require_once( ABSPATH . 'wp-admin/includes/image.php' );

						// Define attachment metadata
						$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

						// Assign metadata to attachment
						wp_update_attachment_metadata( $attach_id, $attach_data );

						// And finally assign featured image to post
						set_post_thumbnail( $post_id, $attach_id );

					}

				}

			}

		}
		die();
	}

	public function postem_ipsum_remove_products() {
		global $wpdb;
		$result = $wpdb->get_results( "SELECT ID FROM wp_posts INNER JOIN wp_postmeta ON wp_postmeta.post_id = wp_posts.ID WHERE (wp_postmeta.meta_key = 'postem_ipsum_woo_flag' AND wp_postmeta.meta_value IS NOT NULL);" );
		foreach ( $result as $post ) {
			$post_id = $post->ID;
			// remove image
			$this->postem_ipsum_remove_attachment_with_post( $post_id );
			// remove post
			wp_delete_post( $post_id );
		}
		die();
	}

	public function postem_ipsum_generateRandomString( $length = 10 ) {
		$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen( $characters );
		$randomString     = '';
		for ( $i = 0; $i < $length; $i ++ ) {
			$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
		}

		return $randomString;
	}

	public function postem_ipsum_truncate_words( $string, $words = 20 ) {
		return preg_replace( '/((\w+\W*){' . ( $words - 1 ) . '}(\w+))(.*)/', '${1}', $string );
	}

	public function postem_ipsum_remove_attachment_with_post( $post_id ) {
		if ( has_post_thumbnail( $post_id ) ) {
			$attachment_id = get_post_thumbnail_id( $post_id );
			wp_delete_attachment( $attachment_id, true );
		}
	}

	public function postem_ipsum_rand_color() {
		return sprintf( '%06X', mt_rand( 0, 0xFFFFFF ) );
	}

}