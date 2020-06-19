=== Web Vitals ===
Contributors: saltagency
Tags: ga, analytics, tagmanager, gtag, performance, webvitals
Requires at least: 5.1
Tested up to: 5.4
Requires PHP: 7.2
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

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
2. Activate the plugin through the \'Plugins\' screen in WordPress
3. Use the Settings->`Web Vitals` screen to configure the plugin
4. Add Google Analytics Report to your GA Property - https://analytics.google.com/analytics/gallery/#posts/search/%3F_.tab%3DMy%26_.start%3D0%26_.viewId%3DdpAv7JWZQiKpz7TpABrcxg/

== Changelog ==
= 0.1.0 =
* First release

= 0.1.1 =
* Changed plugin filename to web-vitals.php to reflect https://wordpress.org/plugins/web-vitals/.

= 0.1.2 =
* Fixed CDN loading