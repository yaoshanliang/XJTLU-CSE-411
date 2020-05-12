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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

require_once(__DIR__ . '/setup.php');
require_once(JPATH_ADMINISTRATOR . '/components/com_komento/includes/komento.php');

if (!$my->authorise('core.manage', 'com_komento')) {
	$app->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'), 'error');
	return $app->close();
}

KT::checkEnvironment();

// Include the base controller
require_once(KOMENTO_ADMIN_ROOT . '/controllers/controller.php');

KT::document()->start();

// Process AJAX calls
KT::ajax()->listen();

// We treat the view as the controller. Load other controller if there is any.
$controller = $input->get('controller', '', 'word');
$task = $input->get('task', 'display', 'cmd');

if (!empty($controller)) {

	$controller = JString::strtolower($controller);

	require_once(KOMENTO_ADMIN_ROOT . '/controllers/' . $controller . '.php');
}

$class = 'KomentoController' . JString::ucfirst($controller);

// Test if the object really exists in the current context
if (!class_exists($class)) {
	JError::raiseError(500, JText::sprintf('COM_EASYSOCIAL_INVALID_CONTROLLER_CLASS_ERROR', $class));
}

$controller	= new $class();
$controller->execute($task);
$controller->redirect();

KT::document()->end();
