<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class KomentoCache extends KomentoBase
{
	// list item
	public $comments = null;

	// local scope
	private $comment = array();
	private $attachment = array();

	private $report = array();
	private $likes = array();
	private $replies = array();

	// types that we will be caching
	private $types = array('comment');


	/**
	 * Load comment's attchments in batch processing and cache the results
	 *
	 * @since	5.0
	 * @access	public
	 * @param	array
	 */
	public function cacheAttachments($comments)
	{
		$ids = array();
		$data = array();

		foreach ($comments as $comment) {
			$ids[] = $comment->id;

			// setup the initial values.
			$data[$comment->id] = array();
		}

		$model = KT::model('Uploads');
		$results = $model->loadBatchAttachments($ids);

		foreach ($results as $item) {
			$data[$item->uid][] = $item;
		}

		// now lets cache it.
		foreach ($ids as $id) {
			$this->attachment[$id] = $data[$id];
		}

	}

	/**
	 * Load comment's attchments in batch processing and cache the results
	 *
	 * @since	5.0
	 * @access	public
	 * @param	array
	 */
	public function cacheReplies($comments, $options = array())
	{
		$ids = array();
		$pcomments = array();
		$data = array();

		foreach ($comments as $comment) {
			// setup the initial values.
			$data[$comment->id] = array();
			$ids[] = $comment->id;


			$repliesCnt = 0;
			$boundary = ($comment->rgt - $comment->lft) - 1;

			if ($boundary > 0) {
				$repliesCnt = floor($boundary / 2);
			}

			if ($repliesCnt > 0) {
				$pcomments[] = $comment;
			}

		}

		if ($pcomments) {
			$model = KT::model('Comments');

			$allreplies = array();

			$repliesLimit = $this->config->get('reply_autohide') ? $this->config->get('reply_autohide_threshold') : 0;
			$repliesLimit = (int) $repliesLimit;

			// filter only published replies
			$options['published'] = 1;

			foreach ($pcomments as $pcomment) {

				$results = $model->loadReplies($pcomment, $options, $repliesLimit);

				if ($results) {
					$data[$pcomment->id] = $results;

					$allreplies = array_merge($allreplies, $results);
				}
			}

			if ($allreplies) {
				$allreplies = KT::formatter('comment', $allreplies, array('loadreplies' => false));
			}
		}

		foreach ($ids as $id) {
			$this->replies[$id] = $data[$id];
		}
	}


	public function cacheActionCounts($comments)
	{
		$ids = array();
		$data = array();

		foreach ($comments as $comment) {
			$ids[] = $comment->id;

			// setup the initial values.
			$data['likes'][$comment->id] = 0;
			$data['report'][$comment->id] = 0;
		}

		$model = KT::model('Actions');
		$results = $model->loadBatchActionCounts($ids);

		foreach ($results as $item) {
			$data[$item->type][$item->comment_id] = $item->cnt;
		}

		// now lets cache it.
		foreach ($ids as $id) {
			$this->report[$id] = $data['report'][$id];
			$this->likes[$id] = $data['likes'][$id];
		}
	}


	/**
	 * Adds a cache for a specific item type
	 *
	 * @since	5.0
	 * @access	public
	 * @param	std object (non jtable object), string
	 * @return  boolean
	 */
	public function set($item, $type = 'comment')
	{
		// Check if this item already exists.
		$this->{$type}[$item->id] = $item;
	}

	/**
	 * set cache for the object type
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string, string
	 * @return  std object (non jtable) /  array
	 */
	public function get($id, $type = 'comment')
	{
		if (isset($this->$type) && isset($this->{$type}[$id])) {
			return $this->{$type}[$id];
		}

		// There should be a fallback method if the cache doesn't exist
		// return $this->fallback($id, $type);
	}

	/**
	 * Retrieves a fallback
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function fallback($id, $type)
	{
		$table = KT::table($type);
		$table->load($id);

		$this->set($table, $type);

		return $table;
	}

	/**
	 * check if the cache for the object type exists or not
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string, string
	 * @type 	'post', 'category', 'tags'
	 * @return  boolean
	 */
	public function exists($id, $type = 'comment')
	{
		if (isset($this->$type) && isset($this->{$type}[$id])) {
			return true;
		}

		return false;
	}

}
