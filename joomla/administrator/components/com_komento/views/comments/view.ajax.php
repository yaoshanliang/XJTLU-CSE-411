<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

KT::import('admin:/views/views');

class KomentoViewComments extends KomentoAdminView
{
	public function stick()
	{
		$model = KT::model('comments');
		$ids = $this->input->get('ids');

		if ($model->stick($ids)) {
			$this->ajax->success();
		} else {
			$this->ajax->fail();
		}

		$this->ajax->send();
	}

	public function unstick()
	{
		$model = KT::model('comments');
		$ids = $this->input->get('ids');

		if ($model->unstick($ids)) {
			$this->ajax->success();
		} else {
			$this->ajax->fail();
		}

		$this->ajax->send();
	}

	public function publish()
	{
		$model = KT::model('comments');
		$ids = $this->input->get('ids');

		if ($model->publish($ids)) {
			$this->ajax->success();
		} else {
			$this->ajax->fail();
		}

		$this->ajax->send();
	}

	public function unpublish()
	{
		$model = KT::model('comments');
		$ids = $this->input->get('ids');

		if ($model->unpublish($ids)) {
			$this->ajax->success();
		} else {
			$this->ajax->fail();
		}

		$this->ajax->send();
	}

	public function akismet()
	{
		$id = $this->input->get('id', '', 'int');
		$submitType = $this->input->get('action', 'spam');

		if (empty($id)) {
			return $this->ajax->fail(JText::_('COM_KOMENTO_COMMENTS_COMMENT_INVALID_ID'));
		} 
			
		$message = 'COM_KOMENTO_AKISMET_SENT';

		$comment = KT::comment($id);

		// Akismet detection
		$akismetData = array(
			'author' => $comment->name,
			'email' => $comment->email,
			'website' => JURI::root(),
			'body' => $comment->comment
		);

		$action = ($submitType == 'spam') ? 'submitSpam' : 'submitHam';

		if (!KT::akismet()->$action($akismetData)) {
			return $this->ajax->fail(JText::_('COM_KOMENTO_AKISMET_ERROR'));
		}

		// Add flag to show that this has been sent to akismet
		$comment->flag(KOMENTO_COMMENT_AKISMET_TRAINED);

		return $this->ajax->resolve(JText::_('COM_KOMENTO_AKISMET_SENT'));
	}
}
