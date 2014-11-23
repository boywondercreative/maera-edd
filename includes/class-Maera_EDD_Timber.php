<?php

/**
 * Timber customizations for Maera_EDD
 */
class Maera_EDD_Timber {

	function __construct() {
		add_filter( 'edd_template_paths',     array( $this, 'templates_path' ) );
		add_filter( 'maera/timber/locations', array( $this, 'twigs_location' ), 1 );
		add_filter( 'timber_context',         array( $this, 'timber_global_context' ) );
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

	function timber_global_context( $data ) {

		global $content_width, $maera_i18n;

		$data['default_image'] = new TimberImage( MAERA_EDD_URL . '/assets/images/default.png' );
		$data['sidebar']['header'] = Timber::get_widgets( 'header' );
		return $data;

	}

}
