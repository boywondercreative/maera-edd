<?php

/**
 * 
 */
class Maera_EDD_Customizer {
	
	function __construct() {

		add_action( 'customize_register', array( $this, 'create_section' ) );

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

}
