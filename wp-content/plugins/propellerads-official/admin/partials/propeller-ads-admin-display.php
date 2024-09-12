<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 */

if (isset($_GET['settings-updated'])) {
	Propeller_Ads_Messages::add_message(__('Settings Updated', 'propeller-ads'));
}
Propeller_Ads_Messages::show_messages();
?>

<div class="wrap">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

	<form class="propeller-ads" action="options.php" method="post">
		<?php if ($this->setting_helper->get_anti_adblock_token()): ?>
			<p class="submit">
				<a href="<?php echo $this->plugin_url() ?>&update-publisher-zones"
				   id="update-zones"
				   title="<?php
				   $last_zone_update = $this->setting_helper->get_last_zone_update();
				   if ($last_zone_update) { ?><?php echo sprintf(__('Updated %s ago', 'propeller-ads'), human_time_diff($last_zone_update));?><?php } ?>"
				   class="button button-primary">
					<?php _e('Update zones list', 'propeller-ads');?>
				</a>
				&nbsp;
				<a href="<?php echo $this->plugin_url() ?>&publisher-logout"
				   id="plugin-logout"
				   class="button button-secondary"
				   onclick="return confirm('<?php esc_attr_e('Are you sure to logout? All installed tags will refused by logout.\n\nIf you are want to use another PropellerAds account, please logout or re-login in SSP before.', 'propeller-ads');?>')"
				>
					<?php _e('Logout from plugin', 'propeller-ads');?>
				</a>
			</p>
		<?php else: ?>
			<p class="submit">
				<a href="<?php echo $this->token_url() ?>" id="get-token"
				   class="button button-primary"><?php _e('Connect to PropellerAds SSP', 'propeller-ads');?></a>
			</p>
		<?php endif; ?>

		<?php settings_fields($this->plugin_name); ?>
		<div class="card-wrapper">
			<?php $this->setting_helper->do_settings_sections($this->plugin_name) ?>
		</div>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'propeller-ads');?>">
		</p>
	</form>
</div>

