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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_FORM_BBCODE'); ?>
			
			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'enable_bbcode', 'COM_KOMENTO_SETTINGS_BBCODE_ENABLE'); ?>
				<?php echo $this->html('settings.toggle', 'enable_smileys', 'COM_KT_BBCODE_ENABLE_SMILEYS'); ?>
				<?php echo $this->html('settings.toggle', 'enable_backgrounds', 'COM_KT_BBCODE_ENABLE_BACKGROUND'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_hide_buttons', 'COM_KOMENTO_SETTINGS_BBCODE_HIDE_BUTTONS'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_bold', 'COM_KOMENTO_SETTINGS_BBCODE_BOLD'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_italic', 'COM_KOMENTO_SETTINGS_BBCODE_ITALIC'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_underline', 'COM_KOMENTO_SETTINGS_BBCODE_UNDERLINE'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_link', 'COM_KOMENTO_SETTINGS_BBCODE_LINK'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_picture', 'COM_KOMENTO_SETTINGS_BBCODE_PICTURE'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_video', 'COM_KOMENTO_SETTINGS_BBCODE_VIDEO'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_bulletlist', 'COM_KOMENTO_SETTINGS_BBCODE_BULLETLIST'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_numericlist', 'COM_KOMENTO_SETTINGS_BBCODE_NUMERICLIST'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_bullet', 'COM_KOMENTO_SETTINGS_BBCODE_BULLET'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_quote', 'COM_KOMENTO_SETTINGS_BBCODE_QUOTE'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_code', 'COM_KOMENTO_SETTINGS_BBCODE_CODE'); ?>
				<?php echo $this->html('settings.toggle', 'bbcode_gist', 'COM_KOMENTO_SETTINGS_BBCODE_GIST'); ?>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
	</div>
</div>
