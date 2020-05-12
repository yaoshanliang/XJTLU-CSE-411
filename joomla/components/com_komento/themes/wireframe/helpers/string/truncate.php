<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="kt-data-truncater" data-kt-truncater>
	<div data-text class="fd-cf"><?php echo $truncated; ?></div>
	<div class="t-hidden" data-original><?php echo $original;?></div>

	<?php if ($showMore) { ?>
	<a href="javascript:void(0);" <?php echo $overrideReadmore ? 'data-filter-item="info"' : 'data-readmore'; ?>><?php echo JText::_('COM_KOMENTO_FRONTPAGE_READMORE'); ?></a>
	<?php } ?>

</div>