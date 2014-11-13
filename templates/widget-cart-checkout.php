<?php global $edd_options; ?>
<li class="cart_item edd_subtotal"><?php echo __( 'Subtotal:', 'edd' ). " <span class='subtotal'>" . edd_currency_filter( edd_format_amount( edd_get_cart_subtotal() ) ); ?></span></li>
<li class="cart_item edd_checkout"><a href="<?php echo edd_get_checkout_uri(); ?>" class="button radius expand <?php echo $edd_options['checkout_color']; ?>"><?php _e( 'Checkout', 'edd' ); ?></a></li>
