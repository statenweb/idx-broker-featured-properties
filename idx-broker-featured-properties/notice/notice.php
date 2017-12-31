<?php

namespace IDX_Broker_Featured_Properties\Notice;


use IDX_Broker_Featured_Properties\Settings\Global_Settings;

class Notice {


	public function init() {
		$this->attach_hooks();
	}

	public function attach_hooks() {
		add_action( 'admin_notices', array( $this, 'maybe_display_notice' ) );
	}

	public function maybe_display_notice() {
		$api_key = Global_Settings::get_api_key();
		if ( $api_key ) {
			return;
		}

		$class   = 'notice notice-error';
		$message = __( 'Please set your IDX Broker API Key in <a href="%s">Settings > IDX Broker Featured Properties</a>. See <a target="_BLANK" href="https://middleware.idxbroker.com/mgmt/apikey.php">middleware.idxbroker.com/mgmt/apikey.php</a> for more information.',
			'ibfp' );

		$message = sprintf( $message, admin_url( 'options-general.php?page=idx_broker_featured_properties' ) );

		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );

	}


}