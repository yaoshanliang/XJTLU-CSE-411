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

class KomentoViewMailq extends KomentoAdminView
{
	/**
	 * Renders the mailer queue from the system
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function display($tpl = null)
	{
		$this->checkAccess('komento.manage.mailq');

		// Set page heading
		$this->heading('COM_KOMENTO_HEADING_EMAIL_ACTIVITIES', 'COM_KOMENTO_DESCRIPTION_EMAIL_ACTIVITIES');

		// Add buttons
		JToolbarHelper::publishList('publish', JText::_('COM_KOMENTO_TOOLBAR_TITLE_BUTTON_MARK_SENT'));
		JToolbarHelper::unpublishList('unpublish', JText::_('COM_KOMENTO_TOOLBAR_TITLE_BUTTON_MARK_PENDING'));
		JToolbarHelper::divider();
		JToolbarHelper::trash('purgeSent', JText::_('COM_KOMENTO_TOOLBAR_TITLE_BUTTON_PURGE_SENT'), false);
		JToolbarHelper::trash('purgePending', JText::_('COM_KOMENTO_TOOLBAR_TITLE_BUTTON_PURGE_PENDING'), false);
		JToolbarHelper::trash('purgeAll', JText::_('COM_KOMENTO_TOOLBAR_TITLE_BUTTON_PURGE_ALL'), false);

		// Get the model
		$model = KT::model('Mailq', array('initState' => true));

		$emails = $model->getItemsWithState();
		$pagination = $model->getPagination();
		$state = $model->getState('filter_publish');
		$search = $model->getState('search');
		$limit = $model->getState('limit');
		$ordering = $model->getState('ordering');
		$direction = $model->getState('direction');

		if ($state != 'all') {
			$state = (int) $state;
		}

		$this->set('ordering', $ordering);
		$this->set('direction', $direction);
		$this->set('search', $search);
		$this->set('limit', $limit);
		$this->set('published', $state);
		$this->set('emails', $emails);
		$this->set('pagination', $pagination);

		parent::display('mailq/default');
	}

	/**
	 * Previews an email
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function preview()
	{
		$id = $this->input->get('id', 0, 'int');

		$mailer = KT::table('Mailq');
		$mailer->load($id);

		// Load front end language file
		KT::loadLanguage(JPATH_ROOT);

		if ($mailer->template && !$mailer->body) {
			$mailer->body = $mailer->processTemplateContent();
		}

		echo $mailer->body;
		exit;
	}

	public function registerToolbar()
	{
		JToolBarHelper::title(JText::_('COM_KOMENTO_MAIL_QUEUE'), 'mailq');

		JToolBarHelper::back(JText::_('COM_KOMENTO_ADMIN_HOME'), 'index.php?option=com_komento');
		JToolBarHelper::divider();
		JToolBarHelper::custom('purge','purge','icon-32-unpublish.png', 'COM_KOMENTO_PURGE_ITEMS', false);
	}

	/**
	 * Renders the list of e-mail templates
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function editor()
	{
		$this->checkAccess('komento.manage.emails');

		$this->heading('COM_KOMENTO_HEADING_EMAIL_TEMPLATES');

		JToolbarHelper::deleteList('', 'reset', JText::_('COM_KOMENTO_EMAILS_RESET_DEFAULT'));

		$model = KT::model('Emails');
		$files = $model->getFiles();

		$this->set('files', $files);

		return parent::display('mailq/editor/default');
	}

	/**
	 * Renders the editor for email template
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function editFile()
	{
		$this->checkAccess('komento.manage.emails');

		JToolBarHelper::title(JText::_('COM_KOMENTO_EMAILS_EDITING_TITLE'), 'emails');

		JToolBarHelper::apply('saveFile');
		JToolBarHelper::cancel();

		$this->heading('COM_KOMENTO_EMAILS_EDITING_TITLE', 'COM_KOMENTO_EMAILS_EDITING_TITLE_DESC');

		$file = $this->input->get('file', '', 'default');
		$file = urldecode($file);

		$model = KT::model("Emails");
		$absolutePath = $model->getFolder() . $file;

		$file = $model->getTemplate($absolutePath, true);

		// Always use codemirror
		$editor = JFactory::getEditor('codemirror');

		$this->set('editor', $editor);
		$this->set('file', $file);

		return parent::display('mailq/editfile/default');
	}
}
