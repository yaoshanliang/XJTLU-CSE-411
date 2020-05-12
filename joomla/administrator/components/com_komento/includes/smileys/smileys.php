<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class KomentoSmileys extends KomentoBase
{
	/**
	 * This class uses the factory pattern.
	 *
	 * @since	3.1
	 * @access	public
	 */
	public static function factory()
	{
		$obj = new self();

		return $obj;
	}

	public function getEmojis()
	{
		static $icons = array();

		if (!$icons) {
			$library = KOMENTO_LIB . '/smileys/emoticons.json';

			$jsonFileOverride = JPATH_ROOT . '/templates/' . JFactory::getApplication()->getTemplate() . '/html/com_komento/emoticons/emoticons.json';
			if (JFile::exists($jsonFileOverride)) {
				$library = $jsonFileOverride;
			}

			$contents = JFile::read($library);
			$items = json_decode($contents);
			$icons = array();

			foreach ($items as $key => $item) {

				$icons[$key] = new stdClass();

				$icons[$key]->key = $key;
				$icons[$key]->override = false;

				// Test if override exists
				$override =  JPATH_ROOT . '/templates/' . $this->app->getTemplate() . '/html/com_komento/emoticons/' . $key . '.png';

				if (JFile::exists($override)) {
					$icons[$key]->image = JURI::root() . 'templates/' . $this->app->getTemplate() . '/html/com_komento/emoticons/' . $key . '.png';
					$icons[$key]->override = true;
				} else {
					$icons[$key]->image = JURI::root() . 'media/com_komento/images/icons/emoji/' . $key . '.png';
				}

				$icons[$key]->command = $item[0];
				$icons[$key]->commands = $item;
			}
		}

		return $icons;
	}

	/**
	 * Generates a list of smileys
	 *
	 * @since	3.1
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function html()
	{
		$icons = $this->getEmojis();

		$theme = KT::themes();
		$theme->set('icons', $icons);
		$output = $theme->output('site/smileys/default');

		return $output;
	}
}
