<?php
namespace TD\Affiliate\Marker\Admin;

use \TD\Affiliate\Marker\Links;

class SettingsPage {
	protected string $option_group = 'affiliate-marker-settings';
	protected string $page = 'affiliate-marker';
	protected string $disclosure;
	protected array $domains;

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_plugin_page' ] );
		add_action( 'admin_init', [ $this, 'page_init' ] );
	}

	public function add_plugin_page() {

		$this->disclosure = get_option( Links::$options_name_disclosure, __( '* What the star implies: Links marked with a * mean that we will receive a commission if a booking or a specific action is made via the linked provider. There will be no additional costs for you. Also, we won\'t receive any money just by setting links.', 'td-affiliate-marker' ) );
		$this->domains = (array) get_option( Links::$options_name_domains, Links::$domains );

		add_options_page(
			'Affiliate Marker', // page_title
			'Affiliate Marker', // menu_title
			'manage_options', // capability
			$this->page, // menu_slug
			[ $this, 'create_admin_page' ], // callback
		);

	}

	public function create_admin_page() {
		?>
			<div class="wrap">
				<h2>Affiliate Marker</h2>
				<p></p>
				<?php settings_errors(); ?>

				<form method="post" action="options.php">
					<?php
						settings_fields( $this->option_group ); // option_group
						do_settings_sections( $this->page ); // page
						submit_button();
					?>
				</form>
			</div>
		<?php
	}

	public function page_init() {
		register_setting(
			$this->option_group, // option_group
			Links::$options_name_disclosure, // option_name
			[
				'type' => 'string',
				'description' => 'Affiliate disclosure printed on the button of every post which includes at least one affiliate Link.',
				'sanitize_callback' => [ $this, 'sanitize_disclosure' ],
				'show_in_rest' => true,
				'default' => __( '* What the star implies: Links marked with a * mean that we will receive a commission if a booking or a specific action is made via the linked provider. There will be no additional costs for you. Also, we won\'t receive any money just by setting links.', 'td-affiliate-marker' ),
			],
		);

		register_setting(
			$this->option_group, // option_group
			Links::$options_name_domains, // option_name
			[
				'description' => 'List of affiliate domains',
				'sanitize_callback' => [ $this, 'sanitize_domains' ],
				'default' => \TD\Affiliate\Marker\Links::$domains,
			],
		);

		add_settings_section(
			'affiliate_marker_disclosure_section', // id
			__( 'Affiliate Disclosure','td-affiliate-marker'), // title
			[ $this, 'section_disclosure' ], // callback
			$this->page // page
		);

		add_settings_section(
			'affiliate_marker_domain_section', // id
			__( 'Affiliate Domains','td-affiliate-marker'), // title
			[ $this, 'section_domains' ], // callback
			$this->page // page
		);

		add_settings_field(
			Links::$options_name_disclosure, // id
			__( 'Disclosure Text','td-affiliate-marker'), // title
			[ $this, 'disclosure_callback' ], // callback
			$this->page, // page
			'affiliate_marker_disclosure_section', // section
			[
				'label_for' => Links::$options_name_disclosure,
				'class' => 'large-text',
			],
		);

		add_settings_field(
			Links::$options_name_domains, // id
			__( 'Domains','td-affiliate-marker'), // title
			[ $this, 'domains_callback' ], // callback
			$this->page, // page
			'affiliate_marker_domain_section', // section
			[
				'label_for' => Links::$options_name_domains,
				'class' => 'large-text',
			],
		);
	}

	public function sanitize_disclosure( $input ) {
		return sanitize_text_field( $input );
	}

	public function sanitize_domains( $input ) {
		$domains_textarea = trim( esc_textarea( $input ) );
		$domains = (array) explode( PHP_EOL, $domains_textarea );
		$domains = array_map( 'trim', $domains );

		return $domains;
	}

	public function section_disclosure() {
		_e( 'The Disclosure, displayed at the end of each post which includes at least one Affiliate Link.','td-affiliate-marker');
	}

	public function section_domains() {
		_e( 'List of Domains and URL parts to detect Affiliate Links in your content. One per line. Can include full Domains e.g. "amazon.com" or just parts of it e.g. "amazon"','td-affiliate-marker');
	}

	public function disclosure_callback( $args ) {
		printf(
			'<input class="%s" type="text" name="%s" id="affiliate_marker_disclosure" value="%s">',
			$args['class'] ?? '',
			$args['label_for'] ?? '',
			$this->disclosure,
		);
	}

	public function domains_callback( $args ) {

		$domains = array_map( 'esc_textarea', $this->domains );

		printf(
			'<textarea class="%s" rows="30" name="%s" id="affiliate_marker_domains">%s</textarea>',
			$args['class'] ?? '',
			$args['label_for'] ?? '',
			implode( PHP_EOL, $domains ),
		);
	}

}
