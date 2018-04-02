<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

}
//Woocommerce works already with 17 theme, but let's imagine it's older than 3.3 version...
add_theme_support( 'woocommerce' );

// Custom Post Type - Movies
function custom_post_movie() {

	$labels = array(
		'name'                => _x( 'Movies', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Movie', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Movies', 'text_domain' ),
		'parent_movie_colon'   => __( 'Parent movie:', 'text_domain' ),
		'all_movies'           => __( 'All Movies', 'text_domain' ),
		'view_movie'           => __( 'View movie', 'text_domain' ),
		'add_new_movie'        => __( 'Add New movie', 'text_domain' ),
		'add_new'             => __( 'Add New movie', 'text_domain' ),
		'edit_movie'           => __( 'Edit movie', 'text_domain' ),
		'update_movie'         => __( 'Update movie', 'text_domain' ),
		'search_movies'        => __( 'Search movie', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$args = array(
		'label'               => __( 'movies', 'text_domain' ),
		'description'         => __( 'Blah-blah description', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( ),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-star-empty',
		'can_export'          => true,
		'has_archive'         => true,
		'supports'   		  => array('thumbnail', 'excerpt', 'title', 'editor','movie','custom-fields'),
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'movies', $args );

}
add_action( 'init', 'custom_post_movie', 0 );

//Metaboxes for our CPT

class Movie_Meta_Box {
	private $screens = array(
		'movies',
	);
	private $fields = array(
		array(
			'id' => 'title',
			'label' => 'Заголовок',
			'type' => 'text',
		),
		array(
			'id' => 'subtitle',
			'label' => 'Подзаголовок',
			'type' => 'text',
		),
		array(
			'id' => 'content_meta',
			'label' => 'Контент',
			'type' => 'textarea',
		),
		array(
			'id' => 'image',
			'label' => 'Картинка',
			'type' => 'media',
		),
		array(
			'id' => 'category',
			'label' => 'Категория',
			'type' => 'text',
		),
		array(
			'id' => 'movie_price',
			'label' => 'Цена',
			'type' => 'text',
		),
	);

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
	}


	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'movie-info',
				__( 'Movie info', 'movies-metabox' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'normal',
				'high'
			);
		}
	}


	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'movie_info_data', 'movie_info_nonce' );
		$this->generate_fields( $post );
	}

	public function admin_footer() {
		?><script>

			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$('.rational-metabox-media').click(function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id').replace('_button', '');
						_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								$("#"+id).val(attachment.url);
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
				}
			});
		</script><?php
	}


	public function generate_fields( $post ) {
		$output = '';
		foreach ( $this->fields as $field ) {
			$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
			$db_value = get_post_meta( $post->ID, 'movie_info_' . $field['id'], true );
			switch ( $field['type'] ) {
				case 'media':
					$input = sprintf(
						'<input class="regular-text" id="%s" name="%s" type="text" value="%s"> <input class="button rational-metabox-media" id="%s_button" name="%s_button" type="button" value="Upload" />',
						$field['id'],
						$field['id'],
						$db_value,
						$field['id'],
						$field['id']
					);
					break;
				case 'textarea':
					$input = sprintf(
						'<textarea class="large-text" id="%s" name="%s" rows="5">%s</textarea>',
						$field['id'],
						$field['id'],
						$db_value
					);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$field['type'] !== 'color' ? 'class="regular-text"' : '',
						$field['id'],
						$field['id'],
						$field['type'],
						$db_value
					);
			}
			$output .= $this->row_format( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	public function row_format( $label, $input ) {
		return sprintf(
			'<tr><th scope="row">%s</th><td>%s</td></tr>',
			$label,
			$input
		);
	}

	public function save_post( $post_id ) {
		if ( ! isset( $_POST['movie_info_nonce'] ) )
			return $post_id;

		$nonce = $_POST['movie_info_nonce'];
		if ( !wp_verify_nonce( $nonce, 'movie_info_data' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
						break;
				}
				update_post_meta( $post_id, 'movie_info_' . $field['id'], $_POST[ $field['id'] ] );
			} else if ( $field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, 'movie_info_' . $field['id'], '0' );
			}
		}
	}
}
new Movie_Meta_Box;

//Skype on register form (The WOO way)

function movies_extra_register_fields() {
    ?>
    <p class="form-row">
    <label for="reg_billing_skype"><?php _e( 'Skype', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="billing_skype" id="reg_billing_skype" value="<?php if ( ! empty( $_POST['billing_skype'] ) ) esc_attr_e( $_POST['billing_skype'] ); ?>" />
    </p>
    <?php
}

add_action( 'woocommerce_register_form_start', 'movies_extra_register_fields' );
//Let's validate
function movies_validate_extra_register_fields( $username, $email, $validation_errors ) {
    if ( isset( $_POST['billing_skype'] ) && empty( $_POST['billing_skypee'] ) ) {
        $validation_errors->add( 'billing_skype_error', __( 'You can\'t have such skype login o_O', 'woocommerce' ) );
    }
}

add_action( 'woocommerce_register_post', 'movies_validate_extra_register_fields', 10, 3 );
//Let's save
function movies_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['billing_skype'] ) ) {
        // WooCommerce billing city
        update_user_meta( $customer_id, 'billing_skype', sanitize_text_field( $_POST['billing_skype'] ) );
    }
}
add_action( 'woocommerce_created_customer', 'movies_save_extra_register_fields' );

 
//Redirect after succesfull registration

function movies_registration_redirect() {
    return home_url( '/url-for-favorite-movies' );
}

add_filter( 'registration_redirect', 'movies_registration_redirect' );

//No cart page, let's go to checkout 

add_filter('woocommerce_add_to_cart_redirect', 'movies_add_to_cart_redirect');
function movies_add_to_cart_redirect() {
 global $woocommerce;
 $checkout_url = wc_get_checkout_url();
 return $checkout_url;
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'movies_cart_button_text' ); 
 
function movies_cart_button_text() {
 return __( 'Checkout', 'woocommerce' );
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'movies_cart_button_text' ); 
add_filter( 'woocommerce_product_add_to_cart_text', 'movies_cart_button_text' ); 

//Prodadim muviki(Let's sell)
class WCCPT_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT {



    public function read( &$product ) {

        $product->set_defaults();

        if ( ! $product->get_id() || ! ( $post_object = get_post( $product->get_id() ) ) || ! in_array( $post_object->post_type, array( 'movies', 'product' ) ) ) {
            throw new Exception( __( 'Invalid product.', 'woocommerce' ) );
        }

        $id = $product->get_id();

        $product->set_props( array(
            'name'              => $post_object->post_title,
            'slug'              => $post_object->post_name,
            'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
            'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
            'status'            => $post_object->post_status,
            'description'       => $post_object->post_content,
            'short_description' => $post_object->post_excerpt,
            'parent_id'         => $post_object->post_parent,
            'menu_order'        => $post_object->menu_order,
            'reviews_allowed'   => 'open' === $post_object->comment_status,
        ) );

        $this->read_attributes( $product );
        $this->read_downloads( $product );
        $this->read_visibility( $product );
        $this->read_product_data( $product );
        $this->read_extra_data( $product );
        $product->set_object_read( true );
    }


    public function get_product_type( $product_id ) {
        $post_type = get_post_type( $product_id );
        if ( 'product_variation' === $post_type ) {
            return 'variation';
        } elseif ( in_array( $post_type, array( 'movies', 'product' ) ) ) { 
            $terms = get_the_terms( $product_id, 'product_type' );
            return ! empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
        } else {
            return false;
        }
    }
}
add_filter( 'woocommerce_data_stores', 'woocommerce_data_stores' );

function woocommerce_data_stores ( $stores ) {      
    $stores['product'] = 'WCCPT_Product_Data_Store_CPT';
    return $stores;
}
add_filter('woocommerce_product_get_price', 'movies_product_get_price', 10, 2 );
function movies_product_get_price( $price, $product ) {
/*  	if ($post->post->post_type === 'movies') {
		$price = get_post_meta( get_the_ID(), 'movie_info_movie_price', true );
	return $price; 
	} */
	if ($post->post->post_type === 'movies') {
	
        $price = '19';        
    return $price;
	}
  
}  

add_filter('woocommerce_product_variation_get_price', 'movies_product_get_price_var', 10, 2 );
function movies_product_get_price_var( $price, $product ) {
 	if ($post->post->post_type === 'movies') {
		$price = get_post_meta( get_the_ID(), 'movie_info_movie_price', true );
	return $price; 
	}
	
} 