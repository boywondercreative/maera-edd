<?php

class Maera_EDD_Shortcodes {

    function __construct() {
        add_filter( 'downloads_shortcode', array( $this, 'modify_edd_download_shortcode' ), 10, 11 );
    }


    /**
     * Filter [download] shortcode HTML
     * @since 1.0
    */
    function modify_edd_download_shortcode( $display, $atts, $buy_button, $columns, $column_width, $downloads, $excerpt, $full_content, $price, $thumbnails, $query ) {

        $button_defaults = apply_filters( 'edd_purchase_link_defaults', array() );
        $button_defaults_class = $button_defaults['class'];

        // We can't divide the grid to 5 columns, so we're setting them to 4.
        $columns = ( 5 == $columns ) ? 4 : $columns;

        if ( 1 == $columns ) {
            $column_class = '[maera_grid_col_12]';
        } else if ( 2 == $columns ) {
            $column_class = '[maera_grid_col_6]';
        } else if ( 3 == $columns ) {
            $column_class = '[maera_grid_col_4]';
        } else if ( 4 == $columns ) {
            $column_class = '[maera_grid_col_3]';
        } else if ( 6 == $columns ) {
            $column_class = '[maera_grid_col_2]';
        }

        ob_start();
        $count = 0;
        $rand = rand( 0, 999 );
        ?>

        <?php if ( 1 != $columns ) : ?>
            <style>.downloads-list .edd-grid-column-<?php echo $rand; ?>_1{clear:left;}</style>

            <div class="downloads-list">
                [maera_grid_row_open]
                    <?php

                    while ( $downloads->have_posts() ) : $downloads->the_post(); $count++;

                        $count_class = 1 < $columns ? 'edd-grid-column-' . $rand . '_' . $count : null;

                        $in_cart         = ( edd_item_in_cart( get_the_ID() ) && ! edd_has_variable_prices( get_the_ID() ) ) ? 'in-cart' : '';
                        $variable_priced = ( edd_has_variable_prices( get_the_ID() ) ) ? 'variable-priced' : '';

                        $context = Timber::get_context();
                        $context['post']             = new TimberPost( get_the_ID() );
                        $context['columns']          = $columns;
                        $context['default_image']    = new TimberImage( MAERA_EDD_URL . '/assets/images/default.png' );
                        $context['display_excerpt']  = ( $excerpt != 'no' && $full_content != 'yes' && has_excerpt() ) ? true : false;
                        $context['display_full']     = $full_content;
                        $context['display_buy_btn']  = $buy_button;
                        $context['in_cart']          = $in_cart;
                        $context['variable_priced']  = $variable_priced;
                        $context['column_class']     = $column_class;
                        $context['count_class']      = $count_class;
                        $context['count']            = $count;
                        $context['download_classes'] = array( $in_cart, $variable_priced, $column_class, $count_class, $count );
                        $context['btn_class']        = $button_defaults_class;

                        Timber::render( array( 'shortcode-download-content.twig', ), $context, apply_filters( 'maera/timber/cache', false ) );

                    endwhile;

                    wp_reset_postdata();
                echo '[maera_grid_container_close]';
            echo '</div>';
        endif; ?>

    	<nav id="downloads-shortcode" class="download-navigation">
    		<?php
    		$big = 999999;
    		echo paginate_links( array(
    			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    			'format'    => '?paged=%#%',
    			'current'   => max( 1, $query['paged'] ),
    			'total'     => $downloads->max_num_pages,
    			'prev_next' => false,
    			'show_all'  => true,
                'type'      => 'list'
    		) );
    		?>
    	</nav>
        <script>jQuery( "ul.page-numbers" ).addClass( "pagination" );</script>
        <?php

        $display = ob_get_clean();
        return $display;
    }

}
