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

class KomentoThemesPanel
{
	/**
	 * Generates a settings row in the panel body
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function label($text, $help = true, $helpText = '', $columns = 5)
	{
		if ($help && !$helpText) {
			$helpText = JText::_($text . '_DESC');
		}

		$text = JText::_($text);

		$theme = KT::themes();
		$theme->set('columns', $columns);
		$theme->set('text', $text);
		$theme->set('help', $help);
		$theme->set('helpText', $helpText);

		$output = $theme->output('admin/helpers/panel/label');

		return $output;
	}

	/**
	 * Back end panel heading section
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function heading($text, $description = '')
	{
		if (!$description) {
			$description = $text . '_DESC';
		}

		$text = JText::_($text);
		$description = JText::_($description);

		$theme = KT::themes();
		$theme->set('text', $text);
		$theme->set('description', $description);

		$output = $theme->output('admin/helpers/panel/heading');

		return $output;
	}
}