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

// Early exit if EDD is not installed.
if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
	return;
}

/**
 * Add the /templates folder for our custom templates
 */
function maera_edd_templates_path( $file_paths ) {

	$file_paths[50] = dirname( __FILE__ ) . '/templates';
	return $file_paths;

}
add_filter( 'edd_template_paths', 'maera_edd_templates_path' );

/**
 * Add the /views folder for our custom twigs
 */
function maera_edd_twigs_location( $locations ) {

	$locations[] = dirname( __FILE__ ) . '/views';
	return $locations;

}
add_filter( 'maera/timber/locations', 'maera_edd_twigs_location', 1 );