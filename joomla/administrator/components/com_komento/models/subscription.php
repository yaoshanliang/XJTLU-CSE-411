<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

KT::import('admin:/includes/model');

class KomentoModelSubscription extends KomentoModel
{
	public $_total = null;
	public $_pagination	= null;
	public $_data = null;

	public function __construct()
	{
		parent::__construct('subscription');

		$mainframe = JFactory::getApplication();

		$limit = $mainframe->getUserStateFromRequest( 'com_komento.subscribers.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest( 'com_komento.subscribers.limitstart', 'limitstart', 0, 'int' );
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	}

	/**
	 * Get subscribers count for an item.
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function getSubscriberCount($component, $cid, $userOnly = true)
	{
		$db = KT::db();

		$query = "select count(1) from `#__komento_subscription`";
		$query .= " where `component` = " . $db->Quote($component);
		$query .= " and `cid` = " . $db->Quote($cid);
		if ($userOnly) {
			$query .= " and `userid` > 0";
		}
		$query .= " and `published` = " . $db->Quote(KOMENTO_STATE_PUBLISHED);

		$db->setQuery($query);
		$count = $db->loadResult();

		return $count ? $count : 0;
	}

	/**
	 * Determine if the subscription is exists for user or email on specified component
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public function checkSubscriptionExist($component, $cid, $userid = 0, $email = '', $type = 'comment')
	{
		$sql = KT::sql();

		$sql->select('#__komento_subscription')
			->column('published')
			->where('component', $component)
			->where('cid', $cid)
			->where('type', $type);

		if ($userid) {
			$sql->where('userid', $userid);
		} else {
			$sql->where('email', $email);
		}

		// $result = null (no subscription)
		// $result = 0 (subscribed but not confirmed)
		// $result = 1 (subscribed and confirmed)
		$result = $sql->loadResult();

		return $result;
	}

	public function getSubscribers($component, $cid)
	{
		$sql = KT::sql();

		$sql->select('#__komento_subscription')
			->column('fullname')
			->column('email')
			->where('component', $component)
			->where('cid', $cid)
			->where('published', 1);

		return $sql->loadObjectList();
	}

	/**
	 * Get total subscribers from the site
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function getTotalSubscribers()
	{
		$sql = KT::sql();

		$sql->select('#__komento_subscription')
			->column('1', 'total', 'count', true)
			->where('published', '1');

		$result	= $sql->loadResult();

		return (empty($result)) ? 0 : $result;
	}

	public function unsubscribe($component, $cid, $userid, $email = '', $type = 'comment')
	{
		$sql = KT::sql();

		$sql->delete('#__komento_subscription')
			->where('component', $component)
			->where('cid', $cid)
			->where('type', $type);

		if ($userid) {
			$sql->where('userid', $userid);
		} else {
			$sql->where('email', $email);
		}

		$state = $sql->query();

		if (!$state) {
			$this->setError($sql->db->getErrorMsg());
			return false;
		}

		return true;
	}

	public function confirmSubscription($token)
	{
		// Load the hashkey
		$hashkeys = KT::table('hashkeys');

		if (!$hashkeys->loadByKey($token)) {
			echo JText::_('COM_KOMENTO_INVALID_TOKEN');
			exit;
		}

		if (empty($hashkeys->uid)) {
			echo JText::_('COM_KOMENTO_CONFIRM_SUBSCRIPTION_INVALID_SUBSCRIBE_ID');
			exit;
		}

		$subscriptionTable = KT::table('subscription');
		$subscriptionTable->load($hashkeys->uid);
		$subscriptionTable->published = 1;

		if ($subscriptionTable->store()) {
			$hashkeys->delete();
		}

		// Get the item permalink so that we can redirect user to a proper page
		$model = KT::model('Comments');
		$itemPermalink = $model->getItemPermalink($subscriptionTable->component, $subscriptionTable->cid);

		$this->app->enqueueMessage(JText::_('COM_KOMENTO_NOTIFICATION_CONFIRMED_SUBSCRIPTION'));
		return $this->app->redirect($itemPermalink);
	}

	public function remove( $subscribers = array() )
	{
		if( $subscribers == null )
		{
			return false;
		}

		if( !is_array( $subscribers ) )
		{
			$subscribers = array($subscribers);
		}

		if( count( $subscribers ) < 1 )
		{
			return false;
		}

		$sql = KT::sql();

		$sql->delete( '#__komento_subscription' )
			->where( 'id', $subscribers, 'in' );

		$state = $sql->query();

		if( !$state )
		{
			$this->setError( $sql->db->getErrorMsg() );
			return false;
		}

		return true;
	}

	public function getUniqueComponents()
	{
		$sql = KT::sql();

		$sql->select( '#__komento_subscription' )
			->column( 'component', 'component', 'distinct' )
			->order( 'component' );

		return $sql->loadResultArray();
	}

	public function getItems()
	{
		if( empty( $this->_data ) )
		{
			$sql = $this->buildQuery();

			$sql->limit( $this->getState( 'limitstart' ), $this->getState( 'limit' ) );

			$this->_data = $sql->loadObjectList();
		}

		return $this->_data;
	}

	public function getPagination()
	{
		// Lets load the content ifit doesn't already exist
		if(empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}

	public function getTotal()
	{
		if( empty( $this->_total ) )
		{
			$sql = $this->buildQuery();

			$query = $sql->getTotalSql();

			$sql->db->setQuery( $query );
			$this->_total = $sql->db->loadResult();
		}

		return $this->_total;
	}

	public function buildQuery()
	{
		$mainframe	= JFactory::getApplication();

		$filter_component	= $mainframe->getUserStateFromRequest( 'com_komento.subscribers.filter_component', 'filter_component', '*', 'string' );
		$filter_type		= $mainframe->getUserStateFromRequest( 'com_komento.subscribers.filter_type', 'filter_type', '*', 'string' );
		$filter_order		= $mainframe->getUserStateFromRequest( 'com_komento.subscribers.filter_order', 'filter_order', 'created', 'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( 'com_komento.subscribers.filter_order_Dir',	'filter_order_Dir',	'DESC', 'word' );

		$sql = KT::sql();

		$sql->select( '#__komento_subscription' );

		if( $filter_component != '*' )
		{
			$sql->where( 'component', $filter_component );
		}

		if( $filter_type != '*' )
		{
			$sql->where( 'type', $filter_type );
		}

		$sql->order( $filter_order, $filter_order_Dir );

		return $sql;
	}

	public function getSubscribeGDPR($userId, $options = array())
	{
		$db = KT::db();

		$limit = isset($options['limit']) ? $options['limit'] : null;
		$exclude = isset($options['exclude']) ? $options['exclude'] : null;

		$query = 'SELECT *';
		$query .= ' FROM `#__komento_subscription`';
		$query .= ' WHERE `userid` = ' . $db->Quote($userId);

		if ($exclude) {
			$query .= ' AND `id` NOT IN(' . implode(',', $exclude) . ')';
		}

		$query .= ' ORDER BY `type`, `created` DESC';
		$query .= ' LIMIT 0,' . $limit;

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}
}
