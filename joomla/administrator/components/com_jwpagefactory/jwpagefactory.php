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

// api site
defined('API_SITE') or define('API_SITE', 'https://pagefactory.joomla.work');

$required_min_php_version = '5.6.0';

if (version_compare(PHP_VERSION, $required_min_php_version, '<')) {
	(include_once JPATH_SITE . '/administrator/components/com_jwpagefactory/views/phpversion.tmpl.php') or die('Your PHP version is too old for this component.');
	return;
}

JHtml::_('behavior.tabstate');

if (!JFactory::getUser()->authorise('core.manage', 'com_jwpagefactory')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('JwpagefactoryHelper', __DIR__ . '/helpers/jwpagefactory.php');

jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('jwpagefactory');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
