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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_CAPTCHA'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'antispam_captcha_enable', 'COM_KOMENTO_SETTINGS_CAPTCHA_ENABLE'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_CAPTCHA_TYPE'); ?>
					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'antispam_captcha_type', $this->config->get('antispam_captcha_type'), array(
							array('value' => '0', 'text' => 'COM_KOMENTO_SETTINGS_CAPTCHA_BUILT_IN'),
							array('value' => '1', 'text' => 'COM_KOMENTO_SETTINGS_CAPTCHA_RECAPTCHA')
						)); ?>
					</div>
				</div>
				
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_CAPTCHA_USERGROUPS'); ?>

					<div class="col-md-7">
						<?php echo $this->html( 'tree.groups' , 'show_captcha', $this->config->get('show_captcha'), array()); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel <?php echo $this->config->get('antispam_captcha_type') != '1' ? 'hidden' : '';?>" data-recaptcha-section>
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_RECAPTCHA'); ?>
			<div class="panel-body">
				<p><?php echo JText::sprintf('COM_KOMENTO_SETTINGS_RECAPTCHA_REGISTER', '<a href="https://www.google.com/recaptcha" target="_blank">https://www.google.com/recaptcha</a>'); ?></p>

				<?php echo $this->html('settings.textbox', 'antispam_recaptcha_public_key', 'COM_KOMENTO_SETTINGS_RECAPTCHA_PUBLIC_KEY'); ?>
				<?php echo $this->html('settings.textbox', 'antispam_recaptcha_private_key', 'COM_KOMENTO_SETTINGS_RECAPTCHA_PRIVATE_KEY'); ?>
				<?php echo $this->html('settings.toggle', 'antispam_recaptcha_invisible', 'COM_KT_RECAPTCHA_INVISIBLE_RECAPTCHA'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_RECAPTCHA_THEME'); ?> 

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'antispam_recaptcha_theme', $this->config->get('antispam_recaptcha_theme'), array(
							array('value' => 'clean', 'text' => 'COM_KOMENTO_SETTINGS_RECAPTCHA_THEME_CLEAN'),
							array('value' => 'white', 'text' => 'COM_KOMENTO_SETTINGS_RECAPTCHA_THEME_WHITE'),
							array('value' => 'red', 'text' => 'COM_KOMENTO_SETTINGS_RECAPTCHA_THEME_RED'),
							array('value' => 'blackglass', 'text' => 'COM_KOMENTO_SETTINGS_RECAPTCHA_THEME_BLACKGLASS')
						)); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_RECAPTCHA_LANGUAGE'); ?> 

					<div class="col-md-7">
						<?php echo $this->html('grid.inputbox', 'antispam_recaptcha_lang', $this->config->get('antispam_recaptcha_lang'), 'antispam_recaptcha_lang', array('class' => 'input-mini text-center')); ?>
						<a href="https://developers.google.com/recaptcha/docs/language" target="_blank"><?php echo JText::_('COM_KOMENTO_VIEW_LANGUAGE_CODES'); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>