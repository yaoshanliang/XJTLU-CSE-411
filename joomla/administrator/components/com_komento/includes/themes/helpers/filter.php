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

class KomentoThemesFilter
{
	/**
	 * Displays a search box in the filter
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function search($value = '', $name = 'search')
	{
		$theme = KT::themes();

		$theme->set('value', $value);
		$theme->set('name', $name);

		$contents = $theme->output('admin/helpers/filter/search');

		return $contents;
	}

	/**
	 * Displays the number of items per page selection
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function limit($selected = 5, $name = 'limit', $step = 5, $min = 5, $max = 100, $showAll = true)
	{
		$theme = KT::themes();

		$theme->set('selected', $selected);
		$theme->set('name', $name);
		$theme->set('step', $step);
		$theme->set('min', $min);
		$theme->set('max', $max);
		$theme->set('showAll', $showAll);

		$contents = $theme->output('admin/helpers/filter/limit');

		return $contents;
	}
}
