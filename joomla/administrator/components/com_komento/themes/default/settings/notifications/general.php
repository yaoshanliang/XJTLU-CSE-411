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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_NOTIFICATION_GENERAL'); ?>
			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'notification_enable', 'COM_KOMENTO_SETTINGS_NOTIFICATION_ENABLE'); ?>
				<?php echo $this->html('settings.toggle', 'notification_sendmailonpageload', 'COM_KOMENTO_SETTINGS_SEND_MAIL_ON_PAGE_LOAD'); ?>
				<?php echo $this->html('settings.textbox', 'notification_total_email', 'COM_KOMENTO_SETTINGS_TOTAL_EMAIL_TO_SEND', '', array('postfix' => 'COM_KOMENTO_EMAILS'), '', 'input-mini text-center'); ?>
				<?php echo $this->html('settings.toggle', 'subscription_auto', 'COM_KOMENTO_SETTINGS_SUBSCRIPTION_AUTO'); ?>
				<?php echo $this->html('settings.toggle', 'subscription_confirmation', 'COM_KOMENTO_SETTINGS_SUBSCRIPTION_CONFIRMATION'); ?>
				<?php echo $this->html('settings.toggle', 'notification_event_new_comment', 'COM_KOMENTO_SETTINGS_NOTIFICATION_EVENT_NEW_COMMENT'); ?>
				<?php echo $this->html('settings.toggle', 'notification_event_new_reply', 'COM_KOMENTO_SETTINGS_NOTIFICATION_EVENT_NEW_REPLY'); ?>
				<?php echo $this->html('settings.toggle', 'notification_event_new_pending', 'COM_KOMENTO_SETTINGS_NOTIFICATION_EVENT_NEW_PENDING'); ?>
				<?php echo $this->html('settings.toggle', 'notification_event_new_pending_author', 'COM_KT_NOTIFY_AUTHOR_FOR_PENDING_COMMENTS'); ?>
				<?php echo $this->html('settings.toggle', 'notification_event_reported_comment', 'COM_KOMENTO_SETTINGS_NOTIFICATION_EVENT_REPORTED_COMMENT'); ?>
				<?php echo $this->html('settings.toggle', 'custom_email_logo', 'COM_KOMENTO_SETTINGS_NOTIFICATIONS_CUSTOM_EMAIL_LOGO', '', 'data-custom-email-logo'); ?>

				<div class="form-group <?php echo $this->config->get('custom_email_logo') ? '' : 't-hidden' ?>" data-email-logo-wrapper>
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_NOTIFICATIONS_CUSTOM_EMAILS_LOGO'); ?>

					<div class="col-md-7" data-email-logo data-id="" data-default-email-logo="<?php echo KT::notification()->getLogo(true); ?>">
						<div class="t-lg-mb--lg">
							<div class="kt-img-holder">
								<div class="kt-img-holder__remove" data-email-logo-restore-default-wrap <?php echo KT::notification()->hasOverrideLogo() ? '' : 'style="display: none;'; ?>>
									<a href="javascript:void(0);" class="" data-email-logo-restore-default-button>
										<i class="fa fa-times"></i>&nbsp; <?php echo JText::_('COM_KOMENTO_DELETE'); ?>
									</a>
								</div>
								<img src="<?php echo KT::notification()->getLogo(); ?>" width="200" data-email-logo-image />
							</div>
							
						</div>

						<div>
							<input type="file" name="email_logo" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_NOTIFICATION_RECIPIENTS'); ?>
			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'notification_to_author', 'COM_KOMENTO_SETTINGS_NOTIFICATION_TO_AUTHOR'); ?>
				<?php echo $this->html('settings.toggle', 'notification_to_subscribers', 'COM_KOMENTO_SETTINGS_NOTIFICATION_TO_SUBSCRIBERS'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_NOTIFICATION_TO_USERGROUP_COMMENT'); ?>

					<div class="col-md-7">
						<?php echo $this->html('tree.groups' , 'notification_to_usergroup_comment', $this->config->get('notification_to_usergroup_comment'), array()); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_NOTIFICATION_TO_USERGROUP_REPLY'); ?>

					<div class="col-md-7">
						<?php echo $this->html('tree.groups' , 'notification_to_usergroup_reply', $this->config->get('notification_to_usergroup_reply'), array()); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_NOTIFICATION_TO_USERGROUP_PENDING'); ?>

					<div class="col-md-7">
						<?php echo $this->html('tree.groups' , 'notification_to_usergroup_pending', $this->config->get('notification_to_usergroup_pending'), array()); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_NOTIFICATION_TO_USERGROUP_REPORTED'); ?>

					<div class="col-md-7">
						<?php echo $this->html('tree.groups' , 'notification_to_usergroup_reported', $this->config->get('notification_to_usergroup_reported'), array()); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>