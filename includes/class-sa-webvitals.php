<?php

if ( ! class_exists( 'SA_WebVitals' ) ) {
	class SA_WebVitals {

		const OPTION_SETTINGS = 'sa_webvitals_settings';

		const LOAD_CDN   = 'cdn';
		const LOAD_LOCAL = 'local';

		const INTEGRATION_AUTO        = 'auto';
		const INTEGRATION_GA          = 'ga';
		const INTEGRATION_GTAG        = 'gtag';
		const INTEGRATION_TAG_MANAGER = 'tagmanager';

		public static $DEFAULT_SETTINGS = array(
			'enabled'     => true,
			'load'        => 'local',
			'integration' => 'auto',
		);

		const CDN_URL = 'https://unpkg.com/web-vitals@0.2.1/dist/web-vitals.es5.umd.min.js';

		public static function init() {
			add_action( 'wp_head', __CLASS__ . '::wp_head' );
		}

		public static function get_settings() {
			return get_option( static::OPTION_SETTINGS, static::$DEFAULT_SETTINGS );
		}

		public static function setting_options() {
			return array(
				'enabled'     => array( false, true ),
				'load'        => array( static::LOAD_LOCAL, static::LOAD_CDN ),
				'integration' => array( static::INTEGRATION_AUTO, static::INTEGRATION_GTAG, static::INTEGRATION_GA, static::INTEGRATION_TAG_MANAGER ),
			);
		}

		// We are using wp_head to load defer script in head
		public static function wp_head() {
			$settings = static::get_settings();
			if ( $settings['enabled'] ) {
				if ( $settings['load'] === static::LOAD_CDN ) {
					static::display_load_cdn_scripts();
					add_action( 'wp_footer', __CLASS__ . '::wp_footer', 20 );
					
				} else /* static::LOAD_LOCAL */ {
					static::display_load_local_scripts($settings);
				}
			}
		}

		public static function display_load_cdn_scripts() {
			echo '<script defer src="', esc_url( static::CDN_URL ), '"></script>';
		}

		public static function display_load_local_scripts($settings) {
			echo '<meta name="webvitals:sink" content="', esc_attr( $settings['integration'] ), '">';
			echo '<script defer src="', esc_url( plugins_url( '/public/js/web-vitals.0.2.1.es5.umd.min.js', __DIR__ ) ), '"></script>';
		}


		public static function wp_footer() {
			$settings    = static::get_settings();
			static::display_load_cdn_footer($settings);
		}

		// We know we are loading web vitals
		// TODO: option to have non lined scripts
		public static function display_load_cdn_footer($settings) {
			$integration = $settings['integration'];
			// TODO : make configurable
			$gtm_variable_name = 'dataLayer';
			?>
	  <script>
		addEventListener('DOMContentLoaded', function sa_webvitals_init() {
			<?php if ( $integration === static::INTEGRATION_AUTO || $integration === static::INTEGRATION_GA ) : ?>
			var sendto_<?php echo static::INTEGRATION_GA; ?> = function(vitals) {
			  if (!window.ga) {
				window.ga || function() {
				  (ga.q = ga.q || []).push(arguments)
				};
				ga.l = +new Date;
			  }
			  ga('send', 'event', {
				eventCategory: 'Web Vitals',
				eventAction: vitals.name,
				eventValue: Math.round(vitals.name === 'CLS' ? vitals.delta * 1000 : vitals.delta),
				eventLabel: vitals.id,
				nonInteraction: true,
			  });
			};
		  <?php endif; ?>
			<?php if ( $integration === static::INTEGRATION_AUTO || $integration === static::INTEGRATION_GTAG ) : ?>
			var sendto_<?php echo static::INTEGRATION_GTAG; ?> = function(vitals) {
			  window.dataLayer = window.dataLayer || [];
			  window.gtag = window.gtag || function gtag() {
				dataLayer.push(arguments);
			  };
			  gtag('event', vitals.name, {
				event_category: 'Web Vitals',
				value: Math.round(vitals.name === 'CLS' ? vitals.delta * 1000 : vitals.delta),
				event_label: vitals.id,
				non_interaction: true,
			  });
			};
		  <?php endif; ?>
			<?php if ( $integration === static::INTEGRATION_AUTO || $integration === static::INTEGRATION_TAG_MANAGER ) : ?>
			var sendto_<?php echo static::INTEGRATION_TAG_MANAGER; ?> = function(vitals) {
			  window.<?php echo $gtm_variable_name; ?> = window.<?php echo $gtm_variable_name; ?> || [];
				<?php echo $gtm_variable_name; ?>.push({
				event: 'web-vitals',
				event_category: 'Web Vitals',
				event_action: vitals.name,
				event_value: Math.round(vitals.name === 'CLS' ? vitals.delta * 1000 : vitals.delta),
				event_label: vitals.id
			  });
			};
		  <?php endif; ?>
			<?php if ( $integration === static::INTEGRATION_AUTO ) : ?>
			var sendto_<?php echo static::INTEGRATION_AUTO; ?> = function(vitals) {
			  if (window.gtag) {
				sendto_<?php echo static::INTEGRATION_GTAG; ?>(vitals);
			  } else if (window.<?php echo $gtm_variable_name; ?>) {
				sendto_<?php echo static::INTEGRATION_TAG_MANAGER; ?>(vitals);
			  } else if (window.ga) {
				sendto_<?php echo static::INTEGRATION_GA; ?>(vitals);
			  } else {
				console.warn('WP WebVitals could not detect GA/GTAG/TagManager.');
			  }
			};
		  <?php endif; ?>
		  if (webVitals) {
			webVitals.getCLS(sendto_<?php echo $integration; ?>);
			webVitals.getFID(sendto_<?php echo $integration; ?>);
			webVitals.getLCP(sendto_<?php echo $integration; ?>);
		  } else {
			console.warn('WebVitals script is not loaded');
		  }
		});
	  </script>
			<?php
		}
	}
}
