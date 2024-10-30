<?php

/**
 * Plugin Name: Combidesk - Yuki
 * Version: 1.24
 * Description: Synchroniseer je WooCommerce orders automatisch naar Yuki
 * Author: Combidesk
 * Author URI: https://www.combidesk.com/
 * Text Domain: combidesk-yuki
 * Domain Path: /languages
 * Tags: WooCommerce
 *
 * WC requires at least: 2.2
 * WC tested up to: 9.3.3
 *
 **/

if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

// Include required classes
require_once( 'classes/class-application.php' );

new Combidesk\Yuki\Application();
