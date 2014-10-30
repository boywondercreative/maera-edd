<?php

/**
 * Timber customizations for Maera_EDD
 */
class Maera_EDD_Timber {

	function __construct() {
		add_filter( 'edd_template_paths',         array( $this, 'templates_path' ) );
		add_filter( 'maera/timber/locations',     array( $this, 'twigs_location' ), 1 );
	}
	/**
	 * Add the /templates folder for our custom templates
	 */
	function templates_path( $file_paths ) {

		$file_paths[50] = MAERA_EDD_PATH . '/templates';
		ksort( $file_paths, SORT_NUMERIC );

		return $file_paths;

	}

	/**
	 * Add the /views folder for our custom twigs
	 */
	function twigs_location( $locations ) {

		$locations[] = MAERA_EDD_PATH . '/views';
		return $locations;

	}

}
