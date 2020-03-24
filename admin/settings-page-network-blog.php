<?php
namespace TD\Affiliate\Marker\Admin;

use TD\Affiliate\Marker\Admin\SettingsPage;
use TD\Affiliate\Marker\Links;

class NetworkBlogSettingsPage extends SettingsPage {

	public function page_init() {

		parent::page_init();

		$this->register_setting_disclosure();

	}

	public function section_domains() {
		printf(	__( 'Manage your Domains from your <a href="%s">Network Admin Area</a>.', 'td-affiliate-marker' ), add_query_arg( [ 'page' => $this->page ], network_admin_url( 'settings.php' ) ) );
	}

}
