<?php
namespace TD\Affiliate\Marker;
/**
 * Plugin Name:  Affiliate Link Marker
 * Plugin URI:
 * Description: Marks Affiliate Links with a * and adds a disclosure at the end of the post
 * Author:      Johannes Kinast <johannes@travel-dealz.de>
 * Author URI:  https://go-around.de
 * Version:     0.1.0
 * Text Domain: td-affiliate-marker
 * Domain Path: /languages
 */

class Links {
	public static $domains = [
		'/go/',
		'partners.webmasterplan.com',
		'track.webgains.com',
		'ad.zanox.com',
		'awin1.com',
		'financeads.net',
		'prf.hn',
		'belboon.de',
		'tradedoubler.com',
		'adcell.de',
		'tc.tradetracker.net',
		'ds1.nl', // Daisycon
		'shareasale.com',
		'jdoqocy.com', 'tkqlhce.com', 'anrdoezrs.net', 'dpbolvw.net', // CJ.com
		'l.neqty.net', // FinanceQuality
		'/c/297275', // Impact
		'c.trackmytarget.com', //Target Circle
		'action.metaffiliation.com', // NetAffiliation
		'work.selecdoo.com', // Selecdoo
		'viglink.com', // Viglink
		'go2.travel-dealz',
		'mytrain.de',
		'booking.com',
		'partner.zenmate.com',
		'billiger-mietwagen.de',
		'amazon',
		'rover.ebay.com',
		'skyscanner',
		'jetradar',
		'getyourguide',
		'secretescapes',
		't.groupon',
		'virtuoso.com',
		'pvn.mediamarkt.de', 'pvn.saturn.de', // Saturn + Media Markt Private Network
		'stacksocial.com', 'citizengoods.com', 'skillwise.com', 'joyus.com', // StackCommerce
		'airportag.com',
	];

	public static string $options_name_disclosure = 'affiliate_marker_disclosure';
	public static string $options_name_domains = 'affiliate_marker_domains';

	public $domain_search = '';

	public $found_match = false;

	public function nofollow_content_links( $content ) {

		$content = preg_replace_callback(
			'/<a[^>]+href="([^"]+)"[^>]*>/',
			[$this, 'check_match'],
			$content
		);

		return $content;
	}

	public function rel_nofollow( $rel, $link_html ) {

		if ( preg_match( '/.*' . $this->domain_search_regex() . '.*/', $link_html ) ) {
			return 'nofollow sponsored noopener';
		}

		return $rel;
	}

	public function domain_search_regex() {

		if ( ! empty( $this->domain_search ) ) {
			return $this->domain_search;
		}

		if ( is_plugin_active_for_network( 'td-affiliate-marker/affiliate-marker.php' ) ) {
			$domains = get_site_option( Links::$options_name_domains, Links::$domains );
		} else {
			$domains = get_option( Links::$options_name_domains, Links::$domains );
		}

		$domains = array_map( function( $value ) {
			return preg_quote( $value, '/' );
		}, $domains );

		$this->domain_search = implode( '|', $domains );

		return $this->domain_search;
	}

	private function check_match( $match ) {
		if ( ! empty( $this->domain_search_regex() ) && preg_match( '/.*' . $this->domain_search_regex() . '.*/', $match[1] ) ) {
			// If nofollow already set
			if ( 0 < strpos( $match[0], 'nofollow external' ) ) {
				return $match[0];
			}
			$count = 0;
			$match[0] = preg_replace( '/rel="[^"]+"/', 'rel="nofollow sponsored noopener"', $match[0], 1, $count );
			// if there is no rel-paramter
			if ( 0 === $count ) {
				$match[0] = str_replace( 'href', 'rel="nofollow sponsored noopener" href', $match[0] );
				$this->found_match = true;
			}
		}
		return $match[0];
	}

}
global $AffiliateLinks;
$AffiliateLinks = new Links;

add_filter( 'the_content', [ $AffiliateLinks, 'nofollow_content_links' ], 11 );
add_filter( 'wp_targeted_link_rel', [ $AffiliateLinks, 'rel_nofollow' ], 10, 2 );

function add_notice( $content ) {
	if ( strpos( $content, 'sponsored' ) ) {
		if ( is_plugin_active_for_network( 'td-affiliate-marker/affiliate-marker.php' ) ) {
			$disclosure = get_site_option( Links::$options_name_disclosure, __( '* What the star implies: Links marked with a * mean that we will receive a commission if a booking or a specific action is made via the linked provider. There will be no additional costs for you. Also, we won\'t receive any money just by setting links.', 'td-affiliate-marker' ) );
		} else {
			$disclosure = get_option( Links::$options_name_disclosure, __( '* What the star implies: Links marked with a * mean that we will receive a commission if a booking or a specific action is made via the linked provider. There will be no additional costs for you. Also, we won\'t receive any money just by setting links.', 'td-affiliate-marker' ) );
		}
		$content .= '<p><aside>' . $disclosure . '</aside></p>';
	}
	return $content;
}
add_filter( 'the_content', __NAMESPACE__ . '\add_notice', 20 );

function style() {
	echo '<style>a[rel*=sponsored]{position:relative}a[rel*=sponsored]:after{text-decoration:none;font-weight:400;display:inline-block;content:"*"}</style>';
}
add_action( 'wp_head', __NAMESPACE__ . '\style' );

if ( is_admin() ) {
	require_once __DIR__ . '/admin/settings-page.php';
	require_once __DIR__ . '/admin/settings-page-network.php';
	if ( is_plugin_active_for_network( 'td-affiliate-marker/affiliate-marker.php' ) ) {
		new namespace\Admin\NetworkSettingsPage();
	} else {
		new namespace\Admin\SettingsPage();
	}
}

function load_plugin_textdomain() {
    \load_plugin_textdomain( 'td-affiliate-marker', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'TD\Affiliate\Marker\load_plugin_textdomain' );