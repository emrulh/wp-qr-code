<?php
/*
 * Plugin Name:       Awesome QR Code
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */

if ( !class_exists( "MyPlugin" ) ) {
    class MyPlguin {
        public function __construct() {
            add_action( "init", array( $this, "init" ) );
        }
        public function init() {
            add_filter( "the_title", array( $this, "change_title" ) );
            add_filter( "the_content", array( $this, "add_qr_code" ) );
        }
        public function change_title( $title ) {
            $title = $title . " Test content";
            return $title;
        }
        public function add_qr_code( $content ) {
            $link = esc_url( get_the_permalink() );
            $title = esc_attr( get_the_title() );
            $qrCode = "<p><img src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&color=ff0000&data={$link}' alt='{$title}'></p>";
            return $content . $qrCode;
        }
    }
    new MyPlguin();
}

//activation notice
register_activation_hook( __FILE__, "plugin_activated" );
function plugin_activated() {
    add_option( "my_plugin_activated", "activated" );
}
add_action( "admin_init", "plugin_activated_notice" );
function plugin_activated_notice() {
    if ( is_admin() && get_option( "my_plugin_activated" ) == "activated" ) {
        //delete_option( 'my_plugin_activated' );
        wp_admin_notice( "Hello", array( 'id' => 'success', 'dismissable' => true ) );
    }
}

//deactivation hook
register_deactivation_hook( __FILE__, 'plugin_deactivated' );
function plugin_deactivated() {
    //delete_option( 'my_plugin_activated' );
    wp_admin_notice( "Hopefully you'll come back soon!", array( 'success' ) );
}