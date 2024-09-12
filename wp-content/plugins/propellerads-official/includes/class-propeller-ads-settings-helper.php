<?php

/**
 * Helper functions for registering / rendering settings
 */
class Propeller_Ads_Settings_Helper
{
	// Field types
	const FIELD_TYPE_CHECKBOX = 'checkbox';
	const FIELD_TYPE_INPUT_TEXT = 'input_text';
	const FIELD_TYPE_DROPDOWN = 'dropdown';

	/**
	 * @var string $settings_page The slug-name of the settings page
	 */
	private $settings_page;

	/**
	 * @var string $settings_prefix Unique options prefix for plugin
	 */
	private $settings_prefix;

	public function __construct($settings_page)
	{
		$this->settings_page = $settings_page;
		$this->settings_prefix = str_replace('-', '_', $this->settings_page);
	}

	/**
	 * Get publisher AntiAdBlock token
	 *
	 * @return string
	 */
	public function get_anti_adblock_token()
	{
		return $this->get_field_value('general', 'token');
	}

	/**
	 * Store publisher AntiAdBlock token
	 *
	 * @param string $value
	 */
	public function set_anti_adblock_token($value)
	{
		$this->set_field_value('general', 'token', $value);
		$this->clear_settings();
	}

	/**
	 * Get site verification code
	 *
	 * @return string|false
	 */
	public function get_verification_code()
	{
		return $this->get_field_value('general', 'verification_code');
	}

	/**
	 * Store site verification code
	 *
	 * @param string $value
	 */
	public function set_verification_code($value)
	{
		$this->set_field_value('general', 'verification_code', $value);
	}

	public function is_ads_disabled_for_authorized_users()
	{
		return $this->get_field_value('general', 'logged_in_disabled');
	}

	/**
	 * Get field (option) value
	 *
	 * @param int $section_id
	 * @param int $field_id
	 *
	 * @return mixed    Option value
	 */
	public function get_field_value($section_id, $field_id)
	{
		return get_option($this->get_field_id($section_id, $field_id));
	}

	/**
	 * Delete field (option)
	 *
	 * @param int $section_id
	 * @param int $field_id
	 *
	 * @return mixed    Option value
	 */
	public function delete_field($section_id, $field_id)
	{
		return delete_option($this->get_field_id($section_id, $field_id));
	}

	public function get_field_id($section_id, $field_id)
	{
		return sprintf('%s_%s_%s', $this->settings_prefix, $section_id, $field_id);
	}

	/**
	 * Add settings section to plugin settings page
	 *
	 * @param array $config Key-value config (id, title)
	 */
	public function add_section($config)
	{
		add_settings_section(
			$this->get_section_id($config['id']),
			__($config['title'], $this->settings_page),   // TODO: is it ok for i18n tools?
			array($this, 'render_section'),   // TODO: Do we need to configure callback?
			$this->settings_page
		);
	}

	private function get_section_id($id)
	{
		return sprintf('%s_%s', $this->settings_prefix, $id);
	}

	/**
	 * Register setting and setup field rendering / sanitization
	 *
	 * @param array $config Key-value config (type, id, title, section)
	 */
	public function add_field($config)
	{
		$field_id = $this->get_field_id($config['section'], $config['id']);
		$renderer_name = 'render_' . $config['type'];
		$args = array_merge($config, array(
			'id' => $field_id,
			'label_for' => $field_id,
			'value' => $this->get_field_value($config['section'], $config['id']),
		));

		add_settings_field(
			$field_id,
			__(isset($config['title']) ? $config['title'] : '', $this->settings_page),
			array($this, $renderer_name),
			$this->settings_page,
			$this->get_section_id($config['section']),
			$args
		);

		register_setting(
			$this->settings_page,
			$field_id,
			$this->get_sanitize_callback($config['type'])
		);

		if (isset($config['validate']) && $config['validate'] === true) {
			register_setting(
				$this->settings_page,
				$field_id,
				array($this, 'validate_' . $field_id)
			);
		}
	}

	private function get_sanitize_callback($type)
	{
		if ($type === self::FIELD_TYPE_CHECKBOX) {
			return 'intval';
		}

		return '';
	}

	/**
	 * Render WP setting sections
	 * Callback function
	 *
	 * @param $page
	 */
	public function do_settings_sections($page)
	{
		global $wp_settings_sections, $wp_settings_fields;

		if (!isset($wp_settings_sections[$page])) {
			return;
		}

		foreach ((array)$wp_settings_sections[$page] as $section) {
			echo '<div class="card">';
			if (!empty($section['title'])) {
				echo '<h2>' . esc_html($section['title']) . '</h2>';
			}

			if (!empty($section['callback'])) {
				call_user_func($section['callback'], $section);
			}

			if (!isset($wp_settings_fields[$page][$section['id']]) || $wp_settings_fields === null) {
				continue;
			}

			$this->do_settings_fields($page, $section['id']);
			echo '</div>';
		}
	}

	/**
	 * Render WP settings field
	 * Callback function
	 *
	 * @param $page
	 * @param $section
	 */
	public function do_settings_fields($page, $section)
	{
		global $wp_settings_fields;

		if (!isset($wp_settings_fields[$page][$section])) {
			return;
		}

		echo '<table class="form-table">';

		foreach ((array)$wp_settings_fields[$page][$section] as $field) {
			echo '<tr id="row_' . esc_attr($field['id']) . '"' . ( !empty($field['args']['class']) ? ' class="' . esc_attr($field['args']['class']) . '"' : '' ) . '>';

			if (!empty($field['title'])) {
				if (!empty($field['args']['label_for'])) {
					echo '<th scope="row"><label for="' . esc_attr($field['args']['label_for']) . '">' . esc_html($field['title']) . '</label></th>';
				} else {
					echo '<th scope="row">' . esc_html($field['title']) . '</th>';
				}

				echo '<td>';
				call_user_func($field['callback'], $field['args']);
				echo '</td>';
			} else {
				echo '<th colspan="2">';
				call_user_func($field['callback'], $field['args']);
				echo '</th>';
			}

			echo '</tr>';
		}

		echo '</table>';
	}

	public function render_section()
	{
		echo '';
	}

	public function render_checkbox($args)
	{
		?>
		<input type="checkbox"
		       id="<?php echo esc_attr( $args['id'] ); ?>"
		       name="<?php echo esc_attr( $args['id'] ); ?>"
		       value="1"
			<?php checked( esc_attr($args['value'] ), 1); ?>
		/>
		<label for="<?php echo esc_attr( $args['id'] ); ?>">
			<?php esc_html_e( $args['checkbox_label'], 'propeller-ads' ); ?>
		</label>
		<?php if ( !empty( $args['description'] ) ): ?>
		<p class="description">
			<?php esc_html_e( $args['description'], 'propeller-ads' ); ?>
		</p>
		<?php endif;
	}

	public function render_input_text($args)
	{
		?>
		<input type="text"
		       id="<?php echo esc_attr( $args['id'] ); ?>"
		       name="<?php echo esc_attr( $args['id'] ); ?>"
		       value="<?php echo esc_attr( $args['value'] ); ?>"
		       class="regular-text"
		/>
		<?php if ( !empty( $args['description'] ) ): ?>
		<p class="description">
			<?php esc_html_e( $args['description'], 'propeller-ads' ) ?>
		</p>
		<?php endif;
	}

	public function render_dropdown($args)
	{
		?>
		<select
		       id="<?php echo esc_attr( $args['id'] ); ?>"
		       name="<?php echo esc_attr( $args['id'] ); ?>"
		       style="width:100%"
		>
		<?php foreach ( $args['options'] as $option ): ?>
			<option
				value="<?php echo esc_attr( $option['value'] ); ?>"
				<?php selected( esc_attr( $args['value'] ), esc_attr( $option['value'] ) ); ?>
			>
				<?php esc_html_e( $option['title'], 'propeller-ads' ); ?>
			</option>
		<?php endforeach; ?>
		</select>
		<?php if ( !empty( $args['description'] ) ): ?>
		<p class="description">
			<?php esc_html_e( $args['description'], 'propeller-ads' ) ?>
		</p>
		<?php endif;
	}

	/**
	 * Set field (option) value
	 *
	 * @param int    $section_id
	 * @param int    $field_id
	 * @param string $value
	 */
	public function set_field_value($section_id, $field_id, $value)
	{
		update_option($this->get_field_id($section_id, $field_id), $value);
	}

	/**
	 * Delete options after update token
	 */
	public function clear_settings()
	{
		$this->delete_field(Propeller_Ads_Zone_Helper::DIRECTION_PUSH_NOTIFICATION, 'enabled');
		$this->delete_field(Propeller_Ads_Zone_Helper::DIRECTION_ONCLICK, 'enabled');
		$this->delete_field(Propeller_Ads_Zone_Helper::DIRECTION_INTERSTITIAL, 'enabled');

		$this->delete_field(Propeller_Ads_Zone_Helper::DIRECTION_PUSH_NOTIFICATION, 'zone_id');
		$this->delete_field(Propeller_Ads_Zone_Helper::DIRECTION_ONCLICK, 'zone_id');
		$this->delete_field(Propeller_Ads_Zone_Helper::DIRECTION_INTERSTITIAL, 'zone_id');
	}

	/*** FORM VALIDATION ***/

	/**
	 * @param string $input
	 * @return string
	 */
	public function validate_propeller_ads_general_token($input)
	{
		if ($input !== '' && !preg_match('/^[a-f0-9]{40}$/', $input)) {
			Propeller_Ads_Messages::add_message(__('Error. Incorrect API token. Maybe you wrote zone id instead?', 'propeller-ads'), Propeller_Ads_Messages::TYPE_ERROR);

			return $this->get_anti_adblock_token();
		}

		return $input;
	}

	public function get_last_zone_update()
	{
		return (int) get_option(Propeller_Ads_Zone_Helper::OPTION_NAME_PUBLISHER_ZONES_LAST_UPDATE);
	}
}
