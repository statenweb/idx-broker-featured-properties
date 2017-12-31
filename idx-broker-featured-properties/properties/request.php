<?php

namespace IDX_Broker_Featured_Properties\Properties;

use IDX_Broker_Featured_Properties\Settings\Global_Settings;

class Request {

	const OPTION_NAME_FOR_SUPPLEMENTAL_DATA = 'properties_plaza_supplemental';
	const TRANSIENT_KEY = 'ibfp_properties_from_api';
	const TRANSIENT_LENGTH = 60;

	public static function get_properties() {

		$api_key = Global_Settings::get_api_key();
		if ( ! $api_key ) {
			return [];
		}

		$data = get_transient( self::TRANSIENT_KEY );
		if ( ! $data ) {


			$idx_api               = new \IdxBrokerAPI( $api_key );
			$supplemental_listings = $idx_api->build_request( 'clients/supplemental' )->request();
			$featured_listings     = $idx_api->build_request( 'clients/featured' )->request();
			$data                  = array_merge( (array) $supplemental_listings, (array) $featured_listings );
			$data                  = self::rekey_data( $data );
			set_transient( self::TRANSIENT_KEY, $data, self::TRANSIENT_LENGTH );
		}

		return $data;

	}


	private static function get_supplemental_data() {
		return get_option( self::OPTION_NAME_FOR_SUPPLEMENTAL_DATA );
	}


	private static function rekey_data( $data ) {
		$rekeyed_data = [];
		$supplemental = self::get_supplemental_data();
		foreach ( $data as $datum ) {
			$new_key = $datum['idxID'] . $datum['listingID'];
			if ( array_key_exists( $new_key, (array) $supplemental ) ) {
				$datum = array_merge( $datum, $supplemental[ $new_key ] );
			}
			$rekeyed_data[ $new_key ] = $datum;
		}

		return $rekeyed_data;

	}


}