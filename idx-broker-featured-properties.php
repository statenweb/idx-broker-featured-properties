<?php

/*
* Plugin Name: IDX Broker Featured Properties
* Plugin URI: https://github.com/statenweb/idx-broker-featured-properties
* Description: API for curating featured properties from IDX Broker
* Author: StatenWeb, Mat Gargano
* Version: 0.0.2
* Text Domain: idx-broker-featured-properties
* Author URI: https://statenweb.com
*/


use IDX_Broker_Featured_Properties\Notice\Notice;
use IDX_Broker_Featured_Properties\Properties\Featured;
use IDX_Broker_Featured_Properties\Settings\Global_Settings;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/wp-api-libraries/wp-idxbroker-api/wp-idxbroker-api.php';

$namespace = 'IDX_Broker_Featured_Properties';
spl_autoload_register( function ( $class ) use ( $namespace ) {
	$base = explode( '\\', $class );
	if ( $namespace === $base[0] ) {
		$file = __DIR__ . DIRECTORY_SEPARATOR . strtolower( str_replace( [ '\\', '_' ], [
					DIRECTORY_SEPARATOR,
					'-'
				], $class ) . '.php' );
		if ( file_exists( $file ) ) {
			require $file;
		} else {
			die( sprintf( 'File %s not found', $file ) );
		}
	}

} );

$global_settings = new Global_Settings();
$global_settings->init();

$notice = new Notice();
$notice->init();

function ibfp_get_featured_properties(){
	return Featured::get();
}
