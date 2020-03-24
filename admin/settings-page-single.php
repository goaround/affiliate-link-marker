<?php
namespace TD\Affiliate\Marker\Admin;

use TD\Affiliate\Marker\Links;
use TD\Affiliate\Marker\Admin\SettingsPage;

class SingleSettingsPage extends SettingsPage {

	protected array $domains;

	public function add_plugin_page() {

		$this->domains = (array) get_option( Links::$options_name_domains, Links::$domains );

		parent::add_plugin_page();

	}

	public function page_init() {

		parent::page_init();

		$this->register_setting_disclosure();
		$this->register_setting_domains();

	}

}
