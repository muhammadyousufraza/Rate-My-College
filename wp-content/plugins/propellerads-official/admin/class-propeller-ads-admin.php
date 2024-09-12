<?php

/**
 * The admin-specific functionality of the plugin.
 */
class Propeller_Ads_Admin
{
	// SSP domain for getting Anti AdBlock token
	const SSP_DOMAIN = 'https://publishers.propellerads.com';

	/**
	 * The ID of this plugin.
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The current version of this plugin.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Settings helper instance
	 *
	 * @var Propeller_Ads_Settings_Helper
	 */
	private $setting_helper;

	/**
	 * Zone helper instance
	 *
	 * @var Propeller_Ads_Zone_Helper
	 */
	private $zone_helper;

	/**
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->setting_helper = new Propeller_Ads_Settings_Helper($this->plugin_name);
		$this->zone_helper = new Propeller_Ads_Zone_Helper($this->plugin_name);
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/propeller-ads-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/propeller-ads-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Add an settings page to the main menu
	 */
	public function add_settings_page()
	{
		// TODO: check https://developer.wordpress.org/reference/functions/add_menu_page/#notes about capabilities
		add_menu_page(
			__('PropellerAds', 'propeller-ads'),
			__('PropellerAds', 'propeller-ads'),
			'administrator',
			$this->plugin_name,
			array($this, 'display_options_page'),
			'none',
			76  // right after the 'Tools' submenu
		);
	}

	/**
	 * Render the options page
	 */
	public function display_options_page()
	{
		include_once 'partials/propeller-ads-admin-display.php';
	}

	/**
	 * Register all plugin settings
	 */
	public function register_settings()
	{
		$this->setting_helper->add_section(array(
			'id' => 'general',
			'title' => __('General', 'propeller-ads'),
		));

		$this->setting_helper->add_field(array(
			'section' => 'general',
			'id' => 'logged_in_disabled',
			'title' => __('Membership', 'propeller-ads'),
			'type' => Propeller_Ads_Settings_Helper::FIELD_TYPE_CHECKBOX,
			'checkbox_label' => __('Disable ads for logged in users', 'propeller-ads'),
			'description' => __('You can disable ads for all registered users (and administrators).', 'propeller-ads'),
		));

		$this->setting_helper->add_field(array(
			'section' => 'general',
			'id' => 'token',
			'title' => __('Token', 'propeller-ads'),
			'type' => Propeller_Ads_Settings_Helper::FIELD_TYPE_INPUT_TEXT,
			'validate' => true,
			'description' => '<a href="' . $this->token_url() . '">' . __('Get or update token automatically', 'propeller-ads') . '</a>',
		));

		$zone_list = $this->zone_helper->get_publisher_zones_group_by_direction();

		foreach ($zone_list as $direction => $zones) {
			$this->setting_helper->add_section(array(
				'id' => $direction,
				'title' => $this->zone_helper->get_direction_title($direction),
			));

			$this->setting_helper->add_field(array(
				'section' => $direction,
				'id' => 'enabled',
				'title' => __('Activation', 'propeller-ads'),
				'type' => Propeller_Ads_Settings_Helper::FIELD_TYPE_CHECKBOX,
				'checkbox_label' => __('Allow ads on all pages', 'propeller-ads'),
			));

			$options = array();

			foreach ($zones as $zone) {
				$title = $zone['id'] . ' ';
				$title .= isset($zone['title']) ? $zone['title'] : $zone['name'];
				$title .= $zone['scheme'] ? ' - ' . $zone['scheme'] : '';

				$options[] = array(
					'value' => $zone['id'],
					'title' => $title,
				);
			}

			$this->setting_helper->add_field(array(
				'section' => $direction,
				'id' => 'zone_id',
				'options' => $options,
				'type' => Propeller_Ads_Settings_Helper::FIELD_TYPE_DROPDOWN,
			));
		}
	}

	/**
	 * Get url for getting AntiAdBlock token
	 *
	 * @return string
	 */
	public function token_url()
	{
		return self::SSP_DOMAIN . '/#/pub/sites/anti_adblock_token?return=' . base64_encode($this->plugin_url());
	}

	/**
	 * Gets plugin settings page url
	 *
	 * @return string
	 */
	public function plugin_url()
	{
		return admin_url('admin.php?page=' . $this->plugin_name);
	}

	/**
	 * Update settings page after update publisher zone list
	 * Wordpress action hook (admin_init)
	 */
	public function redirect_after_update()
	{
		if (isset($_GET['update-publisher-zones'])) {
			$this->zone_helper->update_publisher_zones();
			Propeller_Ads_Messages::add_message(__('Cache was removed. Synchronization of new zones may process some time. Please, repeat this action in 10 minutes. Thank you.', 'propeller-ads'));
			wp_redirect($this->plugin_url());
			exit();
		}

		if (isset($_GET['publisher-logout'])) {
			$this->setting_helper->delete_field('general', 'token');
			$this->setting_helper->clear_settings();
			Propeller_Ads_Messages::add_message(__('Logout successful', 'propeller-ads'));
			wp_redirect($this->plugin_url());
			exit();
		}
	}

	/**
	 * Save publisher Anti AdBlock after redirect from SSP
	 * Wordpress action hook (admin_init)
	 */
	public function auto_save_publisher_token()
	{
		if (isset($_GET['propeller-ads-aab-token'])) {
			$token = $this->setting_helper->get_anti_adblock_token();
			$value = sanitize_text_field($_GET['propeller-ads-aab-token']);

			if ($token !== $value) {
				$this->setting_helper->set_anti_adblock_token($value);
				$this->zone_helper->update_publisher_zones();
			}
			$this->auto_save_verification_code();

			wp_redirect($this->plugin_url());
			exit();
		}
	}

	private function auto_save_verification_code()
	{
		if (isset($_GET['propeller-ads-verification-code'])) {
			$value = sanitize_text_field($_GET['propeller-ads-verification-code']);
			$code = $this->setting_helper->get_verification_code();

			if ($code !== $value) {
				$this->setting_helper->set_verification_code($value);
			}
		}
	}

	public function error_notices()
	{
		if (!$this->setting_helper->get_anti_adblock_token()): ?>
			<?php if(isset($_GET['page']) && $_GET['page'] === $this->plugin_name): ?>
				<div class="error notice">
					<p><?php _e('Do you have a PropellerAds Publisher account? If not,', 'propeller-ads');?> <a
							href="https://propellerads.com/registration-publisher/"
							target="_blank"><strong><?php _e('register one', 'propeller-ads');?></strong></a> -
						<?php _e('it takes less than 3 minutes.', 'propeller-ads');?></p>
				</div>
			<?php endif; ?>
			<div class="error notice">
				<p><?php _e('PropellerAds plugin error. API token is missing.', 'propeller-ads');?>
					<a href="<?php echo $this->plugin_url() ?>"><?php _e('Fix it', 'propeller-ads');?></a></p>
			</div>
		<?php endif;

		if (!$this->setting_helper->get_field_value(Propeller_Ads_Zone_Helper::DIRECTION_PUSH_NOTIFICATION, 'enabled') &&
			!$this->setting_helper->get_field_value(Propeller_Ads_Zone_Helper::DIRECTION_ONCLICK, 'enabled') &&
			!$this->setting_helper->get_field_value(Propeller_Ads_Zone_Helper::DIRECTION_INTERSTITIAL, 'enabled')): ?>
			<div class="notice-warning notice">
				<p><?php _e('PropellerAds plugin warning. All ads directions are disabled. Deactivate plugin or', 'propeller-ads');?>
					<a href="<?php echo $this->plugin_url() ?>"><?php _e('fix this', 'propeller-ads');?></a></p>
			</div>
		<?php endif;
	}

	public function action_save_publisher_token()
	{
		// Clear all POST data after save publisher token
		unset($_POST);
		$this->setting_helper->clear_settings();
		$this->zone_helper->update_publisher_zones();
	}

	public function action_in_plugin_update()
	{
		$wp_list_table = _get_list_table('WP_Plugins_List_Table');

		printf(
			'<tr class="plugin-update-tr"><td colspan="%s" class="plugin-update update-message notice inline notice-warning notice-alt"><div class="update-message"><h4 style="margin: 0; font-size: 14px;">%s</h4>%s</div></td></tr>',
			$wp_list_table->get_column_count(),
			__('PropellerAds Official Plugin Update Info', 'propeller-ads'),
			__('WARNING! This is a brand new PropellerAds plugin version and its not compatible with old one. You\'ll must to relogin to PropellerAds SSP via plugin\'s page.', 'propeller-ads')
		);
	}

	/**
	 * Open session if it doesn't start
	 */
	public function register_session()
	{
		if (!session_id()) {
			session_start();
		}
	}

	public function zone_update_event()
	{
		if ($this->setting_helper->get_anti_adblock_token()) {
			$this->zone_helper->update_publisher_zones();
		}
	}
}
