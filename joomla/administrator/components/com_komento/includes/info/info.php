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

class KomentoInfo extends KomentoBase
{
	static $instance = null;

	/**
	 * Sets a message in the queue.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function set($message = '', $class = '', $namespace = null)
	{
		$session = JFactory::getSession();

		$obj = new stdClass();
		$obj->message = JText::_($message);
		$obj->type = $class;

		$data = serialize($obj);

		$messages = $session->get('messages', array(), KOMENTO_SESSION_NAMESPACE);

		// Namespacing purposes to prevent duplication
		// Without namespacing (backwards/legacy), messages will just get queued indefinitely
		// With namespacing, only 1 instance of the same message should exist
		if (empty($namespace)) {
			$messages[]	= $data;
		} else {
			$messages[$namespace] = $data;
		}

		$session->set('messages', $messages, KOMENTO_SESSION_NAMESPACE);
	}

	/**
	 * Generates the info html block
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function html($admin = false)
	{
		$output = '';
		$session = JFactory::getSession();
		$messages = $session->get('messages', array(), KOMENTO_SESSION_NAMESPACE);

		// Remove this data once we retrieved it.
		$session->clear('messages', KOMENTO_SESSION_NAMESPACE);

		// If there's nothing stored in the session, ignore this.
		if (!$messages) {
			return;
		}

		foreach ($messages as $message) {
			$data = unserialize($message);

			if (!is_object($data)) {

				$obj = new stdClass();
				$obj->message = $data;
				$obj->type = 'info';

				$data = $obj;
			}

			
			$theme = KT::themes();

			$theme->set('content', $data->message);
			$theme->set('class', $data->type);

			$prefix = $admin ? 'admin' : 'site';

			$output .= $theme->output($prefix . '/info/default');
		}

		return $output;

	}
}
