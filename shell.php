<?php

define( 'MAERA_EDD_SHELL_URL', plugins_url( '', __FILE__ ) );
define( 'MAERA_EDD_SHELL_PATH', dirname( __FILE__ ) );

// Include the framework class
require_once MAERA_EDD_SHELL_PATH . '/includes/class-Maera_EDD_Shell.php';
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

/**
 * Message about the forced use of the EDD shell
 */
function maera_edd_shell_description( $content ) {
    return $content . '<p style="padding: 15px; color: #a94442; background-color: #f2dede; border-color: #ebccd1;">' . __( 'You have the Maera-EDD plugin activated. This plugin currently <strong>forces</strong> the use of its own shell so changing shells will not have an effect.', 'maera_edd' ) . '</p>';
}
add_filter( 'maera/admin/shell_select_description', 'maera_edd_shell_description' );

/**
 * Force the shell
 */
function maera_edd_force_shell() {

    // Get the option from the database and replace the current shell with 'edd'.
    $options = get_option( 'maera_admin_options', array() );
    if ( 'edd' != $options['shell'] ) {
        $options['shell'] = 'edd';
        update_option( 'maera_admin_options', $options );
    }

}
add_action( 'init', 'maera_edd_force_shell' );

/**
 * Keep a record of what the previously activated shell was
 */
function maera_edd_save_previous_shell_setting() {

    // Get the shell and save it on a separate setting as a backup
    $options = get_option( 'maera_admin_options', array() );
    update_option( 'maera_edd_previous_shell', $options['shell'] );

}
register_activation_hook( MAERA_EDD_FILE, 'maera_edd_save_previous_shell_setting' );

/**
 * Restore previously activated shell on plugin deactivation
 */
function maera_edd_restore_previous_shell_setting() {

    // Get the theme options & Replace the shell in the array with the backed-up option
    $options = get_option( 'maera_admin_options', array() );
    $options['shell'] = get_option( 'maera_edd_previous_shell' );

    // Update the core theme settings & Delete the backup option.
    update_option( 'maera_admin_options', $options );
    delete_option( 'maera_edd_previous_shell' );

}
register_deactivation_hook( MAERA_EDD_FILE, 'maera_edd_restore_previous_shell_setting' );
