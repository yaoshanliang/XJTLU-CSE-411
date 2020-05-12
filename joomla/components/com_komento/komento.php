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

require_once(JPATH_ADMINISTRATOR . '/components/com_komento/includes/komento.php');
require_once(JPATH_ROOT . '/components/com_komento/bootstrap.php');

// Listen for AJAX calls here
KT::ajax()->listen();

$app = JFactory::getApplication();
$input = $app->input;

$task = $input->get('task', '', 'cmd');

if ($task == 'confirmSubscription') {

	$token = $input->get('token', '', 'cmd');

	if (empty($token)) {
		echo JText::_('COM_KOMENTO_INVALID_TOKEN');
		exit;
	}

	$model = KT::model('subscription');
	$state = $model->confirmSubscription($token);
}

// We need the base controller
KT::import('site:/controllers/controller');

// Try to get the task from query string.
$task = $input->get('task', 'display', 'cmd');

// We treat the view as the controller. Load other controller if there is any.
$controller	= $input->get('controller', '', 'word');

if (!empty($controller)) {
	$controller	= JString::strtolower($controller);

	// Import controller
	$state = KT::import('site:/controllers/' . $controller);

	if (!$state) {
		JError::raiseError(500 , JText::sprintf('Invalid controller %1$s', $controller));
	}
}

// We need to process cronjobs when needed
$cron = $input->get('cron', false, 'bool');
$crondata = $input->get('crondata', false, 'bool');

if ($crondata) {
	$msg = KT::gdpr()->cron();

	echo $msg;
	exit;
}

if ($cron) {
	$config = KT::config();

	$requirePhrase = $config->get('secure_cron');
	$storedPhrase = $config->get('secure_cron_key');
	$phrase = $input->get('phrase', '');

	if ($requirePhrase && empty($phrase) || ($requirePhrase && $storedPhrase != $phrase)) {
		echo JText::_('COM_KT_CRONJOB_PASSPHRASE_INVALID');
		exit;
	}

	$messages = array();

	
	$total = $input->get('total', $config->get('notification_total_email'), 'int');

	// mailer
	$mailer = KT::mailer();
	$mailer->send($total);
	$messages[] = JText::_('COM_KOMENTO_EMAIL_BATCH_PROCESS_FINISHED');

	// one signal push
	$push = KT::push();
	if ($push->isEnabled()) {
		$push->notifyQueue(KOMENTO_PUSH_NOTIFICATION_THRESHOLD);
		$messages[] = JText::_('COM_KOMENTO_PUSH_NOTTIFICATION_BATCH_PROCESS_FINISHED');
	}

	foreach ($messages as $msg) {
		echo $msg . '<br>';
	}

	exit;
}

$class = 'KomentoController' . JString::ucfirst($controller);

// Test if the object really exists in the current context
if (!class_exists($class)) {
	JError::raiseError( 500 , JText::sprintf('Invalid controller %1$s', $class));
}

$controller = new $class();
$controller->execute($task);
$controller->redirect();
