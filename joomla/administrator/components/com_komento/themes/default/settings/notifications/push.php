<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="row">
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_PUSH_ONESIGNAL'); ?>
			<div class="panel-body">
				<p><?php echo JText::_('COM_KOMENTO_SETTINGS_PUSH_ONESIGNAL_ABOUT'); ?></p>
				<div class="t-lg-mb--md">
					<a href="https://onesignal.com/?from=komento" class="btn btn-kt-primary btn-sm t-lg-mr--sm" target="_blank">Register Now</a>
					<a href="https://stackideas.com/docs/komento" class="btn btn-kt-default-o btn-sm t-lg-mr--sm" target="_blank">Documentation</a>
				</div>

				<?php echo $this->html('settings.toggle', 'onesignal_enabled', 'COM_KOMENTO_SETTINGS_ONESIGNAL_ENABLE'); ?>
				<?php echo $this->html('settings.toggle', 'onesignal_show_welcome', 'COM_KT_SETTINGS_ONESIGNAL_DISPLAY_WELCOME_MESSAGE'); ?>
				<?php echo $this->html('settings.textbox', 'onesignal_app_id', 'COM_KOMENTO_SETTINGS_ONESIGNAL_APP_ID'); ?>
				<?php echo $this->html('settings.textbox', 'onesignal_api_key', 'COM_KOMENTO_SETTINGS_ONESIGNAL_REST_API_KEY'); ?>
				<?php echo $this->html('settings.textbox', 'onesignal_subdomain', 'COM_KOMENTO_SETTINGS_ONESIGNAL_SUBDOMAIN', '', array(), 'COM_KOMENTO_SETTINGS_ONESIGNAL_SUBDOMAIN_NOTE'); ?>
				<?php echo $this->html('settings.textbox', 'onesignal_safari_id', 'COM_KOMENTO_SETTINGS_ONESIGNAL_SAFARI_WEB_ID'); ?>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
	</div>
</div>