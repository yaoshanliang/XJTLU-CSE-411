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
defined('_JEXEC') or die('Restricted access');

KT::import('admin:/includes/model');

class KomentoModelSettings extends KomentoModel
{
	public function __construct()
	{
		parent::__construct('settings');
	}

	/**
	 * Saves the settings
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function save($data)
	{
		unset($data['component']);

		$config	= KT::table('Configs');
		$config->load('config');

		$registry = KT::registry($this->_getParams());

		foreach ($data as $index => $value) {

			// If the value is an array, we would assume that it should be comma separated
			if (is_array($value)) {
				$value = implode(',', $value);
			}

			$registry->set($index, $value);
		}

		// Get the complete INI string
		// $config->params	= $registry->toString('INI');
		$config->params	= $registry->toString();

		// Save it
		if (!$config->store()) {
			return false;
		}

		return true;
	}

	function &_getParams($key = 'config')
	{
		static $params	= null;

		if (is_null($params)) {
			$db = KT::db();

			$query	= 'SELECT ' . $db->nameQuote( 'params' ) . ' '
					. 'FROM ' . $db->nameQuote( '#__komento_configs' ) . ' '
					. 'WHERE ' . $db->nameQuote( 'name' ) . '=' . $db->Quote($key);

			$db->setQuery($query);

			$params	= $db->loadResult();
		}

		return $params;
	}

	/**
	 * Update Email Logo
	 *
	 * @since	3.0.7
	 * @access	public
	 */
	public function updateEmailLogo($file)
	{
		$notification = KT::notification();

		return $notification->storeEmailLogo($file);
	}
}
