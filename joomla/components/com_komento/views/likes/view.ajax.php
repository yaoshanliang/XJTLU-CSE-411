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

class KomentoViewLikes extends KomentoView
{
	/**
	 * Allows caller to like / unlike a comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function action()
	{
		$likes = KT::likes();

		if (!$likes->isEnabled()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_LIKE_COMMENT'));
		}

		// Comment id
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		// Get the action type
		$action = $this->input->get('type', '', 'word');
		$allowed = array('like', 'unlike');

		if (!in_array($action, $allowed)) {
			return JError::raiseError(500, 'Unknown action');
		}

		if ($action == 'like' && $likes->isLiked($comment->id, $this->my->id)) {
			return $this->ajax->reject();
		}

		// Perform the action
		$likes->$action($comment);

		return $this->ajax->resolve();
	}

	/**
	 * Preview a list of users that also likes the comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function browse()
	{
		$likes = KT::likes();

		if (!$likes->isEnabled()) {
			return JError::raiseError(500, 'COM_KOMENTO_LIKES_ERROR_NOT_ALLOWED_VIEW_LIKES');
		}

		// Comment id
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, 'COM_KOMENTO_LIKES_ERROR_INVALID_COMMENT_ID');
		}

		$model = KT::model('Actions');

		// Get maximum of 10 likes only for preview
		$users = $model->getLikedUsers($comment->id, 10);

		// Get total likes
		$total = $this->input->get('total', 0, 'int');

		$theme = KT::themes();
		$theme->set('users', $users);
		$theme->set('id', $comment->id);
		$theme->set('total', $total);
		$output = $theme->output('site/likes/users/default');

		return $this->ajax->resolve($output);
	}

	/**
	 * Renders a list of users that likes the comment in form of dialog
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function browseAll()
	{
		$likes = KT::likes();

		if (!$likes->isEnabled()) {
			return JError::raiseError(500, 'COM_KOMENTO_LIKES_ERROR_NOT_ALLOWED_VIEW_LIKES');
		}

		// Comment id
		$id = $this->input->get('id', 0, 'int');

		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, 'COM_KOMENTO_LIKES_ERROR_INVALID_COMMENT_ID');
		}

		$model = KT::model('Actions');
		$users = $model->getLikedUsers($comment->id);

		$theme = KT::themes();
		$theme->set('users', $users);
		$output = $theme->output('site/likes/dialogs/default');

		return $this->ajax->resolve($output);
	}
}
