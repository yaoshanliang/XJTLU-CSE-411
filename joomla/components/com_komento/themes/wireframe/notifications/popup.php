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
<div id="kt" class="kt-noti-pop" style="position: fixed; right: 20px; bottom: 80px;" data-kt-notifications>
	<div class="kt-noti">
		<a href="javascript:void(0);" class="kt-noti__close" data-kt-notifications-close><i class="fa fa-close"></i></a>

		<div class="kt-noti__title">
			<i class="fa fa-comments kt-noti__icon"></i>
			<?php echo JText::_('COM_KOMENTO_NEW_COMMENT_NOTIFICATION_TITLE'); ?>
		</div>
		<div class="kt-noti__content">
			<?php echo JText::sprintf('COM_KOMENTO_NEW_COMMENT_NOTIFICATION_CONTENT', '<b>' . $newItems . '</b>'); ?>
		</div>
	</div>
</div>