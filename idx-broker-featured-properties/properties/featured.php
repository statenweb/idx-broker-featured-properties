<?php

namespace IDX_Broker_Featured_Properties\Properties;

use IDX_Broker_Featured_Properties\Settings\Global_Settings;

class Featured {

	public static function get() {

		$all_properties      = Request::get_properties();
		$featured_properties = get_option( Global_Settings::FEATURED_PROPERTIES_OPTION );
		$properties_array    = [];

		foreach ( $featured_properties as $featured_property ) {

			$properties_array[ $featured_property ] = $all_properties[ $featured_property ];
		}

		return $properties_array;
	}


}