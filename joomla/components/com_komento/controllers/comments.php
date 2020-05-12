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

require_once(__DIR__ . '/base.php');

class KomentoControllerComments extends KomentoControllerBase
{
	/**
	 * Allows caller to delete a comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function delete()
	{
		KT::checkToken();

		$ids = $this->input->get('id', 0, 'int');

		if (!is_array($ids)) {
			$ids = array($ids);
		}

		// Get the return url
		$returnUrl = $this->getReturnUrl();

		foreach ($ids as $id) {
			$id = (int) $id;
			$comment = KT::comment($id);

			if (!$id || !$comment->id) {
				return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
			}

			// Ensure that the user is really allowed to delete
			if (!$comment->canDelete()) {
				return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_DELETE_COMMENT'));	
			}

			// Try to delete the comment now
			$state = $comment->delete();

			if (!$state) {
				$this->app->enqueueMessage($comment->getError(), 'error');
				return $this->app->redirect($returnUrl);
			}
		}

		$this->app->enqueueMessage(JText::_('COM_KOMENTO_COMMENT_DELETED_SUCCESSFULLY'));
		return $this->app->redirect($returnUrl);
	}

	/**
	 * Allows caller to submit a comment as spam
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function spam()
	{
		KT::checkToken();

		$ids = $this->input->get('id', 0, 'int');
		$action = $this->input->get('action', 'add');

		if (!is_array($ids)) {
			$ids = array($ids);
		}

		// Get the return url
		$returnUrl = $this->getReturnUrl();

		foreach ($ids as $id) {
			$id = (int) $id;
			$comment = KT::comment($id);

			if (!$id || !$comment->id) {
				return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
			}

			// Ensure that the user is really allowed to submit spam
			if (!$comment->canSubmitSpam()) {
				return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_SUBMIT_COMMENT_AS_SPAM'));
			}

			if ($action == 'add') {
				$state = $comment->spam();
			} else {
				$state = $comment->publish();
			}

			if (!$state) {
				$this->app->enqueueMessage($comment->getError(), 'error');
				return $this->app->redirect($returnUrl);
			}
		}

		$this->app->enqueueMessage(JText::_('COM_KOMENTO_COMMENT_SUBMIT_SPAM_SUCCESSFULLY'));
		return $this->app->redirect($returnUrl);
	}

	/**
	 * Allows caller to clear reported comments
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function clearReports()
	{
		KT::checkToken();

		$ids = $this->input->get('id', 0, 'int');

		if (!is_array($ids)) {
			$ids = array($ids);
		}

		if (!$ids) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		// Get the return url
		$returnUrl = $this->getReturnUrl();

		foreach ($ids as $id) {
			$id = (int) $id;

			$comment = KT::comment($id);
			$comment->removeReport();
		}

		$message = JText::_('COM_KOMENTO_REPORT_CLEARED_FROM_SELECTED_COMMENTS');
		$this->app->enqueueMessage($message);

		return $this->app->redirect($returnUrl);
	}

	/**
	 * Allows caller to moderate a comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function moderate()
	{
		KT::checkToken();

		$ids = $this->input->get('id', 0, 'int');
		$action = $this->input->get('action', 'approve');

		if (!is_array($ids)) {
			$ids = array($ids);
		}

		// Get the return url
		$returnUrl = $this->getReturnUrl();

		foreach ($ids as $id) {
			$id = (int) $id;
			$comment = KT::comment($id);

			if (!$id || !$comment->id) {
				return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));				
			}

			// Ensure that the user is really allowed to submit spam
			if (!$comment->canManage()) {
				return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_MODERATE_COMMENT'));	
			}

			if ($action == 'approve') {
				$state = $comment->publish();
				$message = JText::_('COM_KOMENTO_APPROVED_COMMENT');
			} else {
				$state = $comment->publish(0);
				$message = JText::_('COM_KOMENTO_REJECTED_COMMENT');
			}

			if (!$state) {
				$this->app->enqueueMessage($comment->getError(), 'error');
				return $this->app->redirect($returnUrl);
			}
		}

		$this->app->enqueueMessage($message);
		return $this->app->redirect($returnUrl);
	}
}
