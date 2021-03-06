<?php

namespace IDX_Broker_Featured_Properties\Settings;


use IDX_Broker_Featured_Properties\Properties\Featured;
use IDX_Broker_Featured_Properties\Properties\Request;

class Global_Settings {

	private static $menu_slug = 'idx_broker_featured_properties';
	public static $featured_properties_option = 'ibfp_featured_properties';

	public function init() {
		$this->attach_hooks();
	}

	public function attach_hooks() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
	}


	public function admin_menu() {

		add_submenu_page( 'options-general.php', 'IDX Broker Featured Properties', 'IDX Broker Featured Properties',
			'manage_options',
			self::$menu_slug, array( $this, 'options_page' ) );

	}


	public function settings_init() {

		add_settings_section(
			'ibfp_plugin_page_section',
			_x( '', 'settings page section title', 'ibfp' ),
			array( $this, 'settings_section_callback' ),
			'ibfp_plugin_page'
		);


		add_settings_field(
			'ibfp_api_key',
			_x( apply_filters( 'ibfp/settings/subtitle/api_key', 'IDX Broker API Key' ), 'settings page subtitle',
				'ibfp' ),
			array( $this, 'api_key_render' ),
			'ibfp_plugin_page',
			'ibfp_plugin_page_section'
		);

		add_settings_field(
			self::$featured_properties_option,
			_x( apply_filters( 'ibfp/settings/subtitle/properties', 'IDX Broker Properties' ), 'settings page subtitle',
				'ibfp' ),

			array( $this, 'featured_properties_render' ),
			'ibfp_plugin_page',
			'ibfp_plugin_page_section'
		);


		register_setting( 'ibfp_plugin_page', self::$featured_properties_option, array(
			'sanitize_callback' => function ( $value ) {

				return $value;
			}
		) );

		register_setting( 'ibfp_plugin_page', 'ibfp_api_key', array(
			'sanitize_callback' => function ( $value ) {
				if ( $value !== self::get_api_key() ) {
					delete_transient( Request::TRANSIENT_KEY );
					delete_option( self::$featured_properties_option );
				}

				return $value;
			}
		) );


	}

	public function featured_properties_render() {
		$api_key = Global_Settings::get_api_key();
		if ( ! $api_key ) :
			?>Please enter your API key<?php
			return;
		endif;
		$properties               = Request::get_properties();
		$selected_properties      = Featured::get();
		$selected_properties_keys = array_keys( $selected_properties );
		$property_keys            = array_keys( $properties );
		$unselected_properties    = array_diff( (array) $property_keys, (array) $selected_properties_keys );
		$properties_to_display    = false;
		?>
		<div id="ibfp-sortable"><?php

		if ( $selected_properties_keys ) :
			$properties_to_display = true;
			foreach ( $selected_properties_keys as $property ) :
				self::generate_checkbox( $property, $properties[ $property ], true );
			endforeach;
		endif;


		if ( $unselected_properties ):
			$properties_to_display = true;
			foreach ( $unselected_properties as $property ):
				self::generate_checkbox( $property, $properties[ $property ] );
			endforeach;
		endif;

		if ( ! $properties_to_display ) :
			?>No Properties To Display, please check your API key at <a
				href="http://middleware.idxbroker.com/mgmt/apikey.php" target="_blank">middleware.idxbroker.com/mgmt/apikey.php</a><?php
		endif;

		?></div><?php
	}

	public static function generate_checkbox( $property_id, $property, $checked = false ) {
		?>
		<p><input type="checkbox" name="<?php esc_attr_e( self::$featured_properties_option ) ?>[]"
				  value="<?php esc_attr_e( $property_id ); ?>"
			<?php checked( $checked, true ); ?>><?php esc_html_e( $property['address'] ); ?>
		<img class="sortable-indicator"
			 src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHdpZHRoPSIxNzkyIiBoZWlnaHQ9IjE3OTIiIHZpZXdCb3g9IjAgMCAxNzkyIDE3OTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTE3OTIgODk2cTAgMjYtMTkgNDVsLTI1NiAyNTZxLTE5IDE5LTQ1IDE5dC00NS0xOS0xOS00NXYtMTI4aC0zODR2Mzg0aDEyOHEyNiAwIDQ1IDE5dDE5IDQ1LTE5IDQ1bC0yNTYgMjU2cS0xOSAxOS00NSAxOXQtNDUtMTlsLTI1Ni0yNTZxLTE5LTE5LTE5LTQ1dDE5LTQ1IDQ1LTE5aDEyOHYtMzg0aC0zODR2MTI4cTAgMjYtMTkgNDV0LTQ1IDE5LTQ1LTE5bC0yNTYtMjU2cS0xOS0xOS0xOS00NXQxOS00NWwyNTYtMjU2cTE5LTE5IDQ1LTE5dDQ1IDE5IDE5IDQ1djEyOGgzODR2LTM4NGgtMTI4cS0yNiAwLTQ1LTE5dC0xOS00NSAxOS00NWwyNTYtMjU2cTE5LTE5IDQ1LTE5dDQ1IDE5bDI1NiAyNTZxMTkgMTkgMTkgNDV0LTE5IDQ1LTQ1IDE5aC0xMjh2Mzg0aDM4NHYtMTI4cTAtMjYgMTktNDV0NDUtMTkgNDUgMTlsMjU2IDI1NnExOSAxOSAxOSA0NXoiLz48L3N2Zz4="/>
		</p><?php
	}

	public static function get_api_key() {
		return get_option( 'ibfp_api_key' );
	}


	public function api_key_render() {

		$api_key = get_option( 'ibfp_api_key' );
		?>
		<input type="text" class="regular-text ltr" name="ibfp_api_key" value="<?php echo esc_attr( $api_key ); ?>">
		<p class="description"
		   id="ibfp_api_key-description"><?php echo sprintf( __( 'See <a target=_BLANK" href="%s">%s</a> for more information' ),
				'https://middleware.idxbroker.com/mgmt/apikey.php', 'middleware.idxbroker.com/mgmt/apikey.php' ); ?></p>

		<?php


	}


	public function settings_section_callback() {


	}


	public function options_page() {

		?>
		<div class="wrap">
			<form action='options.php' method='post'>

				<h1><?php echo esc_html_x( apply_filters( 'ibfp/settings/title', 'IDX Broker Featured Properties' ),
						'settings page title',
						'ibfp' ); ?></h1>

				<?php

				settings_fields( 'ibfp_plugin_page' );
				do_settings_sections( 'ibfp_plugin_page' );
				submit_button();
				?>

			</form>
		</div>
		<?php

	}
}