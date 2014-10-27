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
		$maera_edd_timber = new Maera_EDD_Timber();


		add_action( 'wp_enqueue_scripts',         array( $this, 'remove_default_edd_styles' ) );

		add_action( 'edd_purchase_link_top',      array( $this, 'purchase_variable_pricing' ), 10, 1 );
		remove_action( 'edd_purchase_link_top',  'edd_purchase_variable_pricing', 10, 1 );

	}

	/**
	 * Include any required files
	 */
	function requires() {

		require_once( __DIR__ . '/class-Maera_EDD_Timber.php');

	}

	/**
	 * Remove the default EDD styles
	 */
	function remove_default_edd_styles() {

		wp_dequeue_style( 'edd-styles' );

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

}
