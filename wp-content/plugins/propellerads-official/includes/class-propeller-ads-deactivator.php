<?php

class Propeller_Ads_Deactivator
{
	public static function deactivate()
	{
		wp_clear_scheduled_hook(Propeller_Ads::ACTION_SCHEDULE_ZONE_UPDATE);
	}
}
