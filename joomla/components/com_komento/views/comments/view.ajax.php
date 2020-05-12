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

require_once(dirname(__DIR__) . '/views.php');

class KomentoViewComments extends KomentoView
{
	public function __construct()
	{
		parent::__construct();

		$component = $this->input->get('component', '', 'cmd');
		$cid = $this->input->get('cid', 0, 'int');
		$id = $this->input->get('id', 0, 'int');

		if ($component == '' && $cid == 0 && $id != 0) {
			$tmp = KT::getTable('comments');
			$tmp->load($id);

			$component = $tmp->component;
			$cid = $tmp->cid;
		}

		if ($component) {
			KT::setCurrentComponent($component);
		}
	}

	/**
	 * Allows caller to submit a new comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function add()
	{
		KT::checkToken();

		$data = $this->input->getArray('post');
		$component = $this->input->get('component', '', 'cmd');
		$cid = $this->input->get('cid', 0, 'int');

		$application = KT::loadApplication($component)->load($cid);

		if ($application === false) {
			$application = KT::getErrorApplication($component, $cid);
		}

		$comment = KT::comment();

		if (!$comment->canPostComments()) {
			return $this->ajax->reject($comment->getError());
		}

		// We need to get the comment
		$data['comment'] = $this->input->get('comment', '', 'raw');

		// Bind the data
		$comment->bind($data);

		// Validate the comment
		$state = $comment->validate($data);

		if ($state === false) {
			return $this->ajax->reject($comment->getError());
		}

		// Validate the comment for spam
		$state = $comment->validateSpam();

		if ($state === false) {
			return $this->ajax->reject($comment->getError());
		}

		// Save the background params
		if (isset($data['preset'])) {
			$params = $comment->getParams();
			$params->set('preset', $data['preset']);
			
			$comment->params = $params->toString();
		}

		$options = array('processAttachments' => true);

		// Save the comment
		$state = $comment->save($options);

		// Throw some errors
		if ($state === false) {
			JError::raiseError(500, $comment->getError());
		}

		// Initialise additional values = 0
		$comment->likes = 0;
		$comment->liked = 0;
		$comment->sticked = 0;
		$comment->childs = 0;
		$comment->reported = 0;

		// Since people can post comments, we will initialize locked as false
		$options = array('lock' => false);

		// Format the comments
		$comment = KT::formatter('comment', $comment->table);

		$theme = KT::themes();
		$theme->set('comment', $comment);
		$theme->set('application', $application);

		$html = $theme->output('site/comments/item');

		// Purge the cache in case the site is caching contents
		JFactory::getCache()->clean();

		$message = JText::_('COM_KOMENTO_FORM_NOTIFICATION_SUBMITTED');

		if ($comment->published == KOMENTO_COMMENT_MODERATE) {
			$message = JText::_('COM_KOMENTO_FORM_NOTIFICATION_PENDING');
		}

		if ($comment->published == KOMENTO_COMMENT_SPAM) {
			$message = JText::_('COM_KOMENTO_FORM_NOTIFICATION_SPAM');
		}

		$sorting = KT::config()->get('default_sort', 'oldest');

		return $this->ajax->resolve($message, $html, $comment->published, $sorting);
	}

	public function loadmore()
	{
		// $config = KT::getConfig();
		$profile = KT::user();
		// $ajax = KT::getAjax();
		$commentsModel = KT::model('comments');

		$defaultsort = $this->config->get('default_sort', 'oldest');
		$limit = $this->config->get('max_comments_per_page');
		$component = $this->input->get('component', '', 'cmd');
		$cid = $this->input->get('cid', '', 'cmd');
		$start = $this->input->get('start', 0, 'default');
		$sort = $this->input->get('sort', $defaultsort, 'default');

		$application = KT::loadApplication($component)->load($cid);

		if ($application === false) {
			$application = KT::getErrorApplication($component, $cid);
		}

		$options = array(
			'limit' => $limit,
			'limitstart' => $start,
			'sort' => $sort,
			'parentid' => '0',
			'threaded' => $this->config->get('enable_threaded'),
			'loadreplies' => true,
			'sticked' => false
		);

		if (!$profile->allow('read_comment')) {
			return $this->ajax->reject(JText::_('COM_KOMENTO_ACL_NO_PERMISSION'));
		}

		$comments = $commentsModel->getComments($component, $cid, $options);
		$commentCount = $commentsModel->getTotal($component, $cid, $options);

		$loadedComments = count($comments);

		$html = "";

		if ($comments) {
			$comments = KT::formatter('comment', $comments, $options);

			foreach ($comments as $comment) {

				$theme = KT::themes();
				$theme->set('comment', $comment);
				$theme->set('application', $application);

				$html .= $theme->output('site/comments/item');
			}
		}

		$nextstart = $start + $limit;

		if ($nextstart >= $commentCount) {
			$nextstart = -1;
		}

		return $this->ajax->resolve($html, $nextstart);
	}

	public function loadComments()
	{
		$commentsModel = KT::model('comments');
		$profile = KT::user();

		$defaultsort = $this->config->get('default_sort', 'oldest');
		$limit = $this->config->get('max_comments_per_page');
		$component = $this->input->get('component', '', 'cmd');
		$cid = $this->input->get('cid', '', 'cmd');
		$endlimit = $this->input->get('endlimit', 0, 'default'); // endlimit
		$sort = $this->input->get('sort', $defaultsort, 'default');
		$sticked = $this->input->get('sticked', 1, 'default');

		$application = KT::loadApplication($component)->load($cid);

		if ($application === false) {
			$application = KT::getErrorApplication($component, $cid);
		}

		if ($endlimit) {
			// we are simulating the limit start using the 'limit' in query.
			$endlimit = $endlimit + $limit;
		} else {
			$endlimit = $limit;
		}

		$options = array(
			'limit' => $endlimit,
			'limitstart' => 0,
			'parentid' => 0,
			'sort' => $sort,
			'threaded' => $this->config->get('enable_threaded'),
			'loadreplies' => true,
			'sticked' => $sticked
		);

		if (!$profile->allow('read_comment')) {
			return $this->ajax->reject(JText::_('COM_KOMENTO_ACL_NO_PERMISSION'));
		}

		$comments = $commentsModel->getComments($component, $cid, $options);
		$commentCount = $commentsModel->getTotal($component, $cid, $options);


		$loadedComments = count($comments);

		if ($loadedComments == 0) {
			// this is not suppose to happen. return fail state.
			return $this->ajax->reject();
		}

		$html = "";

		if ($comments) {
			$comments = KT::formatter('comment', $comments, $options);

			foreach ($comments as $comment) {

				$theme = KT::themes();
				$theme->set('comment', $comment);
				$theme->set('application', $application);

				$html .= $theme->output('site/comments/item');
			}

		}

		$nextstart = $endlimit;

		if ($nextstart >= $commentCount) {
			$nextstart = -1;
		}

		return $this->ajax->resolve($html, $nextstart);
	}


	public function loadReplies()
	{
		$commentsModel = KT::model('comments');
		$profile = KT::user();

		$defaultsort = $this->config->get('default_sort', 'oldest');
		$limit = $this->config->get('reply_autohide') ? $this->config->get('reply_autohide_threshold') : 0;
		$component = $this->input->get('component', '', 'cmd');
		$cid = $this->input->get('cid', '', 'cmd');
		$sort = $this->input->get('sort', $defaultsort, 'default');
		$rownumber = $this->input->get('rownumber', 0, 'default');
		$parentId = $this->input->get('parentid', 0, 'default');

		$comment = KT::comment($parentId);
		$comment->rownumber = $rownumber;

		$application = KT::loadApplication($component)->load($cid);

		if ($application === false) {
			$application = KT::getErrorApplication($component, $cid);
		}

		$repliesCount = $comment->getRepliesCount();

		if (!$repliesCount) {
			return $this->ajax->resolve(false);
		}

		$endlimit = $limit;

		if ($repliesCount > $limit) {
			$endlimit = $repliesCount - $limit;
		}

		$options = array(
			'limit' => $endlimit,
			'parentid' => $parentId,
			'sort' => $sort,
			'published' => 1,
			'threaded' => $this->config->get('enable_threaded'),
			'startlimit' => 0
		);

		if (!$profile->allow('read_comment')) {
			return $this->ajax->reject(JText::_('COM_KOMENTO_ACL_NO_PERMISSION'));
		}

		$comments = $commentsModel->loadReplies($comment, $options, $endlimit);

		$loadedComments = count($comments);

		if ($loadedComments == 0) {
			// this is not suppose to happen. return fail state.
			return $this->ajax->reject();
		}

		$html = "";

		if ($comments) {
			$comments = KT::formatter('comment', $comments, $options);

			foreach ($comments as $comment) {

				$theme = KT::themes();
				$theme->set('comment', $comment);
				$theme->set('application', $application);

				$html .= $theme->output('site/comments/item');
			}

		}

		return $this->ajax->resolve($html);
	}



	/**
	 * Allows caller to reload a set of comments on the site
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function reload()
	{
		KT::checkToken();

		$component = $this->input->get('component', '', 'cmd');
		$cid = $this->input->get('cid', '', 'int');
		$sort = $this->input->get('sort', '', 'word');
		$contentLink = $this->input->get('contentLink', '', 'default');

		$model = KT::model('Comments');

		$application = KT::loadApplication($component)->load($cid);

		if ($application === false) {
			$application = KT::getErrorApplication($component, $cid);
		}

		if (!$this->my->allow('read_comment')) {
			return $this->ajax->reject('You are not allowed here');
		}

		// check if allowed in admin mode
		if ( isset( $options['published'] ) && $options['published'] != '1' && !$acl->allow( 'publish', '', $component, $cid ) ) {
			$ajax->fail(JText::_('COM_KOMENTO_ERROR'));
			$ajax->send();
		}

		$options = array();
		$options['sort'] = $sort;

		$total = $model->getCount($component, $cid, $options);

		// Load previous comments
		if ($this->config->get('load_previous')) {
			$options['limitstart'] = $total - $this->config->get('max_comments_per_page');

			if ($options['limitstart'] < 0) {
				$options['limitstart'] = 0;
			}
		}

		$options['threaded'] = $this->config->get('enable_threaded');

		$comments = $model->getComments($component, $cid, $options);

		$theme = KT::themes();
		$theme->set('ajaxcall', 1 );
		$theme->set('component', $component);
		$theme->set('cid', $cid);
		$theme->set('comments', $comments);
		$theme->set('options', $options);
		$theme->set('commentCount', $total);
		$theme->set('application', $application);
		$theme->set('contentLink', $contentLink);
		$html = $theme->output('site/comments/list.php');

		return $this->ajax->resolve($html, count($comments), $total);
	}

	public function getComment()
	{
		if (!$this->my->allow('read_comment')) {
			return $this->ajax->reject();
		}

		$id = $this->input->get('id', 0, 'int');

		$comment = KT::getComment($id);
		$comment = KomentoCommentHelper::process($comment);

		$themes = KT::themes();
		$themes->set('row', $comment);

		// todo: configurable
		$html  = $parentTheme->fetch( 'comment/item/avatar.php' );
		$html .= $parentTheme->fetch( 'comment/item/author.php' );
		$html .= $parentTheme->fetch( 'comment/item/time.php' );
		$html .= $parentTheme->fetch( 'comment/item/text.php' );

		return $this->ajax->resolve($html);
	}

	/**
	 * Allows caller to edit the comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function edit()
	{
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		if (!$comment->canEdit()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_EDIT_COMMENT'));
		}

		// get the background presets
		$bgModel = KT::model('Backgrounds');
		$presets = $bgModel->getPresets(array('published' => true));

		$theme = KT::themes();
		$theme->set('comment', $comment);
		$theme->set('presets', $presets);
		$output = $theme->output('site/form/edit/default');

		// Export other data incase we need these raw data
		$data = $comment->export();

		return $this->ajax->resolve($output, $data);
	}

	/**
	 * Allows caller to save an edited comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function save()
	{
		$id = $this->input->get('id', 0, 'int');
		$preset = $this->input->get('preset', 0, 'int');
		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		if (!$comment->canEdit()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_EDIT_COMMENT'));
		}

		$data = array();
		$data['comment'] = $this->input->get('comment', '', 'default');
		$data['modified_by'] = $this->my->id;
		$data['modified'] = JFactory::getDate()->toSql();

		$comment->bind($data);

		// Save the background params
		$params = $comment->getParams();
		$params->set('preset', $preset);
		
		$comment->params = $params->toString();


		$comment->save(array('isEdited' => true, 'ignorePreSave' => true));

		// Get the content so that we can update the comment's content
		$contents = $comment->getContent();
		$message = JText::sprintf('COM_KOMENTO_COMMENT_EDITTED_BY', $comment->getModifiedDate()->toLapsed(), KT::themes()->html('html.name', $comment->modified_by));

		return $this->ajax->resolve($message, $contents);
	}

	/**
	 * Renders the delete confirmation dialog
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmDelete()
	{
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		if (!$id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		if (!$comment->canDelete()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_DELETE_COMMENT'));
		}

		$theme = KT::themes();
		$theme->set('comment', $comment);

		$output = $theme->output('site/comments/dialogs/delete');

		return $this->ajax->resolve($output);
	}

	/**
	 * Renders the dialog to confirm submit comment as spam
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmSubmitSpam()
	{
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		$theme = KT::themes();
		$theme->set('comment', $comment);
		$output = $theme->output('site/comments/dialogs/spam');

		return $this->ajax->resolve($output);
	}

	/**
	 * Renders the dialog to confirm unpublish comments
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmUnpublish()
	{
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		$theme = KT::themes();
		$theme->set('comment', $comment);
		$output = $theme->output('site/comments/dialogs/unpublish');

		return $this->ajax->resolve($output);
	}

	/**
	 * Allows caller to delete a comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function delete()
	{
		$ids = $this->input->get('id', 0, 'int');

		if (!is_array($ids)) {
			$ids = array($ids);
		}

		$childs = array();

		foreach ($ids as $id) {
			$id = (int) $id;
			$comment = KT::comment($id);

			$childs = KT::model('comments')->getChilds($id);

			if (!$id || !$comment->id) {
				return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
			}

			// Ensure that the user is really allowed to delete
			if (!$comment->canDelete()) {
				return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_DELETE_COMMENT'));
			}

			$repliesCount = KT::model('comments')->getRepliesCount($comment);

			// Try to delete the comment now
			$state = $comment->delete();

			if (!$state) {
				return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_DELETE_COMMENT'));
			}
		}

		return $this->ajax->resolve($childs, $repliesCount);
	}

	/**
	 * Unpublishes a comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function unpublish()
	{
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		if (!$comment->canUnpublish()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_UNPUBLISH_COMMENT'));
		}

		// Unpublish the comment
		$state = $comment->publish(0);

		return $this->ajax->resolve();
	}

	public function submitSpam()
	{
		$id	= $this->input->get('id', 0, 'int');

		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		if (!$comment->canSubmitSpam()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_SUBMIT_COMMENT_AS_SPAM'));
		}

		// submit the comment as spam
		$comment->spam();

		return $this->ajax->resolve();
	}

	/**
	 * Expands a comment
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public function expand()
	{
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		// Ensure that the user has permissions
		if (!$comment->canMinimize()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_PIN_COMMENT'));
		}

		// Try to pin the comment now
		$comment->expand();

		return $this->ajax->resolve();
	}

	/**
	 * Minimizes a comment
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public function minimize()
	{
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		// Ensure that the user has permissions
		if (!$comment->canMinimize()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_PIN_COMMENT'));
		}

		// Try to pin the comment now
		$comment->minimize();

		return $this->ajax->resolve();
	}

	/**
	 * Pin a comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function pin()
	{
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		// Ensure that the user has permissions
		if (!$comment->canFeature()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_PIN_COMMENT'));
		}

		// Try to pin the comment now
		$comment->feature();

		return $this->ajax->resolve();
	}

	/**
	 * Unpins a comment
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function unpin()
	{
		$id = $this->input->get('id', 0, 'int');
		$comment = KT::comment($id);

		if (!$id || !$comment->id) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_ID'));
		}

		// Ensure that the user has permissions
		if (!$comment->canFeature()) {
			return JError::raiseError(500, JText::_('COM_KT_NOT_ALLOWED_PIN_COMMENT'));
		}

		// Try to pin the comment now
		$comment->unfeature();

		return $this->ajax->resolve();
	}

	/**
	 * Checks for new comments
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function check()
	{
		KT::checkToken();

		$component = $this->input->get('component', '', 'cmd');
		$cid = $this->input->get('cid', '', 'int');

		if (!$component || !$cid) {
			return JError::raiseError(500, JText::_('COM_KT_INVALID_REQUEST'));
		}

		$lastchecktime = $this->input->get('lastchecktime', '', 'default');

		if (! $lastchecktime) {
			// if empty, always use the current datetime
			$lastchecktime = KT::date()->toSql();
		}

		$my = KT::user();

		// We don't have to check if it is guest
		if (!$my->id) {
			return;
		}

		$model = KT::model('Comments');
		$newItems = $model->getNewCounts($component, $cid, $lastchecktime, $my->id);

		$nextchecktime = KT::date()->toSql();

		// Nothing has changed
		if (! $newItems) {
			return $this->ajax->resolve(false, 0, '', $nextchecktime);
		}

		$theme = KT::themes();
		$theme->set('newItems', $newItems);
		$output = $theme->output('site/notifications/popup');

		return $this->ajax->resolve(true, $newItems, $output, $nextchecktime);
	}

	/**
	 * Display terms and conditions dialog message
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getTnc()
	{
		KT::checkToken();

		$content = JText::_($this->config->get('tnc_text'));

		$content = nl2br($content);

		$theme = KT::themes();
		$theme->set('content', $content);
		$output = $theme->output('site/form/dialogs/tnc');

		return $this->ajax->resolve($output);
	}

	function checkAcl()
	{
		$rule		= $this->input->get('rule');
		$profile	= KT::getProfile();
		$ajax		= KT::getAjax();
		$ajax->success( $profile->allow( $rule ) );
		$ajax->send();
	}

	function checkPermission()
	{
		$id			= JRequest::getInt( 'id' );
		$action		= $this->input->get('action');
		$acl		= KT::getHelper( 'acl' );
		$ajax		= KT::getAjax();
		$ajax->success( $acl->allow( $action, $id ) );
		$ajax->send();
	}
}
