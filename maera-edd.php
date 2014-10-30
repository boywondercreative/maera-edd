<?php
/*
Plugin Name:         Maera EDD
Plugin URI:
Description:         Add support for Easy Digital Downloads to the Maera Framework
Version:             1.0-dev
Author:              Aristeides Stathopoulos, Dimitris Kalliris
Author URI:          http://press.codes
*/


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'MAERA_EDD_PATH' ) ) {
	define( 'MAERA_EDD_PATH', dirname( __FILE__ ) );
}

if ( ! defined( 'MAERA_EDD_URL' ) ) {
	define( 'MAERA_EDD_URL', plugin_dir_url( __FILE__ ) );
}

// Load our Maera_EDD class if EDD is installed
if ( class_exists( 'Easy_Digital_Downloads' ) ) {

	require_once( __DIR__ . '/includes/class-Maera_EDD.php');
	Maera_EDD::get_instance();

}