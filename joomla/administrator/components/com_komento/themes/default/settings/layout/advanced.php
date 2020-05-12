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
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_LAYOUT_ADVANCED'); ?>
			
			<div class="panel-body">
				<p><?php echo JText::_('COM_KOMENTO_SETTINGS_LAYOUT_CSS_CONTROL_DESC'); ?></p>

				<?php echo $this->html('settings.textbox', 'layout_css_admin', 'COM_KOMENTO_SETTINGS_CSS_CLASS_ADMIN', '', array('attributes' => 'data-custom-css data-original="kmt-comment-item-admin"')); ?>
				<?php echo $this->html('settings.textbox', 'layout_css_registered', 'COM_KOMENTO_SETTINGS_CSS_CLASS_REGISTERED', '', array('attributes' => 'data-custom-css data-original="kmt-comment-item-registered"')); ?>
				<?php echo $this->html('settings.textbox', 'layout_css_author', 'COM_KOMENTO_SETTINGS_CSS_CLASS_CONTENT_AUTHOR', '', array('attributes' => 'data-custom-css data-original="kmt-comment-item-author"')); ?>
				<?php echo $this->html('settings.textbox', 'layout_css_public', 'COM_KOMENTO_SETTINGS_CSS_CLASS_PUBLIC', '', array('attributes' => 'data-custom-css data-original="kmt-comment-item-public"')); ?>

				<div class="form-group">
					<div class="col-md-5"></div>
					<div class="col-md-7">
						<a href="javascript:void(0);" class="btn btn-kt-default-o btn-sm" data-reset-css><i class="fa fa-undo"></i>&nbsp; <?php echo JText::_('COM_KOMENTO_REVERT_ORIGINAL_CSS'); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
	</div>
</div>

