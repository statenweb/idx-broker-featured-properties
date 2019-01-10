<?php

namespace IDX_Broker_Featured_Properties\Properties;

use IDX_Broker_Featured_Properties\Settings\Global_Settings;

class Request {

	const TRANSIENT_KEY = 'ibfp_properties_from_api';
	const TRANSIENT_LENGTH = 600;

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
                        $data                  = apply_filters( 'ibfp/raw-data', $data );
			set_transient( self::TRANSIENT_KEY, $data, self::TRANSIENT_LENGTH );
		}

		return $data;

	}


	private static function rekey_data( $data ) {
		$rekeyed_data = [];
		foreach ( $data as $datum ) {
			$new_key                  = $datum['idxID'] . $datum['listingID'];
			$rekeyed_data[ $new_key ] = $datum;
		}

		return $rekeyed_data;

	}


}
