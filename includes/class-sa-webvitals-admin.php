<?php

if ( ! class_exists( 'SA_WebVitals_Admin' ) ) {
	class SA_WebVitals_Admin {

		public static function init() {
			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu' );
			add_action( 'admin_init', __CLASS__ . '::settings_init' );
		}

		public static function add_admin_menu() {
			add_options_page( 'Web Vitals', 'Web Vitals', 'manage_options', 'sa-webvitals', __CLASS__ . '::options_page' );
		}

		public static function options_page() {
			?>
	  <form action="options.php" method="post">
		<h2><?php echo esc_html__( 'Web Vitals', 'sa-webvitals' ); ?></h2>
			<?php
			settings_fields( 'sa_webvitals' );
			do_settings_sections( 'sa_webvitals' );
			submit_button();
			?>
	  </form>
			<?php
		}

		public static function settings_init() {
			register_setting(
				'sa_webvitals',
				SA_WebVitals::OPTION_SETTINGS,
				array(
					'sanitize_callback' => static function ( $settings ) {
						if ( ! is_array( $settings ) ) {
							return SA_WebVitals::$DEFAULT_SETTINGS;
						}

						$all_setting_options = SA_WebVitals::setting_options();
						foreach ( $settings as $key => $value ) {
							if ( isset( $all_setting_options[ $key ] ) ) {
								if ( $key === 'enabled' ) {
									  $value = $settings[ $key ] = ( $value === '1' || $value === 'on' ) ? true : false;
								}
								if ( ! in_array( $value, $all_setting_options[ $key ] ) ) {
									$settings[ $key ] = SA_WebVitals::$DEFAULT_SETTINGS[ $key ];
								}
							} else {
								unset( $settings[ $key ] );
							}
						}

						return $settings;
					},
				)
			);

			add_settings_section(
				'sa_webvitals_general',
				// TODO: section description instead of empty string __('WEB VITALS TBD', 'sa-webvitals'),
				'',
				__CLASS__ . '::settings_general_description',
				'sa_webvitals'
			);

			add_settings_field(
				'sa_webvitals_enabled',
				__( 'Enabled', 'sa-webvitals' ),
				__CLASS__ . '::render_enabled',
				'sa_webvitals',
				'sa_webvitals_general'
			);

			add_settings_field(
				'sa_webvitals_load',
				__( 'Load', 'sa-webvitals' ),
				__CLASS__ . '::render_load',
				'sa_webvitals',
				'sa_webvitals_general'
			);

			add_settings_field(
				'sa_webvitals_integration',
				__( 'Google Analytics Integration', 'sa-webvitals' ),
				__CLASS__ . '::render_integration',
				'sa_webvitals',
				'sa_webvitals_general'
			);
		}

		public static function settings_general_description() {
			// TODO: settings description
			// echo __('This section description', 'sa');
		}

		public static function render_enabled() {
			$options         = get_option( SA_WebVitals::OPTION_SETTINGS, SA_WebVitals::$DEFAULT_SETTINGS );
			$enabled_setting = isset( $options['enabled'] ) ? $options['enabled'] : SA_WebVitals::$DEFAULT_SETTINGS['enabled'];
			?>
	  <input type="hidden" name="<?php echo SA_WebVitals::OPTION_SETTINGS; ?>[enabled]" value="0">
	  <input type="checkbox" name="<?php echo SA_WebVitals::OPTION_SETTINGS; ?>[enabled]" 
											  <?php
												if ( $enabled_setting ) :
													?>
			checked="1"<?php endif; ?>>
			<?php
		}

		public static function render_load() {
			$options      = get_option( SA_WebVitals::OPTION_SETTINGS, SA_WebVitals::$DEFAULT_SETTINGS );
			$load_setting = isset( $options['load'] ) ? $options['load'] : SA_WebVitals::$DEFAULT_SETTINGS['load'];
			$load_options = array(
				SA_WebVitals::LOAD_LOCAL => __( 'Local', 'sa-webvitals' ),
				SA_WebVitals::LOAD_CDN   => __( 'CDN', 'sa-webvitals' ),
			);
			?>
	  <select name="<?php echo SA_WebVitals::OPTION_SETTINGS; ?>[load]">
			<?php foreach ( $load_options as $option => $label ) : ?>
		  <option value="<?php echo esc_attr( $option ); ?>" 
									<?php
									if ( $load_setting === $option ) :
										?>
				selected<?php endif; ?>><?php echo esc_html( $label ); ?></option>
		<?php endforeach ?>
	  </select>
			<?php
		}

		public static function render_integration() {
			$options             = get_option( SA_WebVitals::OPTION_SETTINGS, SA_WebVitals::$DEFAULT_SETTINGS );
			$integration_setting = isset( $options['integration'] ) ? $options['integration'] : SA_WebVitals::$DEFAULT_SETTINGS['integration'];
			$integration_options = array(
				SA_WebVitals::INTEGRATION_AUTO        => __( 'Auto', 'sa-webvitals' ),
				SA_WebVitals::INTEGRATION_GTAG        => __( 'GTAG', 'sa-webvitals' ),
				SA_WebVitals::INTEGRATION_GA          => __( 'GA', 'sa-webvitals' ),
				SA_WebVitals::INTEGRATION_TAG_MANAGER => __( 'Google Tag Manager', 'sa-webvitals' ),
			);
			?>
	  <select name="<?php echo SA_WebVitals::OPTION_SETTINGS; ?>[integration]">
			<?php foreach ( $integration_options as $option => $label ) : ?>
		  <option value="<?php echo esc_attr( $option ); ?>" 
									<?php
									if ( $integration_setting === $option ) :
										?>
				selected<?php endif; ?>><?php echo esc_html( $label ); ?></option>
		<?php endforeach ?>
	  </select>
			<?php
		}
	}
}
