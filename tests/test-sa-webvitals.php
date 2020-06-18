<?php

/**
 * Class SampleTest
 *
 * @package Web_Vitals
 */

/**
 * Sample test case.
 */
class SA_WebVitalsTest extends WP_UnitTestCase
{

	public function setUp()
	{
		$this->plugin_dir_url = plugin_dir_url(__DIR__);
	}

	/**
	 * Dummy tests to ensure wp/php compatibility
	 */
	public function test_load_local()
	{
		// AUTO
		ob_start();
		SA_WebVitals::display_load_local_scripts(['integration' => SA_WebVitals::INTEGRATION_AUTO]);
		$this->assertStringContainsString('<meta name="webvitals:sink" content="auto">', ob_get_clean());

		// GA
		ob_start();
		SA_WebVitals::display_load_local_scripts(['integration' => SA_WebVitals::INTEGRATION_GA]);
		$this->assertStringContainsString('<meta name="webvitals:sink" content="ga">', ob_get_clean());

		// GTAG
		ob_start();
		SA_WebVitals::display_load_local_scripts(['integration' => SA_WebVitals::INTEGRATION_GTAG]);
		$this->assertStringContainsString('<meta name="webvitals:sink" content="gtag">', ob_get_clean());

		// Google Tag Manager
		ob_start();
		SA_WebVitals::display_load_local_scripts(['integration' => SA_WebVitals::INTEGRATION_TAG_MANAGER]);
		$this->assertStringContainsString('<meta name="webvitals:sink" content="tagmanager">', ob_get_clean());
	}

	public function test_load_cdn()
	{
		// CDN
		ob_start();
		SA_WebVitals::display_load_cdn_scripts();
		$this->assertStringContainsString('<script', ob_get_clean());

		// AUTO
		ob_start();
		SA_WebVitals::display_load_cdn_footer(['integration' => SA_WebVitals::INTEGRATION_AUTO]);
		$this->assertStringContainsString('<script>', ob_get_clean());

		// GA
		ob_start();
		SA_WebVitals::display_load_cdn_footer(['integration' => SA_WebVitals::INTEGRATION_GA]);
		$this->assertStringContainsString('<script>', ob_get_clean());

		// GTAG
		ob_start();
		SA_WebVitals::display_load_cdn_footer(['integration' => SA_WebVitals::INTEGRATION_GTAG]);
		$this->assertStringContainsString('<script>', ob_get_clean());

		// Google Tag Manager
		ob_start();
		SA_WebVitals::display_load_cdn_footer(['integration' => SA_WebVitals::INTEGRATION_TAG_MANAGER]);
		$this->assertStringContainsString('<script>', ob_get_clean());
	}
}
