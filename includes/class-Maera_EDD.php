<?php

/**
* The main Maera_EDD class
*/
class Maera_EDD {

	private static $instance;

	/**
	 * Get the class instance
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {

		$this->requires();
		$maera_edd_timber        = new Maera_EDD_Timber();
		$maera_edd_customizer    = new Maera_EDD_Customizer();
		$maera_edd_shortcodes    = new Maera_EDD_Shortcodes();
		$maera_edd_scripts       = new Maera_EDD_Scripts();
		$maera_edd_mods          = new Maera_EDD_Mods();
		$maera_edd_compatibility = new Maera_EDD_Compatibility();

	}

	/**
	 * Include any required files
	 */
	function requires() {

		require_once( __DIR__ . '/class-Maera_EDD_Timber.php');
		require_once( __DIR__ . '/class-Maera_EDD_Tonesque.php');
		require_once( __DIR__ . '/class-Maera_EDD_Customizer.php');
		require_once( __DIR__ . '/widgets.php');
		require_once( __DIR__ . '/class-Maera_EDD_Shortcodes.php');
		require_once( __DIR__ . '/class-Maera_EDD_Scripts.php');
		require_once( __DIR__ . '/class-Maera_EDD_Mods.php');
		require_once( __DIR__ . '/class-Maera_EDD_Compatibility.php');

	}

}
