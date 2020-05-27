<?php

/**
 * Plugin Name: Web Vitals
 * Plugin URI: https://wordpress.org/plugins/sa-webvitals
 * Description: Send Web Vitals to GA
 * Version: 0.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author: SALT.agency
 * Author URI: https://salt.agency
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sa-webvitals
 *
 * @package sa-webvitals
 */

require_once __DIR__ . '/includes/class-sa-webvitals.php';

SA_WebVitals::init();

if (is_admin()) {
  require_once __DIR__ . '/includes/class-sa-webvitals-admin.php';
  SA_WebVitals_Admin::init();
}
