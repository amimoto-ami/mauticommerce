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

	public function subscribe_to_mautic( $order_id, $status = 'new', $new_status = 'pending' ) {
		$order = wc_get_order( $order_id );
		$query = $this->_create_query( $order );
		$this->_subscribe( $query );
	}

	private function _create_query( $order ) {
		$query = array(
			'address1' => $order->billing_address_1,
			'address2' => $order->billing_address_2,
			'city' => $order->billing_city,
			'company' => $order->billing_company,
			'country' => $order->billing_country,
			'email' => $order->billing_email,
			'firstname' => $order->billing_first_name,
			'lastname' => $order->billing_last_name,
			'phone' => $order->billing_phone,
			'zipcode' => $order->billing_postcode,
			'state' => $order->billing_state,
			'order_id' => $order->id,
		);
		return apply_filters( 'mauticommerce_query_mapping', $query );
	}

	private function _subscribe( $query ) {
		$ip = $this->_get_ip();
		$settings = get_option( 'mauticommece_settings' );
		if ( ! isset( $query['return'] ) ) {
			$query['return'] = get_home_url();
		}
		$query['formId'] = $settings['form_id'];
		$data = array(
			'mauticform' => $query,
		);
		$url = path_join( $settings['url'], "form/submit?formId={$settings['form_id']}" );

		$response = wp_remote_post(
			$url,
			array(
				'method' => 'POST',
				'timeout' => 45,
				'headers' => array(
					'X-Forwarded-For' => $ip,
				),
				'body' => $data,
				'cookies' => array()
			)
		);
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			error_log( "MautiCommerce Error: $error_message" );
		}
	}

	private function _get_ip() {
		$ip_list = [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED'
		];
		foreach ( $ip_list as $key ) {
			if ( ! isset( $_SERVER[ $key ] ) ) {
				continue;
			}
			$ip = esc_attr( $_SERVER[ $key ] );
			if ( ! strpos( $ip, ',' ) ) {
				$ips =  explode( ',', $ip );
				foreach ( $ips as &$val ) {
					$val = trim( $val );
				}
				$ip = end ( $ips );
			}
			$ip = trim( $ip );
			break;
		}
		return $ip;
	}
}
