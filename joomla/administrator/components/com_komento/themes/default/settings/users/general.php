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
		<div class="panel form-horizontal">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_LAYOUT_USER_APPEARENCE'); ?>
			<div class="panel-body">
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_COMMENTS_NAME_TYPE'); ?>
					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'name_type', $this->config->get('name_type'), array(
							array('value' => 'default', 'text' => 'COM_KOMENTO_SETTINGS_NAME_TYPE_DEFAULT'),
							array('value' => 'username', 'text' => 'COM_KOMENTO_SETTINGS_NAME_TYPE_USERNAME'),
							array('value' => 'name', 'text' => 'COM_KOMENTO_SETTINGS_NAME_TYPE_NAME'),
							array('value' => 'easysocial', 'text' => 'EasySocial'),
							array('value' => 'easyblog', 'text' => 'EasyBlog'),
							array('value' => 'easydiscuss', 'text' => 'EasyDiscuss')
						)); ?>
					</div>
				</div>

				<?php echo $this->html('settings.toggle', 'layout_avatar_enable', 'COM_KOMENTO_LAYOUT_AVATAR_ENABLE'); ?>
				<?php echo $this->html('settings.toggle', 'layout_avatar_character', 'COM_KOMENTO_LAYOUT_AVATAR_CHARACTER_BASED', '', 'data-avatar-character-based'); ?>

				<div class="form-group <?php echo $this->config->get('layout_avatar_character') ? 't-hidden' : '';?>" data-avatar-integration-option>
					<?php echo $this->html('panel.label', 'COM_KOMENTO_LAYOUT_AVATAR_INTEGRATION'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'layout_avatar_integration', $this->config->get('layout_avatar_integration'), array(
							array('value' => 'default', 'text' => 'Default'),
							array('value' => 'easysocial', 'text' => 'EasySocial'),
							array('value' => 'easyblog', 'text' => 'EasyBlog'),
							array('value' => 'easydiscuss', 'text' => 'EasyDiscuss'),
							array('value' => 'communitybuilder', 'text' => 'Community Builder'),
							array('value' => 'k2', 'text' => 'K2'),
							array('value' => 'gravatar', 'text' => 'Gravatar'),
							array('value' => 'jomsocial', 'text' => 'Jomsocial'),
							array('value' => 'kunena', 'text' => 'Kunena'),
							array('value' => 'kunena3', 'text' => 'Kunena 3'),
							array('value' => 'phpbb', 'text' => 'PHPBB'),
							array('value' => 'hwdmediashare', 'text' => 'HWDMediaShare'),
							array('value' => 'joomprofile', 'text' => 'JoomProfile'),
							array('value' => 'cmavatar', 'text' => 'CM Avatar')
						), 'layout_avatar_integration', array('data-avatar-integration')); ?>
					</div>
				</div>

				<div class="form-group <?php echo $this->config->get('layout_avatar_integration') == 'gravatar' && !$this->config->get('layout_avatar_character') ? '' : 'hidden'; ?>" data-avatar-option data-avatar-gravatar>
					<?php echo $this->html('panel.label', 'COM_KOMENTO_LAYOUT_AVATAR_GRAVATAR_DEFAULT_PICTURE'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'gravatar_default_avatar', $this->config->get('gravatar_default_avatar'), array(
							array('value' => 'mm', 'text' => 'Mysteryman'),
							array('value' => 'identicon', 'text' => 'Identicon'),
							array('value' => 'monsterid', 'text' => 'Monsterid'),
							array('value' => 'wavatar', 'text' => 'Wavatar'),
							array('value' => 'retro', 'text' => 'Retro')
						)); ?>
					</div>
				</div>

				<div class="form-group <?php echo $this->config->get('layout_avatar_integration') == 'phpbb' && !$this->config->get('layout_avatar_character') ? '' : 'hidden'; ?>" data-avatar-option data-avatar-phpbb>
					<?php echo $this->html('panel.label', 'COM_KOMENTO_LAYOUT_LAYOUT_PHPBB_PATH'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.inputbox', 'layout_phpbb_path', $this->config->get('layout_phpbb_path')); ?>
					</div>
				</div>

				<div class="form-group <?php echo $this->config->get('layout_avatar_integration') == 'phpbb' && !$this->config->get('layout_avatar_character') ? '' : 'hidden'; ?>" data-avatar-option data-avatar-phpbb>
					<?php echo $this->html('panel.label', 'COM_KOMENTO_LAYOUT_LAYOUT_PHPBB_URL'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.inputbox', 'layout_phpbb_url', $this->config->get('layout_phpbb_url')); ?>
					</div>
				</div>

				<?php echo $this->html('settings.toggle', 'enable_rank_bar', 'COM_KOMENTO_SETTINGS_RANK_BAR_ENABLE'); ?>
				<?php echo $this->html('settings.toggle', 'admin_label', 'COM_KT_SETTINGS_DISPLAY_ADMIN_LABEL'); ?>
				<?php echo $this->html('settings.toggle', 'author_label', 'COM_KT_SETTINGS_DISPLAY_AUTHOR_LABEL'); ?>
				<?php echo $this->html('settings.toggle', 'guest_label', 'COM_KOMENTO_SETTINGS_LAYOUT_GUEST_LABEL'); ?>
				<?php echo $this->html('settings.toggle', 'enable_guest_link', 'COM_KOMENTO_SETTINGS_GUEST_LINK_ENABLE'); ?>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
	</div>
</div>


