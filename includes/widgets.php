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

class Maera_EDD_Download_Meta extends WP_Widget {

    private $fields = array(
        'title' => 'Title (optional)',
    );

    function __construct() {

        $widget_ops = array(
            'classname'   => 'widget_maera_edd',
            'description' => __( 'Use this widget to add meta details for single products', 'maera_edd' )
        );

        $this->WP_Widget( 'widget_maera_edd', __( 'Maera EDD: Download meta', 'maera_edd' ), $widget_ops );
        $this->alt_option_name = 'widget_maera_edd';

        add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
        add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
        add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
    }

    function widget( $args, $instance ) {
        global $post, $ss_edd, $ss_framework;

        if ( is_singular( 'download' ) ) {

            $cache = wp_cache_get( 'widget_maera_edd', 'widget' );
            $cache = ( ! is_array( $cache ) ) ? array() : $cache;

            $args['widget_id'] = ( ! isset( $args['widget_id'] ) ) ? null : $args['widget_id'];

            if ( isset( $cache[$args['widget_id']] ) ) {
                echo $cache[$args['widget_id']];
            }

        } else {
            // Do not show the widget if we're not on a single download.
            return;
        }

        ob_start();

        extract( $args, EXTR_SKIP );

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Product Details', 'maera_edd' ) : $instance['title'], $instance, $this->id_base );

        foreach( $this->fields as $name => $label ) {

            if ( ! isset( $instance[$name] ) ) {
                $instance[$name] = '';
            }

        }

        echo $before_widget;
        echo ( $title ) ? $before_title . $title . $after_title : '';

        $button_args = apply_filters( 'edd_purchase_link_defaults', array(
            'download_id' => $post->ID,
            'price'       => (bool) false,
        ) );

        $price = edd_price( $post->ID, false );

        if ( edd_has_variable_prices( $post->ID ) ) {

            $low   = edd_get_lowest_price_option( $post->ID );
            $high  = edd_get_highest_price_option( $post->ID );

            // Check if both high and low are the same.
            // This can be true if for example we have 2 variations with the same price
            // but one of them is recurring while the other is not.
            // In this case, only show one of the 2 prices and not a range.
            if ( $low != $high ) {
                $price = __( 'From ', 'maera_edd' ) . edd_price( $post->ID, false );
            }

        }

        if ( has_post_thumbnail( $post->ID ) ) {
            the_post_thumbnail();
        }

        echo '<h3 style="text-align: center">' . $price . '</h3>';

        echo edd_get_purchase_link( $button_args ); ?>

        <?php if ( ! get_post_meta( $post->ID, 'edd_coming_soon', true ) ) : ?>

            <table class="table table-striped table-bordered" style="margin-top: 2em;">
                <?php
                // Number of Downloads
                ?>
                <tr>
                    <td><i class="dashicons dashicons-chart-area"></i> <?php _e( 'Downloads', 'maera_edd' ); ?></td>
                    <td><?php echo edd_get_download_sales_stats( $post->ID ); ?></td>
                </tr>

                <?php if ( !class_exists( 'EDD_Software_Specs' ) ) : ?>
                    <?php
                    // Created Date
                    ?>
                    <tr>
                        <td><i class="dashicons dashicons-calendar"></i> <?php _e( 'Created', 'maera_edd' ); ?></td>
                        <td><?php echo get_the_date(); ?></td>
                    </tr>

                    <?php
                    // Updated Date
                    ?>
                    <?php if ( get_the_date() != get_the_modified_date() ) : ?>
                        <tr>
                            <td><i class="dashicons dashicons-calendar"></i> <?php _e( 'Last Modified', 'maera_edd' ); ?></td>
                            <td><?php echo get_the_modified_date(); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>

                <?php
                // Software Specs
                ?>
                <?php if ( class_exists( 'EDD_Software_Specs' ) ) :
                    $isa_curr = empty($pc) ? 'USD' : $pc;
                    $eddchangelog_version = get_post_meta( $post->ID, '_edd_sl_version', TRUE );

                    if ( empty( $eddchangelog_version ) )
                    $vKey = '_smartest_currentversion';
                    else
                    $vKey = '_edd_sl_version';


                    $sVersion = get_post_meta($post->ID, $vKey, true);
                    $appt = get_post_meta($post->ID, '_smartest_apptype', true);
                    $filt = get_post_meta($post->ID, '_smartest_filetype', true);
                    $fils = get_post_meta($post->ID, '_smartest_filesize', true);
                    $reqs = get_post_meta($post->ID, '_smartest_requirements', true);
                    ?>

                    <tr>
                        <td><i class="dashicons dashicons-calendar-alt"></i> <?php _e( 'Release date:', 'edd-specs' ); ?></td>
                        <td>
                            <meta itemprop="datePublished" content="<?php echo get_post_time('Y-m-d', false, $post->ID); ?>">
                            <?php echo get_post_time('F j, Y', false, $post->ID, true); ?>
                        </td>
                    </tr>

                    <?php if ( $sVersion ) : ?>
                        <tr>
                            <td><i class="dashicons dashicons-flag"></i> <?php _e( 'Current version:', 'edd-specs' ); ?></td>
                            <td itemprop="softwareVersion"><?php echo $sVersion; ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ( $appt ) : ?>
                        <tr>
                            <td><i class="dashicons dashicons-portfolio"></i> <?php _e( 'Type:', 'maera_edd' ); ?></td>
                            <td itemprop="applicationCategory"><?php echo $appt; ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ( $filt ) : ?>
                        <tr>
                            <td><i class="dashicons dashicons-media-default"></i> <?php _e( 'File format:', 'edd-specs' ); ?></td>
                            <td itemprop="fileFormat"><?php echo $filt; ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ( $fils ) : ?>
                        <tr>
                            <td><i class="dashicons dashicons-admin-generic"></i> <?php _e( 'File size:', 'edd-specs' ); ?></td>
                            <td itemprop="fileSize"><?php echo $fils; ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ( $reqs ) : ?>
                        <tr>
                            <td><i class="dashicons dashicons-editor-ol"></i> <?php _e( 'Requirements:', 'edd-specs' ); ?></td>
                            <td itemprop="requirements"><?php echo $reqs; ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if ( $price ) : ?>
                        <tr itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                            <td><i class="dashicons dashicons-money"></i> <?php _e( 'Price:', 'edd-specs' ); ?></td>
                            <td>
                                <span><?php echo $price; ?></span><span itemprop="priceCurrency"><?php echo $isa_curr; ?></span>
                            </td>
                        </tr>

                        <?php do_action( 'eddss_add_specs_table_row' ); ?>
                    <?php endif; ?>

                <?php endif; ?>

            </table>
        <?php endif;
        echo $after_widget;
        $cache[$args['widget_id']] = ob_get_flush();
        wp_cache_set( 'widget_maera_edd', $cache, 'widget' );
    }

    function update( $new_instance, $old_instance ) {
        $instance = array_map( 'strip_tags', $new_instance );
        $this->flush_widget_cache();
        $alloptions = wp_cache_get( 'alloptions', 'options' );

        if ( isset( $alloptions['widget_maera_edd'] ) ) {
            delete_option('widget_maera_edd');
        }

        return $instance;
    }

    function flush_widget_cache() {
        wp_cache_delete( 'widget_maera_edd', 'widget' );
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
    register_widget( 'Maera_EDD_Download_Meta' );
}
add_action( 'widgets_init', 'maera_edd_register_widgets' );

// Remove the default EDD Software Specs output from the bottom of the download.
if ( class_exists( 'EDD_Software_Specs' ) ) {
    $EDD_Software_Specs = EDD_Software_Specs::get_instance();
    remove_action( 'edd_after_download_content', array( $EDD_Software_Specs, 'specs' ), 30 );
}
