<?php

class Propeller_Ads_Activator
{

	public static function activate()
	{
		self::setSchedulers();
	}

	public static function setSchedulers()
	{
		if (!wp_next_scheduled(Propeller_Ads::ACTION_SCHEDULE_ZONE_UPDATE)) {
			wp_schedule_event(time(), 'hourly', Propeller_Ads::ACTION_SCHEDULE_ZONE_UPDATE);
		}
	}

}
