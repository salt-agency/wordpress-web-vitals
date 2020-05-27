=== Web Vitals ===
Contributors: saltagency
Tags: ga, analytics, tagmanager, gtag, performance, webvitals
Requires at least: 5.0
Requires PHP: 7.1
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Send [Web Vitals](https://web.dev/vitals/) to Google Analytics.

== Description ==
Send [Web Vitals](https://web.dev/vitals/) to Google Analytics.

This plugin does not embed or include GA/GTAG/GTM on to your site, it uses existing integration to submit Google Analytics events collected through javascript Web Vitals script.

Integrates with:

* GA  (analytics.js)
* GTAG
* Google Tag Manager

By default method to send web vitals is picked automatically, if you do not recieve events in Analytics, select your analytics integration from the plugin options.

Web Vitals script can be either loaded from CDN or locally.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/sa-webvitals` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the \'Plugins\' screen in WordPress
2. Use the Settings->`Web Vitals` screen to configure the plugin

== Changelog ==
= 0.1.0 =
* First release
