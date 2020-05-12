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
<div class="kt-toolbar t-lg-mb--lg">
	<div class="kt-toolbar__item kt-toolbar__item--home-submenu">
		<nav class="o-nav kt-toolbar__o-nav">
			<div class="o-nav__item <?php echo $layout == 'dashboard' ? 'is-active' : ''; ?>">
				<a href="<?php echo JRoute::_('index.php?option=com_komento&view=dashboard', false) ?>" class="o-nav__link kt-toolbar__link">
					<i class="fa fa-file-text-o t-sm-visible"></i>
					<span><?php echo JText::_('COM_KT_TOOLBAR_DASHBOARD'); ?></span>
				</a>
			</div>
			<div class="o-nav__item <?php echo $layout == 'download' ? 'is-active' : ''; ?>">
				<a href="<?php echo JRoute::_('index.php?option=com_komento&view=dashboard&layout=download', false) ?>" class="o-nav__link kt-toolbar__link">
					<i class="fa fa-file-text-o t-sm-visible"></i>
					<span><?php echo JText::_('COM_KT_TOOLBAR_DOWNLOAD_DATA'); ?></span>
				</a>
			</div>
		</nav>
	</div>
</div>

