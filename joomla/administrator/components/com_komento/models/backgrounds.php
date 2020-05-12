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

KT::import('admin:/includes/model');

class KomentoModelBackgrounds extends KomentoModel
{
	protected $_total = null;
	protected $_pagination = null;
	protected $_data = null;

	public function __construct()
	{
		parent::__construct('backgrounds');
		$app = JFactory::getApplication();
		
		$limit = $this->app->getUserStateFromRequest('com_komento.backgrounds.limit', 'limit', $app->getCfg('list_limit'));
		$limitstart	= $this->input->get('limitstart', 0, 'int');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Retrieves backgrounds
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function getPresets($options = array())
	{
		static $_cache = array();

		$db = KT::db();
		$query = array();

		$idx = '';

		$query[] = 'SELECT * FROM ' . $db->qn('#__komento_backgrounds');

		if (isset($options['published'])) {
			$query[] = ' WHERE `published`=' . $db->Quote($options['published']);
			$idx .= 'published-' . $options['published'];
		}

		// Set the total number of items.
		$query = implode(' ', $query);

		$isBackend = JFactory::getApplication()->isAdmin();

		// we know only backend required pagination.
		if ($isBackend) {

			$countQuery = str_replace('SELECT * FROM', 'SELECT COUNT(1) FROM', $query);
			$db->setQuery($countQuery);
			$this->_total = $db->loadResult();
		}


		if (!isset($_cache[$idx]) || $isBackend) {

			// Get the list of users
			$db->setQuery($query);
			$this->_data = $db->loadObjectList();
			$_cache[$idx] = $this->_data;
		}

		$rows = $_cache[$idx];

		if (!$rows) {
			return array();
		}

		$presets = array();

		foreach ($rows as $row) {
			$preset = KT::table('Backgrounds');
			$preset->bind($row);

			$presets[] = $preset;
		}

		return $presets;
	}

	/**
	 * Method to get the total nr of the presets
	 *
	 * @since  3.1
	 * @access public
	 */
	public function getTotal()
	{
		return $this->_total;
	}

	/**
	 * Method to get a pagination object for the presets
	 *
	 * @since  3.1
	 * @access public
	 */
	public function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			$this->_pagination = KT::pagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}
}
