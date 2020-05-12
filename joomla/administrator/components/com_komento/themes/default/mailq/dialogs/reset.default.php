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
defined('_JEXEC') or die('Unauthorized Access');
?>
<dialog>
	<width>450</width>
	<height>150</height>
	<selectors type="json">
	{
		"{close}": "[data-close-button]",
		"{submit}": "[data-submit-button]",
		"{form}": "[data-form-reset]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{close} click": function() {
			this.parent.close();
		},
		"{submit} click": function() {
			this.form().submit();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_KOMENTO_EMAILS_RESET_DEFAULT'); ?></title>
	<content>
		<form data-form-reset method="post" action="<?php echo JRoute::_('index.php');?>">
			<?php echo JText::_('COM_KOMENTO_EMAILS_RESET_DEFAULT_CONFIRMATION'); ?>

			<?php if ($files) { ?>
				<?php foreach ($files as $file) { ?>
					<input type="hidden" name="file[]" value="<?php echo $file;?>" />
				<?php } ?>
			<?php } ?>

			<input type="hidden" name="task" value="reset" data-kt-table-task />
			<input type="hidden" name="option" value="com_komento" />
			<input type="hidden" name="controller" value="mailq" />
			<?php echo JHTML::_('form.token'); ?>
		</form>
	</content>
	<buttons>
		<button data-close-button type="button" class="btn btn-kt-default btn-sm"><?php echo JText::_('COM_KOMENTO_CANCEL_BUTTON'); ?></button>
		<button data-submit-button type="button" class="btn btn-kt-danger btn-sm"><?php echo JText::_('COM_KOMENTO_RESET_EMAIL_FILES'); ?></button>
	</buttons>
</dialog>