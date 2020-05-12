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
	<div class="col-md-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_ADVANCED'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'komento_jquery', 'COM_KOMENTO_SETTINGS_LOAD_JQUERY'); ?>
				<?php echo $this->html('settings.toggle', 'komento_ajax_index', 'COM_KOMENTO_SETTINGS_USE_INDEX'); ?>
				<?php echo $this->html('settings.textbox', 'komento_cdn_url', 'COM_KOMENTO_SETTINGS_CDN_URL'); ?>

				<?php echo $this->html('settings.toggle', 'secure_cron', 'COM_KT_SETTINGS_SECURE_CRON', '', 'data-secure-cron'); ?>
				
				<div class="form-group <?php echo $this->config->get('secure_cron') ? '' : 't-hidden';?>" data-secure-cron-settings>
					<?php echo $this->html('panel.label', 'COM_KT_SETTINGS_SECURE_CRON_KEY'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.inputbox', 'secure_cron_key', $this->config->get('secure_cron_key')); ?>

						<div class="help-block">
							<?php echo JText::_('COM_KT_SETTINGS_SECURE_CRON_KEY_INFO'); ?>
						</div>

						<?php if ($this->config->get('secure_cron_key')) { ?>
						<div class="o-input-group">
							<div class="o-input-group__addon">
								<i class="fa fa-globe"></i>
							</div>
							<input type="text" class="o-form-control" value="<?php echo JURI::root() . 'index.php?option=com_komento&cron=true&phrase=' . $this->config->get('secure_cron_key');?>" />
						</div>
						<?php } ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_TRIGGERS_METHOD'); ?>
					
					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'trigger_method', $this->config->get('trigger_method'), array(
							array('value' => 'none', 'text' => 'COM_KOMENTO_SETTINGS_TRIGGERS_METHOD_NONE'),
							array('value' => 'component', 'text' => 'COM_KOMENTO_SETTINGS_TRIGGERS_METHOD_COMPONENT_PLUGIN'),
							array('value' => 'joomla', 'text' => 'COM_KOMENTO_SETTINGS_TRIGGERS_METHOD_JOOMLA_PLUGIN')
						)); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
	</div>
</div>