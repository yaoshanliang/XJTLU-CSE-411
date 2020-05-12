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

require_once(dirname(__DIR__) . '/views.php');

class KomentoViewDashboard extends KomentoView
{
	/**
	 * Renders the delete confirmation dialog
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmDelete()
	{
		$items = $this->input->get('items');
		$return = $this->input->get('return');

		if (!$items) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		foreach ($items as &$id) {
			$id = (int) $id;
		}

		$theme = KT::themes();
		$theme->set('items', $items);
		$theme->set('return', $return);

		$output = $theme->output('site/dashboard/dialogs/delete');

		return $this->ajax->resolve($output);
	}

	/**
	 * Renders the delete confirmation dialog
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmSpam()
	{
		$items = $this->input->get('items');
		$return = $this->input->get('return');

		if (!$items) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		foreach ($items as &$id) {
			$id = (int) $id;
		}

		$theme = KT::themes();
		$theme->set('items', $items);
		$theme->set('return', $return);
		$theme->set('action', 'add');

		$output = $theme->output('site/dashboard/dialogs/spam');

		return $this->ajax->resolve($output);
	}

	/**
	 * Renders the delete confirmation dialog
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmRemoveSpam()
	{
		$items = $this->input->get('items');
		$return = $this->input->get('return');

		if (!$items) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		foreach ($items as &$id) {
			$id = (int) $id;
		}

		$theme = KT::themes();
		$theme->set('items', $items);
		$theme->set('return', $return);
		$theme->set('action', 'remove');

		$output = $theme->output('site/dashboard/dialogs/spam');

		return $this->ajax->resolve($output);
	}

	/**
	 * Renders the approve confirmation dialog
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmModerate()
	{
		$items = $this->input->get('items');
		$return = $this->input->get('return');
		$action = $this->input->get('action');

		if (!$items) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		foreach ($items as &$id) {
			$id = (int) $id;
		}

		$theme = KT::themes();
		$theme->set('items', $items);
		$theme->set('return', $return);
		$theme->set('action', $action);

		$output = $theme->output('site/dashboard/dialogs/moderate');

		return $this->ajax->resolve($output);
	}

	/**
	 * Renders the clear report confirmation dialog
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmClearReports()
	{
		$items = $this->input->get('items');
		$return = $this->input->get('return');

		if (!$items) {
			return JError::raiseError('Invalid comment id provided');
		}

		foreach ($items as &$id) {
			$id = (int) $id;
		}

		$theme = KT::themes();
		$theme->set('items', $items);
		$theme->set('return', $return);

		$output = $theme->output('site/dashboard/dialogs/clear.reports');

		return $this->ajax->resolve($output);
	}

	/**
	 * Publishes comments
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function publish()
	{
		$items = $this->input->get('id', array(), 'int');

		if (!$items) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		foreach ($items as $id) {
			$comment = KT::comment($id);

			if (!$id || !$comment->id) {
				return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
			}

			if (!$comment->canPublish()) {
				return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_UNPUBLISH_COMMENT'));
			}

			// Unpublish the comment
			$comment->publish();
		}

		$message = JText::_('COM_KOMENTO_SELECTED_COMMENTS_PUBLISHED_SUCCESSFULLY');

		return $this->ajax->resolve($message);
	}

	/**
	 * Unpublishes comments
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function unpublish()
	{
		$items = $this->input->get('id', array(), 'int');

		if (!$items) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		foreach ($items as $id) {
			$comment = KT::comment($id);

			if (!$id || !$comment->id) {
				return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
			}

			if (!$comment->canUnpublish()) {
				return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_UNPUBLISH_COMMENT'));
			}

			// Unpublish the comment
			$comment->publish(0);
		}

		$message = JText::_('COM_KOMENTO_SELECTED_COMMENTS_UNPUBLISHED_SUCCESSFULLY');

		return $this->ajax->resolve($message);
	}


	/**
	 * Show dialog confirmation for download
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function confirmDownload()
	{
		$userId = $this->my->id;

		$table = KT::table('download');
		$table->load(array('userid' => $userId));
		$state = $table->getState();

		$email = $this->my->email;
		$emailPart = explode('@', $email);
		$email = JString::substr($emailPart[0], 0, 2) . '****' . JString::substr($emailPart[0], -1) . '@' . $emailPart[1];

		$theme = KT::themes();
		$theme->set('userId', $userId);
		$theme->set('email', $email);
		$output = $theme->output('site/dashboard/dialogs/gdpr.confirm');

		return $this->ajax->resolve($output);
	}
}