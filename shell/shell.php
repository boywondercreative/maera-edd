<?php

define( 'MAERA_EDD_SHELL_URL', plugins_url( '', __FILE__ ) );
define( 'MAERA_EDD_SHELL_PATH', dirname( __FILE__ ) );

// Include the framework class
require_once MAERA_EDD_SHELL_PATH . '/class-Maera_EDD_Shell.php';
/**
 * Include the shell
 */
function maera_edd_shell_include( $shells ) {

    // Add our shell to the array of available shells
    $shells[] = array(
        'value' => 'edd',
        'label' => 'Easy Digital Downloads',
        'class' => 'Maera_EDD_Shell',
    );

    return $shells;

}
add_filter( 'maera/shells/available', 'maera_edd_shell_include' );
