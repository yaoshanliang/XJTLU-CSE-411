<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<dialog>
	<width>450</width>
	<height>200</height>
	<selectors type="json">
	{
		"{closeButton}": "[data-close-button]",
		"{submit}": "[data-subscribe-button]",
		"{name}": "[data-subscribe-name]",
		"{email}": "[data-subscribe-email]",
		"{userId}": "[data-subscribe-userid]",
		"{form}": "[data-subscribe-form]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_KOMENTO_SUBSCRIBE_DIALOG_TITLE'); ?></title>
	<content>
		<p><?php echo JText::_('COM_KOMENTO_FORM_SUBSCRIBE_ENTER_DETAILS'); ?></p>

		<form action="<?php echo JRoute::_('index.php');?>" method="post" class="o-form-horizontal" data-subscribe-form>
			<div class="o-form-group">
				<label for="subscribe-name" class="o-control-label"><?php echo JText::_('COM_KOMENTO_FORM_NAME'); ?></label>
				<div class="o-control-input">
					<input type="text" name="name" id="subscribe-name" value="<?php echo $this->my->getName();?>" class="o-form-control" <?php echo $this->my->id ? 'disabled="true"' : '';?> data-subscribe-name />
				</div>
			</div>

			<div class="o-form-group">
				<label for="subscribe-email" class="o-control-label"><?php echo JText::_('COM_KOMENTO_FORM_EMAIL'); ?></label>
				<div class="o-control-input">
					<input type="text" name="email" id="subscribe-email" value="<?php echo $this->my->email;?>" class="o-form-control" <?php echo $this->my->id ? 'disabled="true"' : '';?> data-subscribe-email />
				</div>
			</div>

			<input type="hidden" name="component" value="<?php echo $component; ?>" />
			<input type="hidden" name="cid" value="<?php echo $cid; ?>" />
			<input type="hidden" name="userId" value="<?php echo $this->my->id; ?>" data-subscribe-userid />

			<?php echo $this->html('form.returnUrl'); ?>
			<?php echo $this->html('form.action', 'subscriptions.subscribe'); ?>
		</form>
	</content>
	<buttons>
		<button type="button" class="btn btn-kt-default-o btn-sm" data-close-button><?php echo JText::_('COM_KOMENTO_CLOSE_BUTTON'); ?></button>
		<button type="button" class="btn btn-kt-primary btn-sm" data-subscribe-button><?php echo JText::_('COM_KOMENTO_FORM_SUBSCRIBE'); ?></button>
	</buttons>
</dialog>
