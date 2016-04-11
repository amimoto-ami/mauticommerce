<?php
class Mauticommerce_Order extends Mauticommerce {
	/**
	 * Instance Class
	 * @access private
	 */
	private static $instance;

	/**
	 * text domain
	 * @access private
	 */
	private static $text_domain;

	/**
	 * Constructer
	 * Set text domain on class
	 *
	 * @since 0.0.1
	 */
	private function __construct() {
		self::$text_domain = Mauticommerce::text_domain();
		$this->mauticommece_settings = get_option( 'mauticommece_settings' );
	}

	/**
	 * Get Instance Class
	 *
	 * @return Mauticommerce_Admin
	 * @since 0.0.1
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
}
