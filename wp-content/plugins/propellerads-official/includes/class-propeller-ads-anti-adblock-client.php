<?php

class Propeller_Ads_Anti_Adblock_Client
{
	const ROUTE_PUBLISHER_ZONES = '/v3/getPublisherZones';
	const ROUTE_SERVICE_WORKER = '/v3/getServiceWorker';
	const ROUTE_ANTI_ADBLOCK_TAG = '/v3/getTag';

	const TAG_DOMAIN = 'http://go.transferzenad.com';

	/**
	 * Settings helper instance
	 *
	 * @var Propeller_Ads_Settings_Helper
	 */
	private $settings_helper;

	public function __construct($plugin_name)
	{
		$this->settings_helper = new Propeller_Ads_Settings_Helper($plugin_name);
	}

	/**
	 * Get all publisher zones by token
	 *
	 * @return array|null
	 */
	public function get_publisher_zones()
	{
		update_option(Propeller_Ads_Zone_Helper::OPTION_NAME_PUBLISHER_ZONES_LAST_UPDATE, time());

		return $this->request(
			$this->get_request_url(self::ROUTE_PUBLISHER_ZONES),
			true
		);
	}

	public function get_request_url($endpoint, $params = array())
	{
		$params['token'] = $this->settings_helper->get_anti_adblock_token();

		if (!$params['token']) {
			return null;
		}

		return self::TAG_DOMAIN . $endpoint . '?' . http_build_query($params);
	}

	public function request($url, $decode = false)
	{
		if ($url === null) {
			return null;
		}

		$args = array(
			'headers' => array(
				'user-agent' => 'WordPress/' . get_bloginfo('version') . '; ' . home_url(),
			),
		);

		$response = wp_remote_get($url, $args);

		if (is_array($response)) {
			if ($decode) {
				$decodedData = json_decode($response['body'], true);

				if (json_last_error() === JSON_ERROR_NONE) {
					return $decodedData;
				}

				return null;
			}

			return $response['body'];
		}

		return null;
	}
}
