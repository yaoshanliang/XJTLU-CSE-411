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
	<height>150</height>
	<selectors type="json">
	{
		"{closeButton}": "[data-close-button]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_KOMENTO_REPORTS_DIALOG_TITLE'); ?></title>
	<content>
		<p class="t-lg-mt--md"><?php echo JText::_('COM_KOMENTO_REPORTS_SUBMITTED_DIALOG_CONTENT'); ?></p>
	</content>
	<buttons>
		<button type="button" class="btn btn-kt-default-o btn-sm" data-close-button><?php echo JText::_('COM_KOMENTO_CLOSE_BUTTON'); ?></button>
	</buttons>
</dialog>
