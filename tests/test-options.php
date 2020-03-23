<?php

use TD\Affiliate\Marker\Links;


class OptionsTest extends WP_UnitTestCase {

	public function test_options_affiliate_domains_will_be_used() {

		$this->reset_domain_search();

		add_option( Links::$options_name_domains, [
			'test.com',
		] );

		$content = apply_filters( 'the_content', '<a href="https://example.org">Amazon</a>' );
		$this->assertNotContains( 'rel="nofollow sponsored noopener"', $content );

		$content = apply_filters( 'the_content', '<a href="https://test.com">Amazon</a>' );
		$this->assertContains( 'rel="nofollow sponsored noopener"', $content );

		$this->reset_domain_search();

	}

	public function test_options_disclosure_will_be_used() {
		add_option( Links::$options_name_disclosure, 'This is just a test' );
		$content = apply_filters( 'the_content', '<a href="https://amazon.com">Amazon</a>' );
		$this->assertContains( 'This is just a test', $content );
	}

	public function reset_domain_search() {
		global $AffiliateLinks;
		$AffiliateLinks->domain_search = '';
	}

}