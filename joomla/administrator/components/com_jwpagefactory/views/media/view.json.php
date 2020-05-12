<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
//no direct accees
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.view');

require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/helpers/language.php';

class JwpagefactoryViewMedia extends JViewLegacy
{
	public function display($tpl = null)
	{

		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$authorised = $user->authorise('core.edit', 'com_jwpagefactory') || $user->authorise('core.edit.own', 'com_jwpagefactory');
		if ($authorised !== true) {
			$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return false;
		}

		$input = JFactory::getApplication()->input;
		$layout = $input->get('layout', 'browse', 'STRING');
		$this->date = $input->post->get('date', NULL, 'STRING');
		$this->start = $input->post->get('start', 0, 'INT');
		$this->search = $input->post->get('search', NULL, 'STRING');
		$this->limit = 18;

		$model = $this->getModel();

		if (($layout == 'browse') || ($layout == 'modal')) {
			$this->items = $model->getItems();
			$this->filters = $model->getDateFilters($this->date, $this->search);
			$this->total = $model->getTotalMedia($this->date, $this->search);
			$this->categories = $model->getMediaCategories();
		} else {
			$this->media = $model->getFolders();
		}

		parent::display($tpl);
	}
}
