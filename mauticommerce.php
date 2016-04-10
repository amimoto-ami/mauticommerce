<?php
/**
 * Plugin Name: Mauticommerce
 * Version: 0.0.1
 * Dmauticiption: Senf Woo Wommerce customer information to Mautic Form.
 * Author: hideokamoto
 * Author URI: YOUR SITE HERE
 * Plugin URI: PLUGIN SITE HERE
 * Text Domain: mauticommerce
 * Domain Path: /languages
 * @package Mauticommerce
 */
if ( ! mautic_is_activate_woocommerce() ) {
	$Mauticommerce_Err = new Mauticommerce_Err();
	$msg = array(
		__( 'MautiCommerce Need "WooCommerce" Plugin.' , 'mautiCommerce-relateditem' ),
		__( 'Please Activate it.' , 'mautiCommerce-relateditem' ),
	);
	$e = new WP_Error( 'MautiCommerce Activation Error', $msg );
	$Mauticommerce_Err->show_error_message( $e );
	add_action( 'admin_notices', array( $Mauticommerce_Err, 'admin_notices' ) );
	return false;
}
define( 'Mauticommerce_ROOT', __FILE__ );

$Mauticommerce = Mauticommerce::get_instance();
$Mauticommerce->init();

class Mauticommerce {
	private $Base;
	private static $instance;
	private static $text_domain;

	private function __construct() {
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	public function init() {
	}
}

class Mauticommerce_Err {

	/**
	 * Show notice for wp-admin if have error messages
	 *
	 * @since 0.0.1
	 **/
	public function admin_notices() {
		if ( $messageList = get_transient( 'mautic-admin-errors' ) ) {
			$this->show_notice_html( $messageList );
		}
	}

	/**
	 * Set error message
	 *
	 * @param WP_Error
	 * @since 0.0.1
	 */
	public function show_error_message( $msg ) {
		if ( ! is_wp_error( $msg ) ) {
			$e = new WP_Error();
			$e->add( 'error' , $msg , 'mautic-admin-errors' );
		} else {
			$e = $msg;
		}
		set_transient( 'mautic-admin-errors' , $e->get_error_messages(), 10 );
	}

	/**
	 * echo error message html
	 *
	 * @param array
	 * @since 0.1.0
	 */
	public function show_notice_html( $messageList ) {
		foreach ( $messageList as $key => $messages ) {
			$html  = "<div class='error'><ul>";
			foreach ( (array)$messages as $key => $message ) {
				$html .= "<li>{$message}</li>";
			}
			$html .= '</ul></div>';
		}
		echo $html;
	}
}

/**
 * Check WooCommerce Plugin status
 *
 * @since 0.0.1
 * @return bool
 */
function mautic_is_activate_woocommerce() {
	$activePlugins = get_option('active_plugins');
	$plugin = 'woocommerce/woocommerce.php';
	if ( ! array_search( $plugin, $activePlugins ) && file_exists( WP_PLUGIN_DIR. '/'. $plugin ) ) {
		return false;
	} else {
		return true;
	}
}
