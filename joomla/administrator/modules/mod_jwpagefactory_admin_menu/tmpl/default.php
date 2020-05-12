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
defined('_JEXEC') or die ('restricted access');

$user = JFactory::getUser();
$input = JFactory::getApplication()->input;
$view = $input->get('view', NULL, 'STRING');
$option = $input->get('option', NULL, 'STRING');
$layout = $input->get('layout', NULL, 'STRING');

if ($user->authorise('core.manage', 'com_jwpagefactory')) { ?>

	<ul id="jw-pagefactory-menu" class="nav <?php echo ($layout == 'edit') ? 'disabled' : ''; ?>">
		<li class="dropdown <?php echo ($option == 'com_jwpagefactory' && $layout != 'edit') ? 'active' : ''; ?> <?php echo ($layout == 'edit') ? 'disabled' : ''; ?> ">

			<?php if ($layout == 'edit') { ?>
				<a class="no-dropdown">
					<?php echo JText::_('MOD_MENU_COM_JWPAGEFACTORY'); ?>
				</a>
			<?php } else { ?>
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<?php echo JText::_('MOD_MENU_COM_JWPAGEFACTORY'); ?>
					<span class="caret"></span>
				</a>
				<ul aria-labelledby="dropdownMenu" role="menu" class="dropdown-menu">
					<li <?php echo ($option == 'com_jwpagefactory' && $view == 'page') ? 'class="active"' : ''; ?>>
						<a href="<?php echo JRoute::_('index.php?option=com_jwpagefactory&task=page.add', false); ?>">
							<?php echo JText::_('MOD_MENU_COM_JWPAGEFACTORY_PAGE'); ?>
						</a>
					</li>
					<li <?php echo ($option == 'com_jwpagefactory' && ($view == '' || $view == 'pages')) ? 'class="active"' : ''; ?>>
						<a href="<?php echo JRoute::_('index.php?option=com_jwpagefactory', false); ?>">
							<?php echo JText::_('MOD_MENU_COM_JWPAGEFACTORY_PAGES'); ?>
						</a>
					</li>
					<li <?php echo ($option == 'com_jwpagefactory' && $view == 'integrations') ? 'class="active"' : ''; ?>>
						<a href="<?php echo JRoute::_('index.php?option=com_jwpagefactory&view=integrations', false); ?>">
							<?php echo JText::_('MOD_MENU_COM_JWPAGEFACTORY_INTEGRATIONS'); ?>
						</a>
					</li>
					<li <?php echo ($option == 'com_jwpagefactory' && $view == 'languages') ? 'class="active"' : ''; ?>>
						<a href="<?php echo JRoute::_('index.php?option=com_jwpagefactory&view=languages', false); ?>">
							<?php echo JText::_('MOD_MENU_COM_JWPAGEFACTORY_LANGUAGES'); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_config&view=component&component=com_jwpagefactory', false) . '#licenseupdate'; ?>">
							<?php echo JText::_('MOD_MENU_COM_JWPAGEFACTORY_ACTIVATE'); ?>
						</a>
					</li>
				</ul>
			<?php } ?>
		</li>
	</ul>

	<?php
}
