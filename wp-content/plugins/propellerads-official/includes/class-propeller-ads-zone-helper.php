<?php

class Propeller_Ads_Zone_Helper
{
	const OPTION_NAME_PUBLISHER_ZONES = 'propeller-ads-option-publisher-zones';
	const OPTION_NAME_PUBLISHER_ZONES_LAST_UPDATE = 'propeller-ads-option-publisher-zones-last-update';

	const DIRECTION_ONCLICK = 'onclick';
	const DIRECTION_INTERSTITIAL = 'interstitial';
	const DIRECTION_PUSH_NOTIFICATION = 'nativeads';

	private static $allowed_directions = array(
		self::DIRECTION_ONCLICK,
		self::DIRECTION_INTERSTITIAL,
		self::DIRECTION_PUSH_NOTIFICATION,
	);

	private static $direction_titles = array(
		self::DIRECTION_ONCLICK => 'Onclick / Popunder Ads',
		self::DIRECTION_INTERSTITIAL => 'Mobile Interstitial',
		self::DIRECTION_PUSH_NOTIFICATION => 'Push Notification',
	);

	/**
	 * AntiAdBlock client instance
	 *
	 * @var Propeller_Ads_Anti_Adblock_Client
	 */
	private $aab_client;

	public function __construct($plugin_name)
	{
		$this->aab_client = new Propeller_Ads_Anti_Adblock_Client($plugin_name);
	}

	/**
	 * Update publisher zone list and store it in database
	 *
	 * @return bool
	 */
	public function update_publisher_zones()
	{
		$zoneList = $this->aab_client->get_publisher_zones();

		if ($zoneList !== null) {
			return update_option(self::OPTION_NAME_PUBLISHER_ZONES, json_encode($this->filter_zone_list($zoneList)));
		}

		return delete_option(self::OPTION_NAME_PUBLISHER_ZONES);
	}

	/**
	 * Remove zones with not allowed ads direction
	 *
	 * @param array $zoneList
	 * @return array
	 */
	private function filter_zone_list($zoneList)
	{
		foreach ($zoneList as $site => $directions) {
			foreach ($directions as $direction_name => $zones) {
				if (!in_array($direction_name, self::$allowed_directions, true)) {
					unset($zoneList[$site][$direction_name]);
				}
			}
		}

		return $zoneList;
	}

	/**
	 * Gets all publisher zones with allowed directions group by ads direction
	 * direction_name => [
	 *   [
	 *     zone_id,
	 *     zone_name,
	 *     direction_name,
	 *     is_antiadblock,
	 *     source_zone,
	 *   ],
	 *   ...
	 * ],
	 * ...
	 *
	 * @return array
	 */
	public function get_publisher_zones_group_by_direction()
	{
		$zoneList = $this->get_publisher_zones();
		$groupZones = array();

		foreach ($zoneList as $site => $directions) {
			foreach ($directions as $direction_name => $zones) {
				if (!isset($groupZones[$direction_name])) {
					$groupZones[$direction_name] = array();
				}

				foreach ($zones as $zone) {
					$groupZones[$direction_name][] = $zone;
				}
			}
		}

		return $groupZones;
	}

	/**
	 * Gets all publisher zones with allowed directions
	 * [
	 *    site_name =>
	 *      direction_name => [
	 *      [
	 *          zone_id,
	 *          zone_name,
	 *          direction_name,
	 *          is_antiadblock,
	 *          source_zone,
	 *      ],
	 *      ...
	 *    ]
	 *    ...
	 * ],
	 * ...
	 *
	 * @return array
	 */
	public function get_publisher_zones()
	{
		$zoneList = get_option(self::OPTION_NAME_PUBLISHER_ZONES);

		if ($zoneList) {
			$zoneList = json_decode($zoneList, true);
		} else {
			$zoneList = $this->aab_client->get_publisher_zones();
			if ($zoneList !== null) {
				update_option(self::OPTION_NAME_PUBLISHER_ZONES, json_encode($this->filter_zone_list($zoneList)));
			} else {
				return array();
			}
		}

		return $zoneList;
	}

	/**
	 * Check zone is Anti AdBlock zone
	 *
	 * @param $zoneId
	 * @return bool
	 */
	public function is_anti_adblock_zone($zoneId)
	{
		$zoneList = $this->get_publisher_zone_list();

		if (array_key_exists($zoneId, $zoneList)) {
			return (bool) $zoneList[$zoneId]['is_antiadblock'];
		}

		return false;
	}

	/**
	 * Gets all publisher zones with allowed directions
	 * [
	 *   zone_id,
	 *   zone_name,
	 *   direction_name,
	 *   is_antiadblock,
	 *   source_zone,
	 * ],
	 * ...
	 *
	 * @return array
	 */
	public function get_publisher_zone_list()
	{
		$zoneList = $this->get_publisher_zones();
		$result = array();

		foreach ($zoneList as $site => $directions) {
			foreach ($directions as $direction_name => $zones) {
				foreach ($zones as $zone) {
					$result[$zone['id']] = $zone;
				}
			}
		}

		return $result;
	}

	/**
	 * Get direction human-friendly title
	 *
	 * @param string $key
	 * @return string
	 */
	public function get_direction_title($key)
	{
		return isset(self::$direction_titles[$key]) ? self::$direction_titles[$key] : 'Unknown';
	}

	public function get_allowed_directions()
	{
		return self::$allowed_directions;
	}
}
