<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
//no direct accees
defined ('_JEXEC') or die ('restricted access');
?>

<div id="jwpagefactory-wrap" class="clearfix">
	<div class="icon-wrapper">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_jwpagefactory&task=page.add', false); ?>">
				<img alt="<?php echo JText::_('MOD_JWPAGEFACTORY_ICONS_ADD_PAGE'); ?>" src="<?php echo JURI::root(true); ?>/administrator/modules/mod_jwpagefactory_icons/tmpl/images/page.png" />
				<span><?php echo JText::_('MOD_JWPAGEFACTORY_ICONS_ADD_PAGE'); ?></span>
			</a>
		</div>
	</div>
	<div class="icon-wrapper">
		<div class="icon">
			<a href="<?php echo JRoute::_('index.php?option=com_jwpagefactory', false); ?>">
				<img alt="<?php echo JText::_('MOD_JWPAGEFACTORY_ICONS_PAGES'); ?>" src="<?php echo JURI::root(true); ?>/administrator/modules/mod_jwpagefactory_icons/tmpl/images/pages.png" />
				<span><?php echo JText::_('MOD_JWPAGEFACTORY_ICONS_PAGES'); ?></span>
			</a>
		</div>
	</div>
</div>

