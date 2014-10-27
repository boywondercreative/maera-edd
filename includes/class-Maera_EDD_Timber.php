<?php

/**
 * Timber customizations for Maera_EDD
 */
class Maera_EDD_Timber {

	function __construct() {
		add_filter( 'edd_template_paths',         array( $this, 'templates_path' ) );
		add_filter( 'maera/timber/locations',     array( $this, 'twigs_location' ), 1 );
		add_filter( 'edd_purchase_link_defaults', array( $this, 'add_button_class' ) );
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

	/**
	 * Get the button classes from the edd-button-classes.twig file
	 */
	public static function get_button_class() {

		$context = Timber::get_context();
		Timber::render( array( 'edd-button-classes.twig', ), $context, apply_filters( 'maera/timber/cache', false ) );

	}

	function add_button_class( $defaults ) {

		$defaults['class'] =  maera_get_echo( array( $this, 'get_button_class' ) );
		return $defaults;

	}

}
