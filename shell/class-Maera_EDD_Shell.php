<?php

class Maera_EDD_Shell {

    private static $instance;

    public function __construct() {

        if ( ! defined( 'MAERA_SHELL_PATH' ) ) {
            define( 'MAERA_SHELL_PATH', dirname( __FILE__ ) );
        }

        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

    }

    /**
     * Singleton
     */
    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    function scripts() {

        wp_register_style( 'maera_edd_foundation', MAERA_EDD_URL . '/assets/css/foundation.min.css' );
        wp_enqueue_style( 'maera_edd_foundation' );

    }

}
