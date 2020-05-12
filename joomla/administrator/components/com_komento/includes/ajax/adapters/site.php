<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(__DIR__ . '/abstract.php');

class KomentoAjaxAdapterSite extends KomentoAjaxAdapterAbstract
{
	public function execute($namespace, $parts, $args, $method)
	{
		$allowed = array('views', 'controllers');

		$type = $parts[0];
		$name = $parts[1];

		if (!in_array($type, $allowed)) {
			return JError::raiseError(500, JText::sprintf('Invalid AJAX request. Request of type %1$s is not supported.', $type));
		}
		
		if ($type == 'views') {
			$className = 'KomentoView' . preg_replace('/[^A-Z0-9_]/i', '', $name);
			$obj = new $className();
		}

		if ($type == 'controllers') {
			$className = 'KomentoController' . preg_replace('/[^A-Z0-9_]/i', '', $name);
			$obj = new $className();
		}
		
		// For controllers, use standard execute method
		if ($type == 'controllers') {
			return $obj->execute($method);
		}

		// If the method doesn't exist in this object, we know something is wrong.
		if (!method_exists($obj, $method)) {
			$this->ajax->reject(JText::sprintf('Method %1s does not exist', $method));
			return $this->ajax->send();
		}

		// When arguments are provided, we provide them as func arguments
		if (!empty($args)) {
			return call_user_func_array(array($obj, $method), json_decode($args));
		}

		return $obj->$method();
	}
}
