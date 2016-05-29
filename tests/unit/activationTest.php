<?php

class activationTest extends WP_UnitTestCase {
	public function setUp() {
	}

	public function tearDown() {
	}

	// tests
	public function testIsActivatedWooCommercePlugin() {
		$this->assertTrue( mautic_is_activate_woocommerce() );
	}
}
