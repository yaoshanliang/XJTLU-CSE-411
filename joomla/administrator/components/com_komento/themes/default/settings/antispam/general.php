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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_ANTISPAM_GENERAL'); ?>

			<div class="panel-body">
				
				<?php echo $this->html('settings.toggle', 'antispam_min_length_enable', 'COM_KOMENTO_SETTINGS_COMMENT_MINIMUM_LENGTH_CHECK_ENABLE'); ?>
				<?php echo $this->html('settings.textbox', 'antispam_min_length', 'COM_KOMENTO_SETTINGS_MINIMUM_COMMENT_LENGTH', '', array('postfix' => 'COM_KOMENTO_CHARACTERS'), '', 'input-mini text-center');?>
				<?php echo $this->html('settings.toggle', 'antispam_max_length_enable', 'COM_KOMENTO_SETTINGS_COMMENT_MAXIMUM_LENGTH_CHECK_ENABLE'); ?>
				<?php echo $this->html('settings.textbox', 'antispam_max_length', 'COM_KOMENTO_SETTINGS_MAXIMUM_COMMENT_LENGTH', '', array('postfix' => 'COM_KOMENTO_CHARACTERS'), '', 'input-mini text-center');?>
				<?php echo $this->html('settings.toggle', 'antispam_flood_control', 'COM_KOMENTO_SETTINGS_FLOOD_CONTROL_ENABLE'); ?>
				<?php echo $this->html('settings.textbox', 'antispam_flood_interval', 'COM_KOMENTO_SETTINGS_FLOOD_INTERVAL', '', array('postfix' => 'COM_KOMENTO_SECONDS'), '', 'input-mini text-center');?>
				<?php echo $this->html('settings.toggle', 'filter_word', 'COM_KOMENTO_SETTINGS_WORD_CENSORING_ENABLE'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_WORDS_TO_CENSOR'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.textarea', 'filter_word_text', $this->config->get('filter_word_text')); ?>

						<div class="help-block"><?php echo JText::_('COM_KOMENTO_SETTINGS_WORDS_TO_CENSOR_ADVANCE');?></div>
					</div>
				</div>

			</div>
		</div>

	</div>

	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_IPADDRESS_BLACKLIST'); ?>
			
			<div class="panel-body">
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_IP_ADDRESSES'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.textarea', 'blacklist_ip', $this->config->get('blacklist_ip')); ?>
						<div class="help-block"><?php echo JText::_('COM_KOMENTO_SETTINGS_IP_ADDRESSES_INFO'); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>