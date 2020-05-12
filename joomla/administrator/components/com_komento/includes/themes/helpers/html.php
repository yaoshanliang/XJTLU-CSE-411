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

class KomentoThemesHtml
{
	/**
	 * Displays the avatar of the user
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function avatar($user = null, $name = '', $email = '', $website = '')
	{
		$config = KT::getConfig();

		if (!$config->get('layout_avatar_enable')) {
			return;
		}

		$id = null;

		if (is_string($user) || is_int($user)) {
			$id = $user;
		}

		if (is_null($user)) {
			$id = (int) JFactory::getUser()->id;
		}

		if (is_object($user)) {
			$id = (int) $user->id;
		}

		$user = KT::user($id);

		static $items = array();

		$key = $id . $name . $email . $website;

		if (isset($items[$key])) {
			return $items[$key];
		}

		$namespace = 'site/html/avatar';

		$theme = KT::themes();
		$theme->set('name', $name);
		$theme->set('email', $email);
		$theme->set('website', $website);
		$theme->set('user', $user);

		$items[$key] = $theme->output($namespace);

		return $items[$key];
	}

	/**
	 * Displays the name of the user
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function name($user = null, $name = '', $email = '', $website = '', $application = null)
	{
		$config = KT::config();

		$id = null;

		if (is_int($user) || is_string($user)) {
			$id = $user;
		}

		if (is_null($user)) {
			$id = JFactory::getUser()->id;
		}

		if (is_object($user)) {
			$id = $user->id;
		}

		$applicationAuthorId = null;

		if ($application) {
			$applicationAuthorId = $application->getAuthorId();
		}

		static $items = array();

		$key = $id . $name . $email . $website . $applicationAuthorId;

		if (isset($items[$key])) {
			return $items[$key];
		}

		$user = KT::user($id);
		$config = KT::config();

		$nofollow = $config->get('links_nofollow') ? ' rel="nofollow"' : '';

		$theme = KT::themes();
		$theme->set('applicationAuthorId', $applicationAuthorId);
		$theme->set('nofollow', $nofollow);
		$theme->set('user', $user);
		$theme->set('name', $name);
		$theme->set('email', $email);
		$theme->set('website', $website);
		$items[$key] = $theme->output('site/html/name');

		return $items[$key];
	}

	/**
	 * Helper to display the date
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function date($dateString)
	{
		$config = KT::config();
		$date = KT::date($dateString);

		if ($config->get('enable_lapsed_time')) {
			return $date->toLapsed();
		}
		
		return $date->toFormat($config->get('date_format'));
	}

	/**
	 * Helper to generate the empty block
	 *
	 * @since	3.0
	 * @access	public
	 * @return
	 */
	public static function emptyBlock($text, $icon, $withBorders = false)
	{
		$text = JText::_($text);

		$theme = KT::template();
		$theme->set('withBorders', $withBorders);
		$theme->set('text', $text);
		$theme->set('icon', $icon);

		$output = $theme->output('site/html/empty');

		return $output;
	}

}
