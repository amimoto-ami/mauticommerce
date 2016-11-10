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

	/**
	 * Subscribe order information to Mautic
	 *
	 * @param string $order_id Order
	 * @param string $status post status (default:'new')
	 * @param string $new_status new post status (default:'pending')
	 * @access public
	 * @since 0.0.1
	 **/
	public function subscribe_to_mautic( $order_id, $status = 'new', $new_status = 'pending' ) {
		$order = wc_get_order( $order_id );
		$query = $this->_create_query( $order );
		$this->_subscribe( $query );
	}

	/**
	 * create query to send Mautic
	 *
	 * @param WC_Order $order WooCommerce order object
	 * @since 0.0.1
	 * @access private
	 * @return array $query posted mautic query
	 **/
	private function _create_query( WC_Order $order ) {
		$query = array(
			'address1' => $order->billing_address_1,
			'address2' => $order->billing_address_2,
			'city' => $order->billing_city,
			'company' => $order->billing_company,
			'country' => $this->_get_country_name( $order->billing_country ),
			'email' => $order->billing_email,
			'firstname' => $order->billing_first_name,
			'lastname' => $order->billing_last_name,
			'phone' => $order->billing_phone,
			'zipcode' => $order->billing_postcode,
			'state' => $this->_get_states_name( $order->billing_country, $order->billing_state ),
			'order_id' => $order->id,
		);

		/**
		 * Filter the query that customize Mautic query
		 *
		 * @since 0.0.1
		 * @param $query default mautic query
		 * @param WC_Order $order WooCommerce order object
		 **/
		return apply_filters( 'mauticommerce_query_mapping', $query, $order );
	}

	/**
	 * Get country name
	 *
	 * @since 0.0.1
	 * @access private
	 * @param string $country_code
	 * @return string $country_name | Exception
	 * @
	 **/
	private function _get_country_name( $country_code ) {
		$countries = wp_remote_get( path_join( MAUTICOMMERCE_URL, 'inc/assets/json/country.json' ) );
		try {
			if ( is_wp_error( $countries ) ) {
				throw new Exception( 'invalid json data.' );
			}
			$json = json_decode( $countries['body'], true );
			if ( ! isset( $json[ $country_code ] ) ) {
				throw new Exception( "Country[{$country_code}] is not found." );
			}
			$country_name = $json[ $country_code ];
			return $country_name;
		} catch ( Exception $e ) {
			$msg = 'Mauticommerce Error:' . $e->getMessage();
			error_log( $msg );
			$wc_country = new WC_Countries();
			return $wc_country->get_countries()[ $country_code ];
		}
	}

	/**
	 * Get state name
	 *
	 * @since 0.0.1
	 * @access private
	 * @param string $country_code country code
	 * @param string $state_code state code
	 * @return string | Exception
	 **/
	private function _get_states_name( $country_code, $state_code ) {
		$states = wp_remote_get( path_join( MAUTICOMMERCE_URL, 'inc/assets/json/states.json' ) );
		try {
			if ( is_wp_error( $states ) ) {
				throw new Exception( 'invalid json data.' );
			}
			$json = json_decode( $states['body'], true );
			if ( ! isset( $json[ $country_code ][ $state_code ] ) ) {
				throw new Exception( "Country[{$country_code}] is not found." );
			}
			$state_name = $json[ $country_code ][ $state_code ];
			return $state_name;
		} catch ( Exception $e ) {
			$msg = 'Mauticommerce Error:' . $e->getMessage();
			error_log( $msg );
			$wc_country = new WC_Countries();
			return $wc_country->get_states( $country_code )[ $state_code ];
		}
	}

	/**
	 * Subscribe to Mautic
	 *
	 * @param array $query Posted mautic query
	 * @access private
	 * @since 0.0.1
	 **/
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
				'cookies' => array(),
			)
		);
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			error_log( "MautiCommerce Error: $error_message" );
		}
	}

	/**
	 * get ip
	 *
	 * @access private
	 * @return string
	 * @since 0.0.1
	 **/
	private function _get_ip() {
		$ip_list = [
			'REMOTE_ADDR',
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
		];
		foreach ( $ip_list as $key ) {
			if ( ! isset( $_SERVER[ $key ] ) ) {
				continue;
			}
			$ip = esc_attr( $_SERVER[ $key ] );
			if ( ! strpos( $ip, ',' ) ) {
				$ips = explode( ',', $ip );
				foreach ( $ips as &$val ) {
					$val = trim( $val );
				}
				$ip = end( $ips );
			}
			$ip = trim( $ip );
			break;
		}
		return $ip;
	}
}
