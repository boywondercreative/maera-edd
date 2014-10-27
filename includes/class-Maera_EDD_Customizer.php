<?php

/**
 * 
 */
class Maera_EDD_Customizer {
	
	function __construct() {

		add_action( 'customize_register', array( $this, 'create_section' ) );
		add_filter( 'kirki/controls', array( $this, 'create_settings' ) );

	}

	/*
	 * Create the section
	 */
	function create_section( $wp_customize ) {

		$wp_customize->add_section( 'maera_edd', array(
			'title'    => __( 'Easy Digital Downloads', 'maera_edd' ),
			'priority' => 999,
		) );

	}

	/**
	 * Create the customizer controls.
	 * Depends on the Kirki Customizer plugin.
	 */
	function create_settings( $controls ) {

		$controls[] = array(
			'type'        => 'image',
			'setting'     => 'edd_default_image',
			'label'       => __( 'Default image for downloads', 'maera_edd' ),
			'subtitle' => __( 'Upload an image that will be used in case the download does not have a featured image assigned to it.', 'maera_bootstrap' ),
			'section'     => 'maera_edd',
			'priority'    => 10,
			'default'     => null
		);

		return $controls;

	}

}
