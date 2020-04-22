=== Affiliate Marker ===
Contributors: goaroundagain
Donate link:
Tags: affiliate, links, sponsored, nofollow, multisite
Requires at least: 5.4
Tested up to: 5.4
Requires PHP: 7.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Mark your Affiliate Links with a *, add `rel="nofollow sponsored noopener"` to affiliate links and attach a disclosure at the end of every post which contains at least one affiliate link.

== Description ==

Mark your Affiliate Links with a `*`, add `rel="nofollow sponsored noopener"` to affiliate links and attach a disclosure at the end of every post which contains at least one affiliate link.

You can manage your own list of affiliate tracking domains or URL parts (used to detect affiliate links) and change the disclosure text.

Works great with Multisites. If activated networkwide, you can manage your affiliate domains from your Network Admin Area and the disclosure (e.g. translated) for every single page.

The following Affiliate Networks with the corresponding tracking domains are supported out of the box (but you can add your own or remove unused):
* Amazon PartnerNet `amazon`
* Affili.net `partners.webmasterplan.com`
* Webgains `track.webgains.com`
* Zanox `ad.zanox.com`
* AWIN `awin1.com`
* financeAds `financeads.net
* Performance Horizon/Partnerize `prf.hn`
* Belboon `belboon.de`
* Tradedoubler `tradedoubler.com`
* Adcell `adcell.de`
* TradeTracker `tc.tradetracker.net`
* Daisycon `ds1.nl`
* ShareASale `shareasale.com`
* CJ.com `jdoqocy.com` `tkqlhce.com` `anrdoezrs.net` `dpbolvw.net`
* FinanceQuality `l.neqty.net`
* Target Circle `c.trackmytarget.com`
* NetAffiliation `action.metaffiliation.com`
* Selecdoo `work.selecdoo.com`
* Viglink `viglink.com`
* eBay Partner Network `rover.ebay.com`
* Groupon Partner Network `t.groupon`
* Saturn + Media Markt Private Network `pvn.mediamarkt.de` `pvn.saturn.de`
* StackCommerce `stacksocial.com` `citizengoods.com` `skillwise.com` `joyus.com`

== Installation ==

1. Upload the folder `affiliate-marker` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to `Settings` → `Affiliate Marker` and add your own affiliate domains or change the default disclosure.
1. Add an affiliate link to one of your post and you'll see the `*` in the front end next to the link and the disclosure at the end of your post.

== Frequently Asked Questions ==

= Can I change the style of the disclosure? =

There is a CSS applied to the disclosure `affiliate-marker-disclosure`. You can change e.g. the font size with some custom CSS `.affiliate-marker-disclosure { font-size: small; }`.

= Does it work with JavaScript tracking code? =

It only works with HTML `<a href="">` links, not with crazy JavaScript tracking code like `onclick="..."`

== Screenshots ==

1. Add your own affiliate related domains or any part of an affiliate links that identifies your affiliate link and change the disclosure text
1. Every affiliate link is marked by a `*` next to the link text and at the end of post the disclosure will be attached

== Changelog ==

= 1.0.4

* Fixed some PHP compatibility issues

= 1.0.0 =
* First release

== Upgrade Notice ==

== Arbitrary section ==
