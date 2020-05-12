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

class KomentoThemesTree
{
	/**
	 * Renders the user group tree listing.
	 *
	 * @since	3.0
	 * @access	public
	 * @param	object	The object to check against.
	 * @param	string	The controller to be called.
	 * @param	string	The key for the object.
	 *
	 */
	public static function groups($name = 'gid', $selected = '', $exclude = array(), $checkSuperAdmin = false)
	{
		static $count;

		$count++;

		if (is_string($selected)) {
			$selected = explode(',', $selected);
		}

		$groups = self::getGroups();

		$theme = KT::template();

		$isSuperAdmin = JFactory::getUser()->authorise('core.admin');

		$theme->set('name', $name);
		$theme->set('checkSuperAdmin', $checkSuperAdmin);
		$theme->set('isSuperAdmin', $isSuperAdmin);
		$theme->set('selected', $selected);
		$theme->set('count', $count);
		$theme->set('groups', $groups);

		return $theme->output('admin/helpers/tree/groups');
	}

	private static function getGroups()
	{
		$db = KT::db();
		$sql = $db->sql();

		$sql->select('#__usergroups', 'a');
		$sql->column('a.*');
		$sql->column('b.id', 'level', 'count distinct');
		$sql->join('#__usergroups', 'b');
		$sql->on('a.lft', 'b.lft', '>');
		$sql->on('a.rgt', 'b.rgt', '<');
		$sql->group('a.id', 'a.title', 'a.lft', 'a.rgt', 'a.parent_id');
		$sql->order('a.lft', 'ASC');

		$db->setQuery($sql);
		$groups = $db->loadObjectList();

		return $groups;
	}
}
