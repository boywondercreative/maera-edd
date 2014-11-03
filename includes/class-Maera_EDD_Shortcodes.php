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

                        ?>

                        <?php
                        if ( has_post_thumbnail() ) {
                            $image = new Maera_EDD_Tonesque( get_the_ID() );
                            $image = $image->colors();
                        } ?>

                        <figure id="post-<?php the_ID(); ?>" <?php post_class( array( $in_cart, $variable_priced, $column_class, $count_class, 'eddgc' . $count, 'effect-goliath' ) ); ?>>
                            <div class="inside" style="background:<?php echo $image['color']; ?>;">
            				 	<?php if ( 'false' != $thumbnails ) : ?>
                                    <a title="<?php _e( 'View ', 'shop-front' ) . the_title(); ?>" href="<?php the_permalink(); ?>">
                                        <?php
                                        $context = Timber::get_context();
                                        $context['post']   = new TimberPost( get_the_ID() );
                                        $context['columns'] = $columns;
                                        $context['default_image'] = new TimberImage( MAERA_EDD_URL . '/assets/images/default.png' );

                                        Timber::render( array( 'shortcode-download-image.twig', ), $context, apply_filters( 'maera/timber/cache', false ) );
                                        ?>
                                    </a>
                                <?php endif; ?>

                                <figcaption>
                                    <a title="<?php _e( 'View ','maera-edd') . the_title(); ?>" href="<?php the_permalink(); ?>">
                                        <h3 style="color: <?php echo $image['fontcolor']; ?>;"><?php the_title(); ?></h3>
                                    </a>
                                    <div class="details">
                                        <span class="price"><?php edd_get_template_part( 'shortcode', 'content-price' ); ?></span>
                				         <!-- Excerpt and Content -->
                                        <?php $excerpt_length = apply_filters( 'excerpt_length', 20 ); ?>
                                        <?php if ( $excerpt != 'no' && $full_content != 'yes' && has_excerpt() ) : ?>
                                            <p><?php echo wp_trim_words( get_the_excerpt(), $excerpt_length ); ?></p>
                                        <?php elseif ( $full_content == 'yes' ) : ?>
                                            <?php the_content(); ?>
                                        <?php endif; ?>
                                        <!-- Buy button -->
                                        <?php if ( 'yes' == $buy_button ) : ?>
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
                                </figcaption>
                            </div>
                        </figure>
                    <?php endwhile; ?>

        			<?php wp_reset_postdata(); ?>
                [maera_grid_container_close]
            </div>
        <?php else : ?>
        <?php endif ?>

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
