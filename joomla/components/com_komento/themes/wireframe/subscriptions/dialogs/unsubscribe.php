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
	<width>400</width>
	<height>100</height>
	<selectors type="json">
	{
		"{closeButton}": "[data-close-button]",
		"{submit}": "[data-unsubscribe-button]",
		"{form}": "[data-unsubscribe-form]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_KOMENTO_UNSUBSCRIBE_DIALOG_TITLE'); ?></title>
	<content>
		<form action="<?php echo JRoute::_('index.php');?>" method="post" data-unsubscribe-form>
			<p><?php echo JText::_('COM_KOMENTO_UNSUBSCRIBE_CONFIRMATION'); ?></p>
			
			<input type="hidden" name="component" value="<?php echo $component; ?>" />
			<input type="hidden" name="cid" value="<?php echo $cid; ?>" />

			<?php echo $this->html('form.returnUrl'); ?>
			<?php echo $this->html('form.action', 'subscriptions.unsubscribe'); ?>
		</form>
	</content>
	<buttons>
		<button data-close-button type="button" class="btn btn-kt-default-o btn-sm"><?php echo JText::_('COM_KOMENTO_CLOSE_BUTTON'); ?></button>
		<button type="button" class="btn btn-kt-primary btn-sm" data-unsubscribe-button><?php echo JText::_('COM_KOMENTO_UNSUBSCRIBE_BUTTON'); ?></button>
	</buttons>
</dialog>
