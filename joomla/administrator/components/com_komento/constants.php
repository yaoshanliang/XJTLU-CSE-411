<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

// Environment paths
define('KOMENTO_ROOT', JPATH_ROOT . '/components/com_komento');
define('KOMENTO_ADMIN_ROOT', JPATH_ADMINISTRATOR . '/components/com_komento');
define('KOMENTO_ASSETS', KOMENTO_ROOT . '/assets');
define('KOMENTO_LIB', KOMENTO_ADMIN_ROOT . '/includes');
define('KOMENTO_CONTROLLERS', KOMENTO_ROOT . '/controllers');
define('KOMENTO_MODELS', KOMENTO_ROOT . '/models');
define('KOMENTO_CLASSES', KOMENTO_ROOT . '/classes');
define('KOMENTO_TABLES', KOMENTO_ADMIN_ROOT . '/tables');
define('KOMENTO_THEMES', KOMENTO_ROOT . '/themes');
define('KOMENTO_MEDIA', JPATH_ROOT . '/media/com_komento');
define('KOMENTO_HELPER', KOMENTO_ADMIN_ROOT . '/includes/komento.php');
define('KOMENTO_HELPERS', KOMENTO_ADMIN_ROOT . '/includes');
define('KOMENTO_ADMIN_UPDATES', KOMENTO_ADMIN_ROOT . '/updates');

define('KOMENTO_JS_ROOT', KOMENTO_MEDIA . '/js');
define('KOMENTO_CSS_ROOT', KOMENTO_MEDIA . '/css');
define('KOMENTO_UPLOADS_ROOT', KOMENTO_MEDIA . '/uploads');
define('KOMENTO_PLUGINS' , KOMENTO_ROOT . '/komento_plugins');
define('KOMENTO_BOOTSTRAP', KOMENTO_ROOT . '/bootstrap.php');
define('KOMENTO_SCRIPTS', KOMENTO_MEDIA . '/scripts');

// Languages
define('KOMENTO_UPDATER_LANGUAGE', 'https://services.stackideas.com/translations/komento');
define('KOMENTO_LANGUAGES_INSTALLED', 1);
define('KOMENTO_LANGUAGES_NOT_INSTALLED', 0);
define('KOMENTO_LANGUAGES_NEEDS_UPDATING', 3);

define('KOMENTO_TMP', KOMENTO_MEDIA . '/tmp');

// Color states for info messages
define('KOMENTO_MSG_SUCCESS', 'success');
define('KOMENTO_MSG_WARNING', 'warning');
define('KOMENTO_MSG_ERROR', 'error');
define('KOMENTO_MSG_INFO', 'info');

define('KOMENTO_STATE_PUBLISHED', 1);
define('KOMENTO_STATE_UNPUBLISHED', 0);

// Action types
define('KOMENTO_ACTIONS_TYPE_REPORT', 'report');

// Comment Statuses
define('KOMENTO_COMMENT_UNPUBLISHED', 0);
define('KOMENTO_COMMENT_PUBLISHED', 1);
define('KOMENTO_COMMENT_MODERATE', 2);
define('KOMENTO_COMMENT_SPAM', 3);

// Comment flag
define('KOMENTO_COMMENT_AKISMET_TRAINED', 1);

// CleanTalk Flag
define('KOMENTO_CLEANTALK_SPAM', 3);
define('KOMENTO_CLEANTALK_POSSIBLE_SPAM', 2);

// Updates server
define('KOMENTO_UPDATES_SERVER', 'stackideas.com');
define('KOMENTO_SERVICE_VERSION', 'https://stackideas.com/updater/manifests/komento');
define('KOMENTO_JUPDATE_SERVICE', 'https://stackideas.com/jupdates/manifest/komento');

// Themes
define('KOMENTO_THEME_BASE', 'kuro');

// Sessions
define('KOMENTO_SESSION_NAMESPACE', 'com_komento');

// Push notification max threshold
define('KOMENTO_PUSH_NOTIFICATION_THRESHOLD', 25);

// GDPR download request state
define('KOMENTO_DOWNLOAD_REQ_NEW', 0);
define('KOMENTO_DOWNLOAD_REQ_LOCKED', 1);
define('KOMENTO_DOWNLOAD_REQ_PROCESS', 2);
define('KOMENTO_DOWNLOAD_REQ_READY', 3);

// GDPR Download Folder
define('KOMENTO_GDPR_DOWNLOADS', KOMENTO_MEDIA . '/downloads');

