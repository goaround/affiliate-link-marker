<?php
/**
 * Plugin Name:  Affiliate Marker
 * Plugin URI:
 * Description: Marks Affiliate Links
 * Author:      Johannes Kinast <johannes@travel-dealz.de>
 * Author URI:  https://go-around.de
 * Version:     0.0.2
 * Text Domain: td-affiliate-marker
 * Domain Path: /languages
 */
namespace TD\Affiliate\Marker;

class Links {
	public $domains = [
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

	public $domain_search = '';

	public $found_match = false;

	public function __construct() {
		$domains = array_map( function( $value ) {
			return preg_quote( $value, '/' );
		}, $this->domains );
		$this->domain_search = implode( '|', $domains );
	}

	public function nofollow_content_links( $content ) {

		$content = preg_replace_callback(
			'/<a[^>]+href="([^"]+)"[^>]*>/',
			[$this, 'check_match'],
			$content
		);
		//exit;
		return $content;
	}

	private function check_match( $match ) {
		if ( preg_match( '/.*' . $this->domain_search. '.*/', $match[1] ) ) {
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

	public function rel_nofollow( $rel, $link_html ) {

		if ( preg_match( '/.*' . $this->domain_search. '.*/', $link_html ) ) {
			return 'nofollow sponsored noopener';
		}

		return $rel;
	}

	public function add_notice( $content ) {
		if ( strpos( $content, 'sponsored' ) ) {
			$content .= '<p><aside>' . __( '* What the star implies: Links marked with a * mean that we will receive a commission if a booking or a specific action is made via the linked provider. There will be no additional costs for you, and in return, we can offer you Travel-Dealz without annoying display advertisement. Also, we won\'t receive any money just by setting links.', 'td-affiliate-marker' ) . '</aside></p>';
		}
		return $content;
	}

	public static function style_marker() {
		echo '<style>a[rel*=sponsored]{position:relative}a[rel*=sponsored]:after{text-decoration:none;font-weight:400;display:inline-block;content:"*"}</style>';
	}

}
$Nofollow = new Links;

add_filter( 'the_content', [ $Nofollow, 'nofollow_content_links' ], 11 );
add_filter( 'the_content', [ $Nofollow, 'add_notice' ], 20 );
add_filter( 'wp_targeted_link_rel', [ $Nofollow, 'rel_nofollow' ], 10, 2 );
add_action( 'wp_head', 'TD\Affiliate\Marker\Links::style_marker' );