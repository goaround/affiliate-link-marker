<?php
namespace Affiliate_Marker\Admin;

use Affiliate_Marker\Admin\SettingsPage;
use Affiliate_Marker\Links;

class NetworkBlogSettingsPage extends SettingsPage {

	public function page_init() {

		parent::page_init();

		$this->register_setting_disclosure();

	}

	public function section_domains() {
		printf(	__( 'Manage your Domains from your <a href="%s">Network Admin Area</a>.', 'affiliate-marker' ), add_query_arg( [ 'page' => $this->page ], network_admin_url( 'settings.php' ) ) );
	}

}
