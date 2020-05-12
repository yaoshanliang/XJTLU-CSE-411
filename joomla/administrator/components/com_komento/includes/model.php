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

jimport('joomla.application.component.model');


require_once( KOMENTO_HELPER );


$version = KT::joomlaVersion();

if ($version >= '3.0') {
	class KomentoModelMain extends JModelLegacy
	{
	}
} else {
	class KomentoModelMain extends JModel
	{
	}
}

class KomentoModel extends KomentoModelMain
{
	/**
	 * The database layer from Joomla.
	 * @var	JDatabase
	 */
	protected $db = null;


	/**
	 * The element name.
	 * @var string
	 */
	protected $element = null;
	protected $key = null;

	public function __construct($element, $config = array())
	{
		$this->db = KT::db();

		$this->app = JFactory::getApplication();
		$this->input = KT::request();
		$this->config = KT::config();


		// Set the element
		$this->element = $element;

		// Set the key element for this model.
		$index = 'com_komento';

		if (isset($config['namespace'])) {
			$index .= '.' . $config['namespace'];
		}

		$index .= '.' . $element;

		$this->key = $index;

		// We don't want to load any of the tables path because we use our own FD::table method.
		$options = array('table_path' => JPATH_ROOT . '/libraries/joomla/database/table');

		parent::__construct($options);
	}

	/**
	 * Get user's state from request
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getUserStateFromRequest($key, $default = '', $type = 'none')
	{
		$app = JFactory::getApplication();
		$namespace = $this->key . '.' . $key;
		$value = $app->input->get($key, null, $type);

		$value = $app->getUserStateFromRequest($namespace, $key, $default, $type);

		return $value;
	}

	/**
	 * Allows caller to pass in an array of data to normalize the data
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function normalize($data, $key, $default = null)
	{
		if (!$data) {
			return $default;
		}

		// $key cannot be an array
		if (is_array($key)) {
			$key = $key[0];
		}

		if (isset($data[$key])) {
			return $data[$key];
		}

		return $default;
	}

	/**
	 * Overrides parent's setUserState
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setUserState($key, $value)
	{
		$app = JFactory::getApplication();

		return $app->setUserState($this->key . '.' . $key, $value);
	}

	/**
	 * Overrides parent's getUserState
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getUserState($key, $default = null)
	{
		$app = JFactory::getApplication();

		return $app->getUserState($this->key . '.' . $key, $default);
	}

	protected function setTotal($query, $wrapTemporary = false)
	{
		if ($wrapTemporary) {
			$query 	= 'SELECT COUNT(1) FROM (' . $query . ') AS zcount';
		}

		// Debug
		// echo str_ireplace( '#__' , 'jos_' , $query );
		// echo "<br /><br />";

		$this->db->setQuery($query);

		$total = (int) $this->db->loadResult();

		// Set the total items here.
		$this->setState( 'total' , $total );

		$this->total = $total;

		return $total;
	}

	/**
	 * Overrides parent's setState
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setState( $key , $value = null )
	{
		$namespace 	= $this->key . '.' . $key;

		parent::setState( $namespace , $value );
	}

	/**
	 * Retrieve a list of state items
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getState( $keyItem = null , $default = null )
	{
		$key 	= $this->key . '.' .$keyItem;

		$value 	= parent::getState( $key );

		return $value;
	}

	protected function getTotal()
	{
		return $this->total;
	}

	protected function getData($query, $debug = false)
	{
		// If enforced to use limit, we get the limitstart values from properties.
		$limit = $this->getState('limit', null);
		$limitstart = $this->getState('limitstart', null);

		if (is_null($limit)) {
			$limit = 0;
		}

		if (is_null($limitstart)) {
			$limitstart = 0;
		}

		// Check if there's anything wrong with the limitstart because
		// User might be viewing on page 7 but switches a different view and it does not contain 7 pages.
		$total = $this->getTotal();


		if ($limitstart > $total) {
			$limitstart = 0;
			$this->setState('limitstart' , 0 );
		}

		if ($query instanceof SocialSql) {

			if ($limit) {
				$query->limit($limitstart, $limit);
			}

			$query = $query->getSql();

			$this->db->setQuery($query);
		} else {
			$this->db->setQuery($query, $limitstart, $limit);
		}

		return $this->db->loadObjectList();
	}

	/**
	 * Sets the limit state
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function setLimit( $limit = null )
	{
		if( is_null( $limit ) )
		{
			$jConfig 		= FD::jconfig();
			$systemLimit	= $jConfig->getValue( 'list_length' );
			$config = KT::config();

			$app 	= JFactory::getApplication();
			$limit 	= $app->getUserStateFromRequest( 'com_komento.' . $this->element . '.limit' , 'limit' , $config->get( $this->element . '.limit' , $systemLimit ) , 'int' );
		}

		$this->setState( 'limit' , $limit );

		return $this;
	}

	public function getForm($data = array(), $loadData = true)
	{
	}

	/**
	 * Stock method to auto-populate the model state.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function populateState()
	{
		// Load the parameters.
		$value = JComponentHelper::getParams($this->option);
		$this->setState('params', $value);
	}


}
