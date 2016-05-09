=== WP API Options ===
Contributors: oleg2tor
Tags: wp-api, wp-rest-api, json-rest-api, json, options, rest, api, wp-rest-options, general, discussion, media, reading, writing
Requires at least: 3.6.0
Tested up to: 4.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Extends WordPress WP REST API with new routes pointing to WordPress options.

== Description ==

This plugin extends the [WordPress JSON REST API](https://wordpress.org/plugins/rest-api/) with new routes for WordPress options

== Installation ==

This plugin requires having [WP API](https://wordpress.org/plugins/rest-api/) installed and activated or it won't be of any use.

Install the plugin as you would with any WordPress plugin in your `wp-content/plugins/` directory or equivalent.

Once installed, activate WP API Options from WordPress plugins dashboard page and you're ready to go, WP API will respond with new /options routes.


== Frequently Asked Questions ==

= Is this an official extension of WP API? =

There's no such thing.

= Will this plugin do 'X' ? =

You can submit a pull request to:
https://github.com/oleg2tor/wp-rest-api-options

== Screenshots ==

Nothing to show really, this plugin has no settings or frontend, it just extends WP API with new endpoints. It's up to you how to use them :)

== Changelog ==
= 1.0.1 =
* Fix: init function name

= 1.0.0 =
* First public release