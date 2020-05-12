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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_AKISMET'); ?>
			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'antispam_akismet', 'COM_KOMENTO_SETTINGS_AKISMET_ENABLE'); ?>
				<?php echo $this->html('settings.textbox', 'antispam_akismet_key', 'COM_KOMENTO_SETTINGS_AKISMET_API_KEY'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_ANTISPAM_REJECTION_TYPE'); ?>
					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'antispam_akismet_rejection_type', $this->config->get('antispam_akismet_rejection_type'), array(
							array('value' => 'high', 'text' => 'COM_KOMENTO_SETTINGS_ANTISPAM_REJECTION_TYPE_COMPLETE_BLOCK'),
							array('value' => 'low', 'text' => 'COM_KOMENTO_SETTINGS_ANTISPAM_REJECTION_TYPE_ALLOW_PASSTHROUGH')
						)); ?>

						<div class="help-block"><?php echo JText::_('COM_KOMENTO_SETTINGS_ANTISPAM_REJECTION_TYPE_INFO');?></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
	</div>
</div>