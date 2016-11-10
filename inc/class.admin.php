<?php
/**
 * Show Admin Panel Class
 *
 * @package Mauticommerce
 * @author hideokamoto
 * @since 0.0.1
 **/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Admin Page Class
 *
 * @class Mauticommerce_Admin
 * @since 0.0.1
 */
class Mauticommerce_Admin extends Mauticommerce {
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
	 * WP_Options Param ( mauticommece_settings )
	 * @access private
	 */
	private $mauticommece_settings = array();

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


	/**
	 * Routing function
	 *
	 * @since 0.0.1
	 */
	public function settings_init() {
		$this->_register_admin_panels();
		if ( empty( $_POST ) ) {
			return;
		}
	}

	/**
	 * Register admin setting field
	 *
	 * @since 0.0.1
	 */
	private function _register_admin_panels() {
		register_setting( 'MautiCommerce', 'mauticommece_settings' );
		add_settings_section(
			'mauticommece_RelatedScore_settings',
			__( 'Settings', self::$text_domain ),
			array( $this, 'mauticommece_settings_url_section_callback' ),
			'MautiCommerce'
		);
		add_settings_field(
			'url',
			__( 'Mautic URL', self::$text_domain ),
			array( $this, 'mautic_url_render' ),
			'MautiCommerce',
			'mauticommece_RelatedScore_settings'
		);
		add_settings_field(
			'form_id',
			__( 'Mautic Form ID', self::$text_domain ),
			array( $this, 'mautic_form_id_render' ),
			'MautiCommerce',
			'mauticommece_RelatedScore_settings'
		);
	}

	/**
	 * echo input field( Mautic Form ID)
	 *
	 * @since 0.0.1
	 */
	public function mautic_form_id_render() {
		if ( ! isset( $this->mauticommece_settings['form_id'] ) ) {
			$this->mauticommece_settings['form_id'] = '';
		}
		echo "<input type='text' name='mauticommece_settings[form_id]' value='" . $this->mauticommece_settings['form_id'] . "'>";
	}

	/**
	 * echo input field( Mautic URL)
	 *
	 * @since 0.0.1
	 */
	public function mautic_url_render() {
		if ( ! isset( $this->mauticommece_settings['url'] ) ) {
			$this->mauticommece_settings['url'] = '';
		}
		echo "<input type='url' name='mauticommece_settings[url]' value='" . $this->mauticommece_settings['url'] . "' style='width:100%;'>";
	}

	/**
	 * echo Search Score Field Dmauticommeceiption
	 *
	 * @since 0.0.1
	 */
	public function mauticommece_settings_url_section_callback() {
		echo __( 'Set Mautic Information.', self::$text_domain );
	}

	/**
	 * echo form area
	 *
	 * @since 0.0.1
	 */
	public function mauticommece_options() {
		echo '<h2>Mauticommece</h2>';
		echo "<form action='options.php' method='post'>";
		settings_fields( 'MautiCommerce' );
		do_settings_sections( 'MautiCommerce' );
		submit_button();
		echo '</form>';
	}

	/**
	 * Register Admin Option Page
	 *
	 * @since 0.0.1
	 */
	public function add_admin_menu() {
		add_options_page( 'Mauticommece', 'Mauticommece', 'manage_options', 'mauticommece', array( $this, 'mauticommece_options' ) );
	}

}
