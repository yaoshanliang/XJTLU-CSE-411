<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');
?>
<div class="row">
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_CATEGORIES'); ?>

			<div class="panel-body">
				<?php if ($tab->id == 'com_ohanahvenue_settings') { ?>
					<p><?php echo JText::_('COM_KOMENTO_SETTINGS_CATEGORIES_OHANAHVENUE_INFO'); ?></p>
				<?php } else { ?>
					<p><?php echo JText::_('COM_KOMENTO_SETTINGS_CATEGORIES_INFO'); ?></p>
					<div class="form-group">
						<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_CATEGORIES_ASSIGNMENT'); ?>

						<div class="col-md-7">
							<?php echo $this->html('grid.selectlist', 'mode_categories_' . $tab->id, $this->config->get('mode_categories_' . $tab->id), array(
								array('value' => '0', 'text' => 'COM_KOMENTO_SETTINGS_CATEGORIES_ON_ALL_CATEGORIES'),
								array('value' => '1', 'text' => 'COM_KOMENTO_SETTINGS_CATEGORIES_ON_SELECTED_CATEGORIES'),
								array('value' => '2', 'text' => 'COM_KOMENTO_SETTINGS_CATEGORIES_ON_ALL_CATEGORIES_EXCEPT_SELECTED'),
								array('value' => '3', 'text' => 'COM_KOMENTO_SETTINGS_CATEGORIES_NO_CATEGORIES')
							)); ?>
						</div>
					</div>

					<div class="form-group">
						<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_ENABLE_ON_CATEGORIES'); ?>

						<div class="col-md-7">
							<?php echo $this->html('grid.multilist', 'allowed_categories_' . $tab->id, $this->config->get('allowed_categories_' . $tab->id), $tab->categories); ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if ($tab->componentSettings) { ?>
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_COMPONENT'); ?>

			<div class="panel-body">
			<?php foreach ($tab->componentSettings as $setting) { ?>
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_COMPONENT_' . strtoupper($setting->name)); ?>

					<div class="col-md-7">
						<?php echo $this->html($setting->type, $setting->name, $this->config->get($setting->name), $setting->values); ?>
					</div>
				</div>
			<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>
</div>