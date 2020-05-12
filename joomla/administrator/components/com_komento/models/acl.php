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

KT::import('admin:/includes/model');

class KomentoModelAcl extends KomentoModel
{
	/**
	 * Category total
	 *
	 * @var integer
	 */
	protected $total = null;

	/**
	 * Pagination object
	 *
	 * @var object
	 */
	protected $pagination = null;

	/**
	 * Category data array
	 *
	 * @var array
	 */
	protected $data = null;


	protected $systemComponents = array();

	public function __construct()
	{
		parent::__construct('acl');

		$mainframe = JFactory::getApplication();

		$limit = $mainframe->getUserStateFromRequest('com_komento.acls.limit', 'limit', $mainframe->getCfg('list_limit', 20), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

		$this->systemComponents = array(
				'com_config', 'com_finder', 'com_media', 'com_redirect', 'com_users', 'com_content', 'com_komento'
			);

		$this->db = KT::db();
	}

	public function getAclObject($cid = 0, $type = 'usergroup')
	{
		$sql = KT::sql();

		$sql->select('#__komento_acl')
			->column('rules')
			->where('cid', $cid)
			->where('type', $type);

		$result = $sql->loadResult();

		if (empty($result)) {
			return false;
		}

		$result = json_decode($result);

		return $result;
	}

	public function updateUserGroups()
	{
		$userGroups	= KT::getUsergroups();
		$userGroupIDs = array();

		foreach ($userGroups as $userGroup) {
			$userGroupIDs[] = $userGroup->id;
		}

		$sql = $this->db->sql();

		$sql->select('#__komento_acl')
			->column('cid')
			->where('type', 'usergroup');

		$this->db->setQuery($sql);

		$current = $this->db->loadObjectList();

		KT::import('admin:/includes/acl/acl');

		$defaultset = KT::ACL()->getEmptySet(true);
		$defaultset = json_encode($defaultset);

		foreach ($userGroupIDs as $userGroupID) {
			if (!in_array($userGroupID, $current)) {

				$table = KT::table('acl');
				$table->cid = $userGroupID;
				$table->type = 'usergroup';
				$table->rules = $defaultset;

				$table->store();
			}
		}
	}

	public function getData($type = 'usergroup', $cid = 0)
	{
		static $_cache = array();

		$idx = $type . $cid;

		if (isset($_cache[$idx])) {
			return $_cache[$idx];
		}

		$query = '';
		$query .= 'SELECT `rules` FROM ' . $this->db->nameQuote('#__komento_acl');
		$query .= ' WHERE `type` = ' . $this->db->quote($type);
		$query .= ' AND `cid` = ' . $this->db->quote($cid);
		$query .= ' ORDER BY `type`';

		$this->db->setQuery($query);

		$rulesets = $this->db->loadResult();

		if (empty($rulesets)){
			$rulesets = new stdClass();
		} else {
			$rulesets = json_decode($rulesets);
		}

		$defaultset = KT::ACL()->getEmptySet();

		foreach ($defaultset as $section => &$rules) {

			foreach ($rules as $key => &$value) {

				if (isset($rulesets->$key)) {
					$value = $rulesets->$key;
				}
			}
		}

		$_cache[$idx] = $defaultset;

		return $defaultset;
	}

	public function save($data)
	{
		$cid = $data['target_id'];
		$type = $data['target_type'];

		KT::import('admin:/includes/acl/acl');

		$defaultset = KT::ACL()->getEmptySet(true);

		foreach ($defaultset as $key => $value) {
			if (isset($data[$key])) {
				$defaultset->$key = $data[$key] ? true : false;
			}
		}

		$table = KT::table('Acl');
		$table->compositeLoad($cid, $type);

		$table->rules = json_encode($defaultset);

		return $table->store();
	}
}
