<?php
/**
* @package		EasyDiscuss
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyDiscuss is free software. This version may have been modified pursuant
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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_WORKFLOW_GENERAL'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'enable_komento', 'COM_KOMENTO_SETTINGS_ENABLE_SYSTEM'); ?>
				<?php echo $this->html('settings.toggle', 'enable_reply', 'COM_KOMENTO_SETTINGS_REPLY_ENABLE'); ?>
				<?php echo $this->html('settings.textbox', 'max_threaded_level', 'COM_KOMENTO_SETTINGS_COMMENTS_MAX_THREADED_LEVEL', '', array('postfix' => 'Levels'), '', 'input-mini text-center');?>
				<?php echo $this->html('settings.toggle', 'enable_report', 'COM_KOMENTO_SETTINGS_REPORT_ENABLE'); ?>
				<?php echo $this->html('settings.toggle', 'enable_likes', 'COM_KOMENTO_SETTINGS_LIKES_ENABLE'); ?>
				<?php echo $this->html('settings.toggle', 'enable_ratings', 'COM_KOMENTO_SETTINGS_RATINGS_ENABLE'); ?>
				<?php echo $this->html('settings.toggle', 'enable_minimize', 'COM_KT_ENABLE_MINIMIZE_COMMENTS'); ?>
				<?php echo $this->html('settings.toggle', 'enable_rss', 'COM_KOMENTO_SETTINGS_RSS_ENABLE'); ?>
				<?php echo $this->html('settings.textbox', 'rss_max_items', 'COM_KOMENTO_SETTINGS_RSS_MAX_ITEMS', '', array('postfix' => 'COM_KOMENTO_COMMENTS'), '', 'input-mini text-center');?>
				<?php echo $this->html('settings.textbox', 'orphanitem_ownership', 'COM_KOMENTO_SETTINGS_ORPHANITEM_OWNERSHIP', '', array(), '', 'input-mini text-center');?>
			</div>
		</div>

		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KT_USER_DOWNLOAD'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'enable_gdpr_download', 'COM_KT_USER_ALLOW_DOWNLOAD'); ?>
				<?php echo $this->html('settings.textbox', 'userdownload_expiry', 'COM_KT_USER_DOWNLOAD_EXPIRY', '', array(), '', 'input-mini text-center');?>
				
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_LIVE_NOTIFICATIONS'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'enable_live_notification', 'COM_KOMENTO_SETTINGS_ADVANCE_ENABLE_LIVE_NOTIFICATION'); ?>
				<?php echo $this->html('settings.textbox', 'live_notification_interval', 'COM_KOMENTO_SETTINGS_ADVANCE_LIVE_NOTIFICATION_INTERVAL', '', array('postfix' => 'COM_KOMENTO_SECONDS'), '', 'input-mini text-center');?>
			</div>
		</div>

		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_MODERATION'); ?>
			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'enable_moderation', 'COM_KOMENTO_SETTINGS_ENABLE_MODERATION'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_MODERATION_USERGROUP'); ?>

					<div class="col-md-7">
						<?php echo $this->html('tree.groups', 'requires_moderation', $this->config->get('requires_moderation'), array()); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>