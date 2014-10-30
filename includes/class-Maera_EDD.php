<?php

/**
* The main Maera_EDD class
*/
class Maera_EDD {

	private static $instance;

	/**
	 * Get the class instance
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct() {

		$this->requires();
		$maera_edd_timber     = new Maera_EDD_Timber();
		$maera_edd_customizer = new Maera_EDD_Customizer();
		$maera_edd_shortcodes = new Maera_EDD_Shortcodes();

		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ), 100 );
		add_action( 'edd_purchase_link_top', array( $this, 'purchase_variable_pricing' ), 10, 1 );
		remove_action( 'edd_purchase_link_top', 'edd_purchase_variable_pricing', 10, 1 );
		add_filter( 'edd_purchase_link_defaults', array( $this, 'add_button_class' ) );

	}

	/**
	 * Include any required files
	 */
	function requires() {

		require_once( __DIR__ . '/class-Maera_EDD_Timber.php');
		require_once( __DIR__ . '/class-Maera_EDD_Customizer.php');
		require_once( __DIR__ . '/widgets.php');
		require_once( __DIR__ . '/class-Maera_EDD_Shortcodes.php');

	}

	/**
	 * Add our custom stylesheet
	 */
	function styles() {

		// Remove the default EDD styles
		// wp_dequeue_style( 'edd-styles' );

		// If EDD-Software-Specs is installed, remove its styles
		if ( class_exists( 'EDD_Software_Specs' ) ) {
			wp_dequeue_style( 'edd-software-specs' );
			wp_deregister_style( 'edd-software-specs' );
		}

		// Add our custom styles
		wp_register_style( 'maera-edd', trailingslashit( MAERA_EDD_URL ) . 'assets/css/style.css' );
		wp_enqueue_style( 'maera-edd' );

	}

	/*
	 * Convert variable prices from radio buttons to a dropdown
	 */
	function purchase_variable_pricing( $download_id ) {

		$variable_pricing = edd_has_variable_prices( $download_id );

		if ( ! $variable_pricing ) {
			return;
		}

		$prices = apply_filters( 'edd_purchase_variable_prices', edd_get_variable_prices( $download_id ), $download_id );

		$type   = edd_single_price_option_mode( $download_id ) ? 'checkbox' : 'radio';

		do_action( 'edd_before_price_options', $download_id );

		echo '<div class="edd_price_options">';
			if ( $prices ) {
				echo '<select name="edd_options[price_id][]">';
				foreach ( $prices as $key => $price ) {
					printf(
						'<option for="%3$s" name="edd_options[price_id][]" id="%3$s" class="%4$s" value="%5$s" %7$s> %6$s</option>',
						checked( 0, $key, false ),
						$type,
						esc_attr( 'edd_price_option_' . $download_id . '_' . $key ),
						esc_attr( 'edd_price_option_' . $download_id ),
						esc_attr( $key ),
						esc_html( $price['name'] . ' - ' . edd_currency_filter( edd_format_amount( $price[ 'amount' ] ) ) ),
						selected( isset( $_GET['price_option'] ), $key, false )
					);
					do_action( 'edd_after_price_option', $key, $price, $download_id );
				}
				echo '</select>';
			}
			do_action( 'edd_after_price_options_list', $download_id, $prices, $type );

		echo '</div><!--end .edd_price_options-->';
		do_action( 'edd_after_price_options', $download_id );

	}

	/**
	 * Add the shell button classes to EDD buttons
	 */
	function add_button_class( $defaults ) {

		$button_size  = get_theme_mod( 'edd_button_size', 'large' );
		$button_color = get_theme_mod( 'edd_button_color', 'primary' );

		$defaults['class'] =  '[maera_button_' . $button_color . '_' . $button_size . ']';
		return $defaults;

	}

}
