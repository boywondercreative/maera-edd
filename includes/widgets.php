<?php

class Maera_EDD_Buy_Button extends WP_Widget {

    /** Constructor */
    function __construct() {
        parent::__construct( 'maera_edd_buy_button', __( 'Maera-EDD Buy Button', 'maera_edd' ), array( 'description' => __( 'Display the buy button on single downloads.', 'maera_edd' ) ) );
    }

    /** @see WP_Widget::widget */
    function widget( $args, $instance ) { ?>
        <?php if ( is_singular( 'download' ) ) : ?>
            <div class="sidebar-buy-button">
                <?php echo edd_get_purchase_link(); ?>
            </div>
        <?php endif;
    }
}

/**
 * Register Widgets
 *
 * Registers the EDD Widgets.
 *
 * @since 1.0
 * @return void
 */
function maera_edd_register_widgets() {
    register_widget( 'Maera_EDD_Buy_Button' );
}
add_action( 'widgets_init', 'maera_edd_register_widgets' );
