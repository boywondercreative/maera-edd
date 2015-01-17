<?php
/*
Plugin Name:         Maera EDD
Plugin URI:
Description:         Add support for Easy Digital Downloads to the Maera Framework
Version:             1.0-dev
Author:              Aristeides Stathopoulos, Dimitris Kalliris
Author URI:          https://press.codes
Text Domain:         maera_edd
*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

		if ( ! defined( 'MAERA_EDD_FILE' ) ) { define( 'MAERA_EDD_FILE', __FILE__ ); }
		if ( ! defined( 'MAERA_EDD_PATH' ) ) { define( 'MAERA_EDD_PATH', dirname( __FILE__ ) ); }
		if ( ! defined( 'MAERA_EDD_URL' ) ) { define( 'MAERA_EDD_URL', plugin_dir_url( __FILE__ ) ); }

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

		require_once( __DIR__ . '/includes/class-Maera_EDD_Timber.php');
		// require_once( __DIR__ . '/includes/class-Maera_EDD_Tonesque.php');
		require_once( __DIR__ . '/includes/class-Maera_EDD_Customizer.php');
		require_once( __DIR__ . '/includes/widgets.php');
		require_once( __DIR__ . '/includes/class-Maera_EDD_Shortcodes.php');
		require_once( __DIR__ . '/includes/class-Maera_EDD_Scripts.php');
		require_once( __DIR__ . '/includes/class-Maera_EDD_Mods.php');
		require_once( __DIR__ . '/includes/class-Maera_EDD_Compatibility.php');

	}

}

// Load our Maera_EDD class if EDD is installed
if ( class_exists( 'Easy_Digital_Downloads' ) ) {
	Maera_EDD::get_instance();
}
