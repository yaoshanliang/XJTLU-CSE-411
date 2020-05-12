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
defined('_JEXEC') or die ('Restricted access');

// api site
defined('API_SITE') or define('API_SITE', 'https://pagefactory.joomla.work');

$required_min_php_version = '5.6.0';

if (version_compare(PHP_VERSION, $required_min_php_version, '<')) {
	(include_once JPATH_SITE . '/administrator/components/com_jwpagefactory/views/phpversion.tmpl.php') or die('Your PHP version is too old for this component.');
	return;
}

require_once JPATH_COMPONENT . '/helpers/route.php';

jimport('joomla.application.component.controller');

//CSRF
\JHtml::_('jquery.token');

$controller = JControllerLegacy::getInstance('Jwpagefactory');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
