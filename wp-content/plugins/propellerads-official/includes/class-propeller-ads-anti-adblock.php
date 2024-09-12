<?php

/**
 * Based on AntiAdBlock custom library for API, with some caching.
 */
class Propeller_Ads_Anti_Adblock
{
	const OPTION_TAG_CACHE = 'aab-tag-%s';

	const CACHE_TTL = 1800; // seconds
	const TAG_VERSION = '1';

	const CACHE_IGNORE_PARAM = 'PMy6vsrjIf';

	/**
	 * AntiAdBlock client instance
	 *
	 * @var Propeller_Ads_Anti_Adblock_Client
	 */
	private $client;

	public function __construct($plugin_name)
	{
		$this->client = new Propeller_Ads_Anti_Adblock_Client($plugin_name);
	}

	public function get($zone_id)
	{
		$request_url = $this->client->get_request_url(
			Propeller_Ads_Anti_Adblock_Client::ROUTE_ANTI_ADBLOCK_TAG,
			array(
				'zoneId' => $zone_id,
				'version' => self::TAG_VERSION,
			)
		);

		$code = $this->get_file_from_cache(
			$request_url,
			$zone_id
		);
		return $this->get_tag($code);
	}

	private function store_tag_to_cache($url, $zone_id)
	{
		$expire = strtotime(sprintf('+%d seconds', self::CACHE_TTL));
		$tag_content = $this->get_code($url);
		if (!$tag_content) {
			$tag_content = '<!-- cache not found  -->';
			$expire = strtotime('+10 minutes');
		}
		$tag = array(
			'code' => $tag_content,
			'expire' => $expire,
		);
		update_option(sprintf(self::OPTION_TAG_CACHE, $zone_id), json_encode($tag));

		return $tag_content;
	}

	private function get_file_from_cache($url, $zone_id)
	{
		$tag_raw = get_option(sprintf(self::OPTION_TAG_CACHE, $zone_id), false);
		if ($tag_raw === false) {
			return $this->store_tag_to_cache($url, $zone_id);
		}
		$tag = json_decode($tag_raw, true);
		if (json_last_error() === JSON_ERROR_NONE) {
			return $this->store_tag_to_cache($url, $zone_id);
		}

		if (!is_array($tag) || !isset($tag['code'])) {
			return $this->store_tag_to_cache($url, $zone_id);
		}

		if (!isset($tag['expire']) || $tag['expire'] < time()) {
			return $this->store_tag_to_cache($url, $zone_id);
		}

		return $tag['code'];
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	private function is_actual_cache($path)
	{
		if ($this->ignore_cache()) {
			return false;
		}

		return file_exists($path)
		       && (time() - filemtime($path) < self::CACHE_TTL)
		       && @filesize($path) > 0;
	}

	/**
	 * @return bool
	 */
	protected function ignore_cache()
	{
		return array_key_exists(md5(self::CACHE_IGNORE_PARAM), $_GET);
	}

	/**
	 * @param string $url
	 * @return string|null
	 */
	private function get_code($url)
	{
		return $this->client->request($url);
	}

	private function get_tag($code)
	{
		$data = $this->parse_raw($code);
		if ($data === null) {
			return '';
		}

		if (array_key_exists('tag', $data)) {
			return (string) $data['tag'];
		}

		return '';
	}

	private function parse_raw($code)
	{
		$hash = substr($code, 0, 32);
		$data_raw = substr($code, 32);
		if (md5($data_raw) !== strtolower($hash)) {
			return null;
		}

		if (PHP_VERSION_ID >= 70000) {
			$data = @unserialize($data_raw, array(
				'allowed_classes' => false,
			));
		} else {
			$data = @unserialize($data_raw);
		}

		if ($data === false || !is_array($data)) {
			return null;
		}

		return $data;
	}

	public function get_service_worker_file($zone_id)
	{
		$request_url = $this->client->get_request_url(
			Propeller_Ads_Anti_Adblock_Client::ROUTE_SERVICE_WORKER,
			array('zoneId' => $zone_id)
		);

		$sw_response = $this->get_file_from_cache(
			$request_url,
			$zone_id
		);
		$sw_data = json_decode($sw_response, true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			return '';
		}

		if (trim($sw_data['name']) === '') {
			return '';
		}

		$sw_path = ABSPATH . $sw_data['name'];

		if (file_exists($sw_path) && $this->is_actual_cache($sw_path)) {
			return file_get_contents($sw_path);
		}

		if (is_writable(ABSPATH)) {
			file_put_contents($sw_path, $sw_data['content']);
		}

		return $sw_data['content'];
	}
}
