<?php

/**
 *
 */
class Maera_EDD_Customizer {

	function __construct() {

		add_action( 'customize_register', array( $this, 'create_section' ) );
		add_filter( 'kirki/controls', array( $this, 'create_settings' ) );

		add_action( 'customize_save_after',array( $this, 'sync_edd_colors_options' ) );
		add_action( 'wp',array( $this, 'sync_edd_colors_theme_mod' ) );

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
			'type'     => 'radio',
			'mode'     => 'image',
			'setting'  => 'maera_edd_layout',
			'label'    => __( 'Layout', 'maera_edd' ),
			'section'  => 'maera_edd',
			'priority' => 1,
			'default'  => 0,
			'choices'  => array(
				0 => get_template_directory_uri() . '/assets/images/2cr.png',
				1 => get_template_directory_uri() . '/assets/images/2cl.png',
			),
		);

		$controls[] = array(
			'type'     => 'select',
			'setting'  => 'checkout_color',
			'label'    => __( 'Button color', 'maera_edd' ),
			'subtitle' => __( 'Select the button color for the purchase/buynow button. Please note that this change will be applied after you save the options (no live-preview available).', 'maera_edd' ),
			'section'  => 'maera_edd',
			'priority' => 12,
			'default'  => 'primary',
			'choices'  => array(
				'white'     => __( 'White', 'edd' ),
				'gray'      => __( 'Gray', 'edd' ),
				'blue'      => __( 'Blue', 'edd' ),
				'red'       => __( 'Red', 'edd' ),
				'green'     => __( 'Green', 'edd' ),
				'yellow'    => __( 'Yellow', 'edd' ),
				'orange'    => __( 'Orange', 'edd' ),
				'dark-gray' => __( 'Dark Gray', 'edd' ),
			),

		);

		$controls[] = array(
			'type'     => 'checkbox',
			'setting'  => 'edd_variables_dropdown',
			'label'    => __( 'Replace variables radio select with a dropdown', 'maera_edd' ),
			'section'  => 'maera_edd',
			'default'  => 0,
			'priority' => 1,
		);

		return $controls;

	}

	function sync_edd_colors_options() {
		global $edd_options;

		$checkout_color = get_theme_mod( 'checkout_color' );

		if ( $checkout_color != $edd_options['checkout_color'] ) {
			$edd_options['checkout_color'] = $checkout_color;
			update_option( 'edd_settings', $edd_options );
		}
	}

	function sync_edd_colors_theme_mod() {
		global $edd_options;

		$checkout_color = get_theme_mod( 'checkout_color' );

		if ( $checkout_color != $edd_options['checkout_color'] ) {
			set_theme_mod( 'checkout_color', $edd_options['checkout_color'] );
		}
	}

}
