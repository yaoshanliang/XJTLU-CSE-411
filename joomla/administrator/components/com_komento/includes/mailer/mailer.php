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

class KomentoMailer extends KomentoBase
{
	/**
	 * Send emails on page load
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function sendOnPageLoad($max = 5)
	{
		static $sent = null;

		if (!$sent) {
			$this->send($max);
			$sent = true;
		}
	}

	/**
	 * Main method to dispatch e-mails out
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function send($max = 5)
	{
		$db = KT::db();
		$sql = KT::sql();

		$sql->select('#__komento_mailq')
			->column('id')
			->where('status', 0)
			->order('created')
			->limit($max);

		$result = $sql->loadObjectList();

		if (!empty($result)) {
			foreach ($result as $mail) {
				$mailq = KT::table('Mailq');
				$mailq->load($mail->id);

				$sendHTML = $mailq->type == 'html' ? 1 : 0;

				$state = 0;

				if (empty($mailq->recipient)) {
					$state = 1;
				}

				$body = $mailq->body;
				// check if we need to get the content from template file or not.
				if ($mailq->template && !$mailq->body) {
					$body = $mailq->processTemplateContent();
				}

				$mail = JFactory::getMailer();
				$state = $mail->sendMail($mailq->mailfrom, $mailq->fromname, $mailq->recipient, $mailq->subject, $body, $sendHTML);

				if ($state) {
					$mailq->status = 1;
					$mailq->store();
				}
			}
		}
	}

	/**
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function addMailq($component, $cid)
	{
		$config = KT::getConfig();

		$subscribers = KT::getModel('subscription')->getSubscribers($component, $cid);

		$mainframe = JFactory::getApplication();
		$mailfrom = $mainframe->getCfg('mailfrom');
		$fromname = $mainframe->getCfg('fromname');

		if ($config->get('admin_email')) {
			// get from config if config exist
		}

		if (count($subscribers) > 0) {
			foreach ($subscribers as $subscriber) {
				$data = array(
					'mailfrom'	=> $mailfrom,
					'fromname'	=> $fromname,
					'recipient'	=> $subscriber->email,
					'subject'	=> 'new email',
					'body'		=> 'body',
					'created'	=> KT::date()->toMySQL(),
					'status'	=> 0
				);

				$mailqTable = KT::getTable('mailq');

				$mailqTable->bind($data);
				$mailqTable->store();
			}

			return true;
		}

		return false;
	}
}
