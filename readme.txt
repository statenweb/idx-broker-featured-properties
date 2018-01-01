=== IDX Broker Featured Properties ===
Contributors: matstars, statenweb
Donate link: https://statenweb.com/donate
Tags: IDX Broker
Requires at least: 4.0
Tested up to: 4.9.1
Stable tag: 0.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Curate properties from IDX Broker

== Description ==

*Note* You must have an IDX Broker account and API key to use this plugin

Adds the ability to curate your IDX Broker properties.

Please note that this plugin makes use of a 3rd party service to retrieve IDX Broker data. You must have an IDX Broker account + API to use this service. This means that a request is made to third party servers requesting data using your API Key. See https://developers.idxbroker.com/idx-broker-api/ for more information.

## Simple usage:

### Curating your featured properties

- Download, install and activate the plugin.
- After installing Go to `Settings` > `IDX Broker Featured Properties` and add your API Key.
- You can now see all of your properties (including supplemental) -- you can select which properties you want as featured (by checking them) and reorder them. Use the API to retrieve the property objects.
- Use the API call `ibfp_get_featured_properties()` to retrieve a listing of property objects. Use them as you see fit!

#### Release Notes


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
