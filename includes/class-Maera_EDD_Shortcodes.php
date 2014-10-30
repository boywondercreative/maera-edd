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

        if ( 1 == $columns ) {
            $column_class = '[maera_grid_col_12]';
        } else if ( 2 == $columns ) {
            $column_class = '[maera_grid_col_6]';
        } else if ( 3 == $columns ) {
            $column_class = '[maera_grid_col_4]';
        } else if ( 4 == $columns || 5 == $columns ) {
            $column_class = '[maera_grid_col_3]';
        } else if ( 6 == $columns ) {
            $column_class = '[maera_grid_col_2]';
        }

        ob_start();
        $count = 0;
        ?>

        <div class="downloads-list">
            [maera_grid_row_open]
                <?php

                while ( $downloads->have_posts() ) : $downloads->the_post(); $count++;

                    $in_cart         = ( edd_item_in_cart( get_the_ID() ) && ! edd_has_variable_prices( get_the_ID() ) ) ? 'in-cart' : '';
                    $variable_priced = ( edd_has_variable_prices( get_the_ID() ) ) ? 'variable-priced' : '';

                    ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class( array( $in_cart, $variable_priced, $column_class ) ); ?>>

    				 	<?php
    				 	/**
    				 	 * Show thumbnails
    				 	*/
    				 	if ( 'false' != $thumbnails ) : ?>
    				        <div class="download-image">
                                <a title="<?php _e( 'View ', 'shop-front' ) . the_title(); ?>" href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <!-- TODO: FEATURED IMAGE -->
                                <?php else : ?>
                                    <!-- TODO: DEFAULT IMAGE -->
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>

                        <div class="download-info">
                            <a title="<?php _e( 'View ','maera-edd') . the_title(); ?>" href="<?php the_permalink(); ?>">
                                <h2><?php the_title(); ?></h2>
                            </a>
                            <?php edd_get_template_part( 'shortcode', 'content-price' ); ?>

                            <?php
    				        /**
    				         * Excerpt and Content
    				        */

                            $excerpt_length = apply_filters( 'excerpt_length', 20 );

                            if ( $excerpt != 'no' && $full_content != 'yes' && has_excerpt() ) {
                                echo '<p>' . wp_trim_words( get_the_excerpt(), $excerpt_length ) . '</p>';
                            } elseif ( $full_content == 'yes' ) {
                                the_content();
                            }

    				        /**
    				         * Buy button
    				        */
                            if ( $buy_button == 'yes' ) : ?>
                                <div class="edd_download_buy_button">
                                    <?php if ( ! edd_has_variable_prices( get_the_ID() ) ) : ?>
                                        <?php echo edd_get_purchase_link( array( 'id' => get_the_ID(), 'price' => false ) ); ?>
                                    <?php else : ?>
                                        <a class="button <?php echo $button_defaults_class; ?>" href="<?php the_permalink(); ?>">
                                            <span class="edd-add-to-cart-label"><?php _e( 'Select Option', 'maera_edd' ); ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </article>
    			<?php endwhile; ?>

    			<?php wp_reset_postdata(); ?>
            [maera_grid_container_close]
        </div>

    	<nav id="downloads-shortcode" class="download-navigation">
    		<?php
    		$big = 999999;
    		echo paginate_links( array(
    			'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
    			'format'  => '?paged=%#%',
    			'current' => max( 1, $query['paged'] ),
    			'total'   => $downloads->max_num_pages,
    			'prev_next' => false,
    			'show_all' => true,
    		) );
    		?>
    	</nav>
        <?php

        $display = ob_get_clean();
        return $display;
    }

}
