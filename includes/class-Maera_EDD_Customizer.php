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
			'type'     => 'select',
			'setting'  => 'edd_button_size',
			'label'    => __( 'Button size', 'maera_edd' ),
			'subtitle' => __( 'Select the button size for the purchase/buynow button', 'maera_edd' ),
			'section'  => 'maera_edd',
			'priority' => 10,
			'default'  => 'large',
			'choices'  => array(
				'extra_small' => __( 'Extra Small', 'maera_edd' ),
				'small'       => __( 'Small', 'maera_edd' ),
				'medium'      => __( 'Medium', 'maera_edd' ),
				'large'       => __( 'Large', 'maera_edd' ),
				'extra_large' => __( 'Extra Large', 'maera_edd' )
			),

		);

		$controls[] = array(
			'type'     => 'select',
			'setting'  => 'edd_button_color',
			'label'    => __( 'Button color', 'maera_edd' ),
			'subtitle' => __( 'Select the button color for the purchase/buynow button', 'maera_edd' ),
			'section'  => 'maera_edd',
			'priority' => 12,
			'default'  => 'primary',
			'choices'  => array(
				'default' => 1,
				'primary' => 2,
				'success' => 3,
				'info'    => 3,
				'warning' => 4,
				'danger'  => 5,
				'link'    => 6,
			),

		);

		return $controls;

	}

}
