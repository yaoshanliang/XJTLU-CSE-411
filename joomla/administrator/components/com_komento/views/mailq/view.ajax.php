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
defined('_JEXEC') or die('Unauthorized Access');

KT::import('admin:/views/views');

class KomentoViewMailq extends KomentoAdminView
{
	/**
	 * Previews an email
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function preview()
	{
		$id = $this->input->get('id', 0, 'int');

		$table = KT::table('Mailq');
		$table->load($id);

		$theme = KT::template();
		$theme->set('mailer', $table);
		$contents = $theme->output('admin/mailq/dialogs/preview');

		return $this->ajax->resolve($contents);
	}

	/**
	 * Confirmation before purging everything
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmPurgeAll()
	{
		$theme 	= KT::template();
		$contents = $theme->output('admin/mailq/dialogs/purge.all');

		return $this->ajax->resolve($contents);
	}

	/**
	 * Confirmation before purging pending e-mails
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmPurgeSent()
	{
		$theme = KT::template();
		$contents = $theme->output('admin/mailq/dialogs/purge.sent');

		return $this->ajax->resolve($contents);
	}

	/**
	 * Confirmation before purging pending e-mails
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmPurgePending()
	{
		$theme = KT::template();
		$contents = $theme->output('admin/mailq/dialogs/purge.pending');

		return $this->ajax->resolve($contents);
	}	

	/**
	 * Display confirmation dialog to reset email theme files
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function confirmReset()
	{
		$files = $this->input->get('files', array(), 'default');

		$theme = KT::template();
		$theme->set('files', $files);
		$contents = $theme->output('admin/mailq/dialogs/reset.default');

		return $this->ajax->resolve($contents);
	}
}