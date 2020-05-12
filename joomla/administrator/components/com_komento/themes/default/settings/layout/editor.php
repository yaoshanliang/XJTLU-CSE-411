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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_FORM'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'enable_login_form', 'COM_KOMENTO_SETTINGS_ENABLE_LOGIN_FORM'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_LOGIN_PROVIDER'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'login_provider', $this->config->get('login_provider'), array(
							array('value' => 'joomla', 'text' => 'COM_KOMENTO_SETTINGS_LOGIN_PROVIDER_JOOMLA'),
							array('value' => 'easysocial', 'text' => 'COM_KOMENTO_SETTINGS_LOGIN_PROVIDER_EASYSOCIAL'),
							array('value' => 'cb', 'text' => 'COM_KOMENTO_SETTINGS_LOGIN_PROVIDER_COMMUNITYBUILDER'),
							array('value' => 'jomsocial', 'text' => 'COM_KOMENTO_SETTINGS_LOGIN_PROVIDER_JOMSOCIAL')
						)); ?>
					</div>
				</div>

				<?php echo $this->html('settings.toggle', 'enable_subscription', 'COM_KOMENTO_SETTINGS_SUBSCRIPTION_ENABLE'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_TNC_ENABLE'); ?>

					<div class="col-md-7">
						<?php echo $this->html('tree.groups', 'show_tnc', $this->config->get('show_tnc'), array()); ?>
					</div>
				</div>

 
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_TNC_TEXT'); ?>

					<div class="col-md-7">
						<textarea name="tnc_text" class="o-form-control" cols="25" rows="15"><?php echo str_replace('<br />', "\n", $this->config->get('tnc_text')); ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">		
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_FORM_FIELDS'); ?>
			<div class="panel-body">

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_SHOW_NAME'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'show_name', $this->config->get('show_name'), array(
							array('value' => '0', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_OFF'),
							array('value' => '1', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_GUEST'),
							array('value' => '2', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_ALL')
						)); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_REQUIRE_NAME'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'require_name', $this->config->get('require_name'), array(
							array('value' => '0', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_OFF'),
							array('value' => '1', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_GUEST'),
							array('value' => '2', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_ALL')
						)); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_SHOW_EMAIL'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'show_email', $this->config->get('show_email'), array(
							array('value' => '0', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_OFF'),
							array('value' => '1', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_GUEST'),
							array('value' => '2', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_ALL')
						)); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_REQUIRE_EMAIL'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'require_email', $this->config->get('require_email'), array(
							array('value' => '0', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_OFF'),
							array('value' => '1', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_GUEST'),
							array('value' => '2', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_ALL')
						)); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_SHOW_WEBSITE'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'show_website', $this->config->get('show_website'), array(
							array('value' => '0', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_OFF'),
							array('value' => '1', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_GUEST'),
							array('value' => '2', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_ALL')
						)); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_REQUIRE_WEBSITE'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'require_website', $this->config->get('require_website'), array(
							array('value' => '0', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_OFF'),
							array('value' => '1', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_GUEST'),
							array('value' => '2', 'text' => 'COM_KOMENTO_SETTINGS_SHOW_FIELD_ALL')
						)); ?>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
