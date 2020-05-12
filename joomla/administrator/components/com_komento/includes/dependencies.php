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

// We need the constants file
require_once(JPATH_ADMINISTRATOR . '/components/com_komento/constants.php');

if (!function_exists('dump')) {
	function dump()
	{
		$args = func_get_args();

		echo '<pre>';

		foreach ($args as $arg) {
			var_dump($arg);
		}
		echo '</pre>';
		exit;
	}
}

if (!function_exists('pdump')) {
	function pdump()
	{
		$args = func_get_args();

		echo '<pre>';

		foreach ($args as $arg) {
			print_r($arg);
		}
		echo '</pre>';
		exit;	
	}
}

class KomentoBase
{
	public $config = null;
	public $jConfig = null;
	public $app = null;
	public $input = null;
	public $my = null;
	public $doc = null;

	protected $error = null;

	public function __construct()
	{
		if (!defined('KOMENTO_CLI')) {
			$this->doc = JFactory::getDocument();
			$this->app = JFactory::getApplication();
			$this->input = $this->app->input;
			$this->jConfig = JFactory::getConfig();
			$this->my = JFactory::getUser();
			$this->profile = KT::getProfile();
			$this->access = KT::acl();
		}

		$this->config = KT::config();
	}

	public function setError($message)
	{
		$this->error = $message;
	}

	public function getError()
	{
		if (!$this->error) {
			return false;
		}

		return JText::_($this->error);
	}

	/**
	 * Determines if the current request is from mobile
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function isMobile()
	{
		$mobile = ES::responsive()->isMobile();

		return $mobile;
	}
	/**
	 * Allows caller to normalize an array key / object key
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function normalize($object, $key, $default = false)
	{
		if (is_array($object)) {
			return isset($object[$key]) ? $object[$key] : $default;
		}

		if (is_object($object)) {
			return isset($object->$key) ? $object->$key : $default;
		}
	}
}