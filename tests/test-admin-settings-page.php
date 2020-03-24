<?php

class AdminSettingsPage extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();

		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		$user = wp_set_current_user( $user_id );

		set_current_screen( 'affiliate-marker' );
	}

	public function tearDown() {
		parent::tearDown();
	}

}