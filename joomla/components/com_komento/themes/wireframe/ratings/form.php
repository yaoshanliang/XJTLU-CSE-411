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
<div class="kt-ratings-stars-forms">
	<div class="kt-ratings-stars-forms__note">
		<?php echo JText::_('COM_KT_RATE_THIS_POST');?>:
	</div>
	<div class="kt-ratings-stars" data-kt-ratings-star></div>
	<input type="hidden" name="ratings" data-kt-rating-input value="" />
	
	<div class="kt-ratings-stars-forms__reset">
		<a href="javascript:void(0);" data-kt-ratings-reset><?php echo JText::_('COM_KT_RESET_RATING');?></a>
	</div>	
</div>