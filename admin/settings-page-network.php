<?php
namespace Affiliate_Marker\Admin;

use Affiliate_Marker\Links;
use Affiliate_Marker\Admin\SettingsPage;

class NetworkSettingsPage extends SettingsPage {

	public function __construct() {
		add_action( 'network_admin_menu', [ $this, 'add_plugin_page' ] );
		add_action( 'admin_init', [ $this, 'page_init' ] );
	}

	public function add_plugin_page() {
		$this->domains = (array) get_site_option( Links::$options_name_domains, Links::$domains );

		add_submenu_page(
			'settings.php', // Parent element
			'Affiliate Marker', // page_title
			'Affiliate Marker', // menu_title
			'manage_options', // Capability
			$this->page, // menu_slug
			[ $this, 'create_admin_page' ], // callback
		);

		add_action( 'network_admin_edit_affiliate_marker_settings', [ $this, 'save_setttings' ] );

	}

	public function page_init() {

		parent::page_init();

		$this->register_setting_domains();

	}

	public function create_admin_page() {
		?>
			<div class="wrap">
				<h2>Affiliate Marker</h2>
				<p></p>
				<?php settings_errors(); ?>

				<form method="post" action="edit.php?action=affiliate_marker_settings">
					<?php
						echo wp_nonce_field(  $this->option_group );
						do_settings_sections( $this->page ); // page
						submit_button();
					?>
				</form>
			</div>
		<?php
	}

	public function save_setttings() {
		check_admin_referer( $this->option_group );

		update_site_option( Links::$options_name_domains, sanitize_textarea_field( $_POST['affiliate_marker_domains'] ?? '' ) );

		wp_redirect( add_query_arg(
			[
				'page' => $this->page,
				'updated' => true
			],
			network_admin_url( 'settings.php' ),
		) );

		exit;

	}

	public function section_disclosure() {
		_e( 'Manage the Disclosure from each Blog Settings.', 'affiliate-marker' );
	}

}
