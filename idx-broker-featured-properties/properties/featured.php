<?php

namespace IDX_Broker_Featured_Properties\Properties;

use IDX_Broker_Featured_Properties\Settings\Global_Settings;
use IDX_Broker_Featured_Properties\Settings\Posts;

class Featured {

	public static function get( $what = [] ) {

		if ( $what && ! is_array( $what ) ) {
			$what['post_id'] = $what;
		}

		if ( isset( $what['post_id'] ) ) {

			$all_properties      = Request::get_properties();
			$featured_properties = get_post_meta( $what['post_id'], Posts::$featured_properties_meta_key, true );

		} elseif ( isset( $what['option'] ) ) {

			$all_properties      = Request::get_properties();
			$featured_properties = get_option( 'ibfp' . $what['option'] );

		} else {
			$all_properties      = Request::get_properties();
			$featured_properties = get_option( Global_Settings::$featured_properties_option );

		}
		$properties_array = [];

		foreach ( (array) $featured_properties as $featured_property ) {

			if ( isset( $all_properties[ $featured_property ] ) ) {
				$properties_array[ $featured_property ] = $all_properties[ $featured_property ];
			}
		}

		return $properties_array;
	}


}
