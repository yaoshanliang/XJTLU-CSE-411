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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_CLEANTALK_GENERAL'); ?>

			<div class="panel-body">
				<div class="o-grid">
					<div class="o-grid__cell-auto-size">
						<img src="<?php echo JURI::root();?>media/com_komento/images/integrations/cleantalk.png" width="150" class="t-lg-mr--xl" />
					</div>
					<div class="o-grid__cell">
						<?php echo JText::_('COM_KOMENTO_SETTINGS_CLEANTALK_ABOUT'); ?>
						<br /><br />
						<a href="http://cleantalk.org/?pid=141225" class="btn btn-kt-primary btn-sm" target="_blank"><?php echo JText::_('Learn More');?></a>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_CLEANTALK_ENABLE'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'cleantalk_enabled', $this->config->get('cleantalk_enabled')); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_CLEANTALK_KEY'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.inputbox', 'cleantalk_key', $this->config->get('cleantalk_key')); ?>
					</div>
				</div>

			</div>
		</div>

	</div>

	<div class="col-lg-6">
	</div>
</div>