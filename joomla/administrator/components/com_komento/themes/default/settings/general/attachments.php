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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_ATTACHMENTS_GENERAL'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'upload_enable', 'COM_KOMENTO_SETTINGS_ATTACHMENT_ENABLE'); ?>
				<?php echo $this->html('settings.textbox', 'upload_path', 'COM_KOMENTO_SETTINGS_ATTACHMENT_CUSTOM_PATH');?>
				<?php echo $this->html('settings.textbox', 'upload_allowed_extension', 'COM_KOMENTO_SETTINGS_ATTACHMENT_ALLOWED_EXTENSION');?>
				<?php echo $this->html('settings.textbox', 'upload_max_file', 'COM_KOMENTO_SETTINGS_ATTACHMENT_MAX_FILE', '', array('postfix' => 'COM_KOMENTO_FILES'), '', 'input-mini text-center');?>
				<?php echo $this->html('settings.textbox', 'upload_max_size', 'COM_KOMENTO_SETTINGS_ATTACHMENT_MAX_SIZE', '', array('postfix' => 'COM_KOMENTO_MB'), '', 'input-mini text-center');?>
				<div class="form-group">
					<div class="col-md-7 col-md-offset-5 help-block">
						<span class="small">
							<?php echo JText::sprintf('COM_KOMENTO_SETTINGS_PHP_MAX_FILESIZE', ini_get('upload_max_filesize')); ?><br>
							<?php echo JText::sprintf('COM_KOMENTO_SETTINGS_PHP_MAX_POSTSIZE', ini_get('post_max_size')); ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
	</div>
</div>