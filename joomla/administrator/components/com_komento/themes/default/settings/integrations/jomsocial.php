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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_ACTIVITIES_JOMSOCIAL'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'jomsocial_enable_comment', 'COM_KOMENTO_SETTINGS_ACTIVITIES_JOMSOCIAL_ENABLE_COMMENT'); ?>
				<?php echo $this->html('settings.toggle', 'jomsocial_enable_reply', 'COM_KOMENTO_SETTINGS_ACTIVITIES_JOMSOCIAL_ENABLE_REPLY'); ?>
				<?php echo $this->html('settings.toggle', 'jomsocial_enable_like', 'COM_KOMENTO_SETTINGS_ACTIVITIES_JOMSOCIAL_ENABLE_LIKE'); ?>
				<?php echo $this->html('settings.textbox', 'jomsocial_comment_length', 'COM_KOMENTO_SETTINGS_ACTIVITIES_JOMSOCIAL_COMMENT_LENGTH', '', array('postfix' => 'COM_KOMENTO_CHARACTERS'), '', 'input-mini text-center');?>
				<?php echo $this->html('settings.toggle', 'jomsocial_enable_userpoints', 'COM_KOMENTO_SETTINGS_ACTIVITIES_JOMSOCIAL_ENABLE_USERPOINTS'); ?>
			</div>
		</div>
	</div>
</div>

