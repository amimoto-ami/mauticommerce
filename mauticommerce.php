<?php
/**
 * Plugin Name: Mauticommerce
 * Version: 0.1.1
 * Descrtiption: Senf Woo Wommerce customer information to Mautic Form.
 * Author: hideokamoto
 * Author URI: http://wp-kyoto.net/
 * Plugin URI: https://github.com/megumiteam/mauticommerce
 * Text Domain: mauticommerce
 * Domain Path: /languages
 * @package Mauticommerce
 */
if ( ! mautic_is_activate_woocommerce() ) {
	$mauticommerce_err = new Mauticommerce_Err();
	$mauticommerce_err->activation_fail();
	return false;
}
define( 'MAUTICOMMERCE_PATH', plugin_dir_path( __FILE__ ) );
define( 'MAUTICOMMERCE_URL', plugin_dir_url( __FILE__ ) );
define( 'MAUTICOMMERCE_ROOT', __FILE__ );

require_once 'inc/class.admin.php';
require_once 'inc/class.order.php';

$mauticommerce = Mauticommerce::get_instance();
$mauticommerce->init();

class Mauticommerce {
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

	/**
	 * Get Plugin text_domain
	 *
	 * @return string
	 * @since 0.0.1
	 */
	public static function text_domain() {
		static $text_domain;

		if ( ! $text_domain ) {
			$data = get_file_data( MAUTICOMMERCE_ROOT , array( 'text_domain' => 'Text Domain' ) );
			$text_domain = $data['text_domain'];
		}
		return $text_domain;
	}

	public function init() {
		$admin = Mauticommerce_Admin::get_instance();
		add_action( 'admin_menu', array( $admin, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $admin, 'settings_init' ) );
		$order = Mauticommerce_Order::get_instance();
		add_action( 'woocommerce_checkout_update_order_meta', array( $order, 'subscribe_to_mautic' ) );
	}
}

class Mauticommerce_Err {
	/**
	 * Set Error to fail to Actiavation Plugin
	 *
	 * @since 0.1.1
	 **/
	public function activation_fail() {
		$msg = array(
			__( 'MautiCommerce Need "WooCommerce" Plugin.' , 'mauticommerce' ),
			__( 'Please Activate it.' , 'mauticommerce' ),
		);
		$e = new WP_Error( 'MautiCommerce Activation Error', $msg );
		$this->show_error_message( $e );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		return $e;
	}

	/**
	 * Show notice for wp-admin if have error messages
	 *
	 * @since 0.0.1
	 **/
	public function admin_notices() {
		if ( $message_list = get_transient( 'mautic-admin-errors' ) ) {
			$this->show_notice_html( $message_list );
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
	public function show_notice_html( $message_list ) {
		foreach ( $message_list as $key => $messages ) {
			$html  = "<div class='error'><ul>";
			foreach ( (array) $messages as $key => $message ) {
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
	$active_plugins = get_option( 'active_plugins' );
	$plugin = 'woocommerce/woocommerce.php';
	if ( ! array_search( $plugin, $active_plugins ) && file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
		return false;
	} else {
		return true;
	}
}
