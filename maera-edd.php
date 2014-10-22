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
	ksort( $file_paths, SORT_NUMERIC );

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

/**
 * Displays a formatted price for a download
 *
 * @since 1.0
 * @param int $download_id ID of the download price to show
 * @param bool $echo Whether to echo or return the results
 * @return void
 */
function maera_get_edd_price( $download_id = 0 ) {

	if( empty( $download_id ) ) {
		$download_id = get_the_ID();
	}

	if ( edd_has_variable_prices( $download_id ) ) {

		$prices = edd_get_variable_prices( $download_id );

		// Return the lowest price
		$i = 0;
		foreach ( $prices as $key => $value ) {

			if( $i < 1 ) {
				$price = $value['amount'];
			}

			if ( (float) $value['amount'] < (float) $price ) {

				$price = (float) $value['amount'];

			}
			$i++;
		}

		$price = edd_sanitize_amount( $price );

	} else {

		$price = edd_get_download_price( $download_id );

	}

	return $price;

}