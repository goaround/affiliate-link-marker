<?php

class FrontendTest extends WP_UnitTestCase {

	public function test_affiliate_link_rel_include_nofollow_spnsored() {
		$post_id = self::factory()->post->create( [
			'post_content' => '<a href="https://amazon.com">Amazon</a>',
		] );

		$post = get_post( $post_id );
		$content = apply_filters( 'the_content', $post->post_content );

		$this->assertContains( 'rel="nofollow sponsored noopener"', $content );
	}

	public function test_normal_links_do_not_include_nofollow_sponsored() {
		$post_id = self::factory()->post->create( [
			'post_content' => '<a href="https://example.org">Amazon</a>',
		] );

		$post = get_post( $post_id );
		$content = apply_filters( 'the_content', $post->post_content );

		$this->assertNotContains( 'rel="nofollow sponsored noopener"', $content );
	}

	public function test_if_a_affiliate_link_is_presend_content_contains_disclosure() {

		$post_id = self::factory()->post->create( [
			'post_content' => '<a href="https://amazon.com">Amazon</a>',
		] );

		$post = get_post( $post_id );
		$content = apply_filters( 'the_content', $post->post_content );

		$this->assertContains( __( '* What the star implies: Links marked with a * mean that we will receive a commission if a booking or a specific action is made via the linked provider. There will be no additional costs for you. Also, we won\'t receive any money just by setting links.', 'td-affiliate-marker' ), $content );

	}

	public function test_if_no_affiliate_links_is_present_content_contains_no_disclosure() {

		$post_id = self::factory()->post->create( [
			'post_content' => '<a href="https://example.org">Amazon</a>',
		] );

		$post = get_post( $post_id );
		$content = apply_filters( 'the_content', $post->post_content );

		$this->assertNotContains( __( '* What the star implies: Links marked with a * mean that we will receive a commission if a booking or a specific action is made via the linked provider. There will be no additional costs for you. Also, we won\'t receive any money just by setting links.', 'td-affiliate-marker' ), $content );

	}

	public function test_style_is_included_in_wp_head() {

		ob_start();
		do_action( 'wp_head' );
		$output = ob_get_contents();
		ob_end_clean();

		$this->assertContains( 'a[rel*=sponsored]', $output );

	}

	public function test_rel_sponsored_gets_added_to_new_links() {
		$rel = apply_filters( 'wp_targeted_link_rel', 'noopener noreferrer', '<a href="https://amazon.com">Amazon</a>' );
		$this->assertEquals( 'nofollow sponsored noopener', $rel );
	}

}