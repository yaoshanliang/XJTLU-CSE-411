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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder' );
jimport('joomla.filesystem.path');
jimport('joomla.access.access');

require_once(JPATH_ADMINISTRATOR . '/components/com_komento/constants.php');
require_once(KOMENTO_HELPERS . '/document/document.php' );
require_once(KOMENTO_HELPER);
require_once(KOMENTO_HELPERS . '/router/router.php' );

// Load language here
// initially language is loaded in content plugin
// for custom integration that doesn't go through plugin, language is not loaded
// hence, language should be loaded in bootstrap

$lang = JFactory::getLanguage();
$path = JPATH_ROOT;

if (JFactory::getApplication()->isAdmin()) {
	$path .= '/administrator';
}

// Load English first as fallback
$config = KT::config();

if ($config->get('enable_language_fallback')) {
	$lang->load('com_komento', $path, 'en-GB', true);
}

$lang->load('com_komento', $path, $lang->getDefault(), true);
$lang->load('com_komento', $path, null, true);
