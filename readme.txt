=== IDX Broker Featured Properties ===
Contributors: matstars, statenweb
Donate link: https://statenweb.com/donate
Tags: IDX Broker
Requires at least: 4.0
Tested up to: 4.9.1
Stable tag: 0.0.8
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Curate properties from IDX Broker

== Description ==

*Note* You must have an IDX Broker account and API key to use this plugin

Adds the ability to curate your IDX Broker properties.

Please note that this plugin makes use of a 3rd party service to retrieve IDX Broker data. You must have an IDX Broker account + API to use this service. This means that a request is made to third party servers requesting data using your API Key. See https://developers.idxbroker.com/idx-broker-api/ for more information.

## Simple usage:

### Getting started (required for all use cases):

- Download, install and activate the plugin.
- After installing Go to `Settings` > `IDX Broker Featured Properties` and add your API Key.


### Curating your featured properties globally

- Follow steps in Getting started above
- You can now see all of your properties (including supplemental) -- you can select which properties you want as featured (by checking them) and reorder them. Use the API to retrieve the property objects.
- Use the API call `ibfp_get_featured_properties()` to retrieve a listing of property objects. Use them as you see fit!

### Curating your featured properties by post
- Follow steps in Getting started above
- You now can programmatically, via a filter, enable the meta box to appear on post edit screens, either by post type as a whole or programmatically based on context, see two examples:

```
add_filter( 'ibfp/post-types/display-meta-box', function ( $post_types ) {
	$post_types   = (array) $post_types;
	$post_types[] = 'post';

	return $post_types;
} );
```

```
add_filter( 'ibfp/post-types/displayd-meta-box-override', function ( $display_boolean, $post_object ) {

	if ( has_category( 'news', $post_object->ID ) ) {
		$display_boolean = true;
	}

	return $display_boolean;
}, 10, 2 );
```

- Use the API call `ibfp_get_featured_properties( $post_id )` to retrieve a listing of property objects for a particular post id. Use them as you see fit!


#### Release Notes

- 0.0.8
Fix warning if no featured properties have been curated already

- 0.0.7
Fix bug with IDX_Broker_Featured_Properties\Properties\Featured::get() to accept a post id parameter

- 0.0.6
Handle properties a bit cleaner (always use \IDX_Broker_Featured_Properties\Properties\Featured::get())
Cleanly handle if a property is delisted from IDX Broker

- 0.0.5
Fix composer validation

- 0.0.4
Added the ability to curate on single posts

- 0.0.3
Fix for notification if bad API key is entered, fix for situation where API key changes
Clean up the API request, remove unnecessary old code, up cache length to 10 minutes
Fix some issues with outputting if IDX API does not have specific types of properties

- 0.0.2
add in readme

- 0.0.1
initial release


#### Roadmap

Things I'd like to add in the future:

- Ability to create customizable carousels with featured properties.

- Translations



== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/idx-broker-featured-properties` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->IDX Broker Featured Properties screen to add your API Key
1. You can now see all of your properties (including supplemental) -- you can select which properties you want as featured (by checking them) and reorder them. Use the API to retrieve the property objects.
