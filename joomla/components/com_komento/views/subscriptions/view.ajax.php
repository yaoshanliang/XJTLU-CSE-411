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
defined('_JEXEC') or die('Unauthorized Access');

require_once(dirname(__DIR__) . '/views.php');

class KomentoViewSubscriptions extends KomentoView
{
	/**
	 * Renders the subscribe dialog
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public function subscribe()
	{
		$component = $this->input->get('component', '', 'cmd');
		$cid = $this->input->get('cid', 0, 'int');

		$theme = KT::themes();
		$theme->set('component', $component);
		$theme->set('cid', $cid);
		$output = $theme->output('site/subscriptions/dialogs/form');

		return $this->ajax->resolve($output);
	}

	/**
	 * Renders the unsubscribe dialog
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public function confirmUnsubscribe()
	{
		$component = $this->input->get('component', '', 'cmd');
		$cid = $this->input->get('cid', 0, 'int');

		$theme = KT::themes();
		$theme->set('component', $component);
		$theme->set('cid', $cid);
		$output = $theme->output('site/subscriptions/dialogs/unsubscribe');

		return $this->ajax->resolve($output);
	}
}
