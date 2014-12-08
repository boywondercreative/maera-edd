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
        add_action( 'after_setup_theme', array( $this, 'setup' ) );

        global $content_width;
        $content_width = ( 0 == get_theme_mod( 'maera_edd_layout', 0 ) && is_active_sidebar( 'sidebar_primary' ) ) ? 1280 : 843;

		// Disable the sidebar if layout is full-width.
		if ( 0 == get_theme_mod( 'maera_edd_layout', 0 ) ) {
			add_filter( 'maera/sidebar/primary', '__return_false' );
		}

        add_theme_support( 'infinite-scroll', array(
            'container' => 'content',
            'footer' => false,
        ) );

        // Add theme support for Custom Header
        $header_args = array(
            'default-image'          => '',
            'width'                  => 0,
            'height'                 => 0,
            'flex-width'             => true,
            'flex-height'            => true,
            'uploads'                => true,
            'random-default'         => true,
            'header-text'            => true,
            'default-text-color'     => '#333333',
            'wp-head-callback'       => '',
            'admin-head-callback'    => '',
            'admin-preview-callback' => '',
        );
        add_theme_support( 'custom-header', $header_args );

        add_filter( 'maera/styles', array( $this, 'custom_header_css' ) );

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
        return $classes . ' row';
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
			'id'            => 'header',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title"',
			'after_title'   => '</h3>',
		) );

		// Remove the secondary sidebar
		unregister_sidebar( 'sidebar_secondary' );

	}

    /**
    * Maera initial setup and constants
    */
    function setup() {

        // Register wp_nav_menu() menu ( http://codex.wordpress.org/Function_Reference/register_nav_menus )
        register_nav_menus( array(
            'offcanvas' => __( 'Off-Canvas', 'maera_edd' )
        ) );

    }

    /**
     * Enqueue styles and scripts
     */
    function scripts() {

		// Foundation core
		wp_register_style( 'maera_edd_foundation', MAERA_EDD_URL . 'assets/css/foundation.css' );
		wp_enqueue_style( 'maera_edd_foundation' );

		// Foundation icons
		wp_register_style( 'maera_edd_foundation_icons', MAERA_EDD_URL . 'assets/foundation-icons/foundation-icons.css' );
		wp_enqueue_style( 'maera_edd_foundation_icons' );

		// Add Foundation required scripts
		wp_enqueue_script( 'fastclick', MAERA_EDD_URL . 'assets/vendor/fastclick.js', false );
		wp_enqueue_script( 'foundation', MAERA_EDD_URL . 'assets/foundation.min.js', 'jquery' );

		// Remove the default EDD styles
		wp_dequeue_style( 'edd-styles' );

        if ( 'isotope' == get_theme_mod( 'filter_mode', 'isotope' ) && ( is_archive( 'download' ) || is_tax('download_tag') || is_tax( 'download_category' ) ) ) {
            // Register && Enqueue Isotope
            wp_register_script( 'maera_isotope', MAERA_EDD_URL . 'assets/vendor/jquery.isotope.min.js', false, null, true );
            wp_enqueue_script( 'maera_isotope' );

            // Register && Enqueue Isotope-Sloppy-Masonry
            wp_register_script( 'maera_isotope_sloppy_masonry', MAERA_EDD_URL . 'assets/vendor/jquery.isotope.sloppy-masonry.min.js', false, null, true );
            wp_enqueue_script( 'maera_isotope_sloppy_masonry' );

			wp_enqueue_script( 'edd_script', MAERA_EDD_URL . 'assets/scripts.js', false, null, true );
			// wp_localize_script( 'maera_edd_script', 'maera_edd_script_vars', array(
			//
			//     )
			// );
        }

    }

    function custom_header_css( $styles ) {

        $custom_header = get_header_image();

        if ( $custom_header ) {
            $styles .= '.header.hero{background-image:url("' . $custom_header . '");}';
        }
        $styles .= '.header.hero{color:#' . get_theme_mod( 'header_textcolor', '333333' ) . '}';

        return $styles;

    }

}
