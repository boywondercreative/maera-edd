<?php

class Maera_EDD_Shell {

    private static $instance;

    public function __construct() {

        // Define the shell path to be used for views etc.
        if ( ! defined( 'MAERA_SHELL_PATH' ) ) {
            define( 'MAERA_SHELL_PATH', MAERA_EDD_PATH );
        }

        add_filter( 'maera/section_class/wrapper', array( $this, 'wrapper_class' ) );
        add_filter( 'maera/section_class/content', array( $this, 'content_class' ) );
        add_filter( 'maera/section_class/primary', array( $this, 'sidebar_class' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

        global $content_width;
        $content_width = ( 0 == get_theme_mod( 'maera_edd_layout', 0 ) && is_active_sidebar( 'sidebar_primary' ) ) ? 1280 : 843;

		// Disable the sidebar if layout is full-width.
		if ( 0 == get_theme_mod( 'maera_edd_layout', 0 ) ) {
			add_filter( 'maera/sidebar/primary', '__return_false' );
		}

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

    /**
     * column classes for main content
     * depending on whether the primary sidebar has any widgets in it or not.
     */
    function wrapper_class( $classes ) {
        return ( is_active_sidebar( 'sidebar_primary' ) ) ? $classes . ' row' : $classes;
    }

    /**
     * column classes for main content
     * depending on whether the primary sidebar has any widgets in it or not.
     */
    function content_class( $classes ) {
		$layout    = get_theme_mod( 'maera_edd_layout', 1 );
        $alignment = ( 2 == get_theme_mod( 'maera_edd_layout' ) ) ? ' right' : null;
        $columns   = ' small-12 columns';
		$columns  .= ( 0 == $layout ) ? ' large-12' : ' large-8';
		if ( edd_is_checkout() ) {
			$columns = ' small-12 medium-10 medium-offset-1 large-6 large-offset-3 columns';
		}
        return ( is_active_sidebar( 'sidebar_primary' ) ) ? $classes . $columns . $alignment : $classes;
    }

    /**
     * column classes for main content
     * depending on whether the primary sidebar has any widgets in it or not.
     */
    function sidebar_class( $classes ) {
        return ( is_active_sidebar( 'sidebar_primary' ) ) ? $classes . ' small-12 large-4 columns' : $classes;
    }


	/**
	* Register sidebars
	*/
	function widgets_init() {

		$class        = apply_filters( 'maera/widgets/class', '' );
		$before_title = apply_filters( 'maera/widgets/title/before', '<h3 class="widget-title">' );
		$after_title  = apply_filters( 'maera/widgets/title/after', '</h3>' );

		register_sidebar( array(
			'name'          => __( 'Header', 'maera_edd' ),
			'id'            => 'sidebar_header',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title"',
			'after_title'   => '</h3>',
		) );

		// Remove the secondary sidebar
		unregister_sidebar( 'sidebar_secondary' );

	}

    /**
     * Enqueue styles and scripts
     */
    function scripts() {

		// Foundation core
		wp_register_style( 'maera_edd_foundation', MAERA_EDD_URL . '/assets/css/foundation.css' );
		wp_enqueue_style( 'maera_edd_foundation' );

		// Foundation icons
		wp_register_style( 'maera_edd_foundation_icons', MAERA_EDD_URL . '/assets/foundation-icons/foundation-icons.css' );
		wp_enqueue_style( 'maera_edd_foundation_icons' );

		// Add Foundation required scripts
		wp_enqueue_script( 'fastclick', MAERA_EDD_URL . '/assets/vendor/fastclick.js', false );
		wp_enqueue_script( 'foundation', MAERA_EDD_URL . '/assets/foundation.min.js', 'jquery' );

		// Remove the default EDD styles
		wp_dequeue_style( 'edd-styles' );

    }

}
