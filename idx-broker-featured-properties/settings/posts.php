<?php

namespace IDX_Broker_Featured_Properties\Settings;


use IDX_Broker_Featured_Properties\Properties\Featured;
use IDX_Broker_Featured_Properties\Properties\Request;

class Posts {

	public static $featured_properties_meta_key = 'ibfp';
	private static $post_types;
	private static $nonce_key = 'securityibfp';

	public function init() {


		$this->attach_hooks();
	}

	public function attach_hooks() {
		add_action( 'init', array( $this, 'set_post_types' ) );
		add_action( 'add_meta_boxes', array( $this, 'meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 3 );
	}

	public function set_post_types() {
		self::$post_types = apply_filters( 'ibfp/post-types/display-meta-box', [] );
	}


	public function meta_box() {


		global $post;
		$current_post_type = $post->post_type;
		$post_types        = get_post_types( array( 'public' => true ) );
		foreach ( $post_types as $post_type ) {

			if (
				in_array( $current_post_type, (array) self::$post_types ) ||
				apply_filters( 'ibfp/post-types/display-meta-box-override', false, $post )

			) {

				add_meta_box( self::$featured_properties_meta_key, __( 'IDX Broker Featured Properties', 'ibfp' ),
					array( $this, 'display_callback' ), $post_type );
			}
		}


	}


	public function enqueue( $slug ) {

		if (
		in_array( $slug, array( 'post.php', 'post-new.php' ) )
		) {
			wp_enqueue_script( 'ibfp-admin', dirname( dirname( plugin_dir_url( __FILE__ ) ) ) . '/js/admin.js',
				[ 'jquery-ui-sortable' ] );

			wp_enqueue_style( 'ibfp-admin',
				dirname( dirname( plugin_dir_url( __FILE__ ) ) ) . '/css/admin.css' );
		}

	}


	public function featured_properties_render() {

		$properties               = Request::get_properties();
		$selected_properties      = Featured::get( get_the_ID() );
		$property_keys            = array_keys( $properties );
		$selected_properties_keys = array_keys( $selected_properties );
		$unselected_properties_keys    = array_diff( (array) $property_keys, (array) $selected_properties_keys );
		$properties_to_display    = false;
		?>
		<div id="ibfp-sortable"><?php

		if ( $selected_properties_keys ) :
			$properties_to_display = true;
			foreach ( $selected_properties_keys as $property ) :
				self::generate_checkbox( $property, $properties[ $property ], true );
			endforeach;
		endif;


		if ( $unselected_properties_keys ):
			$properties_to_display = true;
			foreach ( $unselected_properties_keys as $property ):
				self::generate_checkbox( $property, $properties[ $property ] );
			endforeach;
		endif;

		if ( ! $properties_to_display ) :
			?>No Properties To Display, please check your API key at <a
				href="http://middleware.idxbroker.com/mgmt/apikey.php" target="_blank">middleware.idxbroker.com/mgmt/apikey.php</a><?php
		endif;

		?>
		<input type="hidden" name="<?php esc_attr_e( self::$nonce_key ); ?>"
			   value="<?php esc_attr_e( wp_create_nonce( self::$nonce_key ) ); ?>">
		<?php

		?></div><?php
	}

	public static function generate_checkbox( $property_id, $property, $checked = false ) {
		?>
		<p><input type="checkbox" name="<?php esc_attr_e( self::$featured_properties_meta_key ) ?>[]"
				  value="<?php esc_attr_e( $property_id ); ?>"
			<?php checked( $checked, true ); ?>><?php esc_html_e( $property['address'] ); ?>
		<img class="sortable-indicator"
			 src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHdpZHRoPSIxNzkyIiBoZWlnaHQ9IjE3OTIiIHZpZXdCb3g9IjAgMCAxNzkyIDE3OTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTE3OTIgODk2cTAgMjYtMTkgNDVsLTI1NiAyNTZxLTE5IDE5LTQ1IDE5dC00NS0xOS0xOS00NXYtMTI4aC0zODR2Mzg0aDEyOHEyNiAwIDQ1IDE5dDE5IDQ1LTE5IDQ1bC0yNTYgMjU2cS0xOSAxOS00NSAxOXQtNDUtMTlsLTI1Ni0yNTZxLTE5LTE5LTE5LTQ1dDE5LTQ1IDQ1LTE5aDEyOHYtMzg0aC0zODR2MTI4cTAgMjYtMTkgNDV0LTQ1IDE5LTQ1LTE5bC0yNTYtMjU2cS0xOS0xOS0xOS00NXQxOS00NWwyNTYtMjU2cTE5LTE5IDQ1LTE5dDQ1IDE5IDE5IDQ1djEyOGgzODR2LTM4NGgtMTI4cS0yNiAwLTQ1LTE5dC0xOS00NSAxOS00NWwyNTYtMjU2cTE5LTE5IDQ1LTE5dDQ1IDE5bDI1NiAyNTZxMTkgMTkgMTkgNDV0LTE5IDQ1LTQ1IDE5aC0xMjh2Mzg0aDM4NHYtMTI4cTAtMjYgMTktNDV0NDUtMTkgNDUgMTlsMjU2IDI1NnExOSAxOSAxOSA0NXoiLz48L3N2Zz4="/>
		</p><?php
	}


	public function display_callback() {


		$this->featured_properties_render();


	}

	public function save_post( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( false !== wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! array_key_exists( self::$featured_properties_meta_key, $_POST ) ) {
			return;
		}

		if ( ! array_key_exists( self::$nonce_key, $_POST ) ) {
			return;
		}


		if ( ! wp_verify_nonce( $_POST[ self::$nonce_key ], self::$nonce_key ) ) {
			die( __( 'Security check', 'ibfp' ) );
		}

		$value = $_POST[ self::$featured_properties_meta_key ];
		update_post_meta( $post_id, self::$featured_properties_meta_key, $value );

	}

}