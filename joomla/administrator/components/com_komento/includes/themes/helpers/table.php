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

class KomentoThemesTable
{
	/**
	 * Renders the featured / unfeatured button for each row in a table
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function featured($view, $obj, $property = 'default', $tasks = 'toggleFeatured', $allowed = true)
	{
		$theme = KT::template();

		$class = 'default';

		if ($obj->$property) {
			$class = 'featured';
		}

		$theme->set('class', $class);
		$theme->set('allowed', $allowed);
		$theme->set('task', $tasks);

		$output = $theme->output('admin/helpers/table/state');

		return $output;
	}

	/**
	 * Renders a dropdown for filtering resultset purpose
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function filter($name, $selected, $items = array())
	{
		$theme = KT::template();


		// Determines if there is a default option by determining the empty key in $items
		$keys = array_keys($items);

		$initial = JText::_('COM_KOMENTO_TABLE_FILTER');

		if (in_array('', $keys, true)) {
			$initial = JText::_($items['']);
		}

		// If there is no items, we set the default filters
		if (!$items) {
			$items = array('P' => 'COM_KOMENTO_TABLE_FILTER_PUBLISHED', 'U' => 'COM_KOMENTO_TABLE_FILTER_UNPUBLISHED');
		}

		$theme->set('initial', $initial);
		$theme->set('items', $items);
		$theme->set('name', $name);
		$theme->set('selected', $selected);

		$output = $theme->output('admin/helpers/table/filter');

		return $output;
	}

	/**
	 * Displays a search box in the filter
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function search($value = '', $name = 'search')
	{
		$theme = KT::template();

		$theme->set('value', $value);
		$theme->set('name', $name);

		$contents = $theme->output('admin/helpers/table/search');

		return $contents;
	}
}
