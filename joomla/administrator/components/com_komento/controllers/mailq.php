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

KT::import('admin:/controllers/controller');

class KomentoControllerMailQ extends KomentoController
{
	/**
	 * Factory method to register the task
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		// Register task aliases here.
		$this->registerTask('publish', 'togglePublish');
		$this->registerTask('unpublish', 'togglePublish');

		// Task aliases for purging items
		$this->registerTask('purgeSent', 'purge');
		$this->registerTask('purgePending', 'purge');
		$this->registerTask('purgeAll', 'purge');

		// Task aliases for saving new item.
		$this->registerTask('apply', 'store');
		$this->registerTask('save', 'store');
		$this->registerTask('save2new', 'store');
	}

	/**
	 * Invoke purge method
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function purge()
	{
		KT::checkToken();

		$task = $this->getTask();

		$model = KT::model('Mailq');
		$state = $model->$task();

		if (!$state) {

			switch ($task)
			{
				case 'purgePending':
					$message = JText::_('COM_KOMENTO_ERRORS_MAILER_PURGE_PENDING');
				break;
				case 'purgeSent':
					$message = JText::_('COM_KOMENTO_ERRORS_MAILER_PURGE_SENT');
				break;
				case 'purgeAll':
				default:
					$message = JText::_('COM_KOMENTO_ERRORS_MAILER_PURGE_ALL');
				break;
			}

			$this->info->set($message, 'error');
			return $this->app->redirect('index.php?option=com_komento&view=mailq');
		}

		switch ($task)
		{
			case 'purgePending':
				$message = JText::_('COM_KOMENTO_MAILER_PENDING_ITEMS_PURGED_SUCCESSFULLY');
			break;
			case 'purgeSent':
				$message = JText::_('COM_KOMENTO_MAILER_SENT_ITEMS_PURGED_SUCCESSFULLY');
			break;
			case 'purgeAll':
			default:
				$message = JText::_('COM_KOMENTO_MAILER_ALL_ITEMS_PURGED_SUCCESSFULLY');
			break;
		}

		$this->info->set($message, 'success');
		return $this->app->redirect('index.php?option=com_komento&view=mailq');
	}

	/**
	 * Invoke toggle publish
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function togglePublish()
	{
		KT::checkToken();

		$method = $this->getTask();

		$ids = JRequest::getVar('cid');
		$ids = KT::makeArray($ids);

		// Test if there's any id's being passed in.
		if (empty($ids)) {
			$this->info->set('COM_KOMENTO_ERRORS_MAILER_NO_ID', 'error');
			return $this->app->redirect('index.php?option=com_komento&view=mailq');
		}

		$stateValue = $method == 'publish' ? 1 : 0;

		foreach ($ids as $id) {
			$mailer = KT::table('Mailq');
			$mailer->load($id);
			$mailer->status = $stateValue;
			$mailer->store();
		}

		$message = $method == 'publish' ? JText::_('COM_KOMENTO_MAILER_ITEMS_MARKED_AS_SENT') : JText::_('COM_KOMENTO_MAILER_ITEMS_MARKED_AS_PENDING');
		
		$this->info->set($message, 'success');
		return $this->app->redirect('index.php?option=com_komento&view=mailq');
	}

	/**
	 * Saves an email template
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function saveFile()
	{
		KT::checkToken();
		
		$contents = $this->input->get('source', '', 'raw');

		$file = $this->input->get('file', '', 'default');
		$file = base64_decode($file);

		// Get the overriden path
		$model = KT::model('Emails');
		$path = $model->getOverrideFolder($file);

		$model->write($path, $contents);

		$this->info->set('COM_KOMENTO_EMAILS_TEMPLATE_FILE_SAVED_SUCCESSFULLY', 'success');
		$this->app->redirect('index.php?option=com_komento&view=mailq&layout=editor');
	}

	/**
	 * Reset a list of emails template files to it's original condition
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function reset()
	{
		KT::checkToken();

		$files = $this->input->get('file', array(), 'default');
		$files = KT::makeArray($files);

		if (!$files) {
			$this->info->set('COM_KOMENTO_EMAIL_INVALID_ID_PROVIDED', 'error');
			$this->app->redirect('index.php?option=com_komento&view=mailq&layout=editor');
		}

		$model = KT::model('Emails');

		foreach ($files as $file) {
			$file = base64_decode($file);
			$overridePath = $model->getOverrideFolder($file);

			JFile::delete($overridePath);
		}

		$this->info->set('COM_KOMENTO_EMAIL_DELETED_SUCCESSFULLY', 'success');
		$this->app->redirect('index.php?option=com_komento&view=mailq&layout=editor');
	}
}