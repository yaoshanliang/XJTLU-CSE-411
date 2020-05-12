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

require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/helpers/language.php';

jimport('joomla.application.component.view');

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
		$this->items = $model->getItems();
		$this->filters = $model->getDateFilters($this->date, $this->search);
		$this->total = $model->getTotalMedia($this->date, $this->search);
		$this->categories = $model->getMediaCategories();

		$this->addToolbar();

		$mediaParams = JComponentHelper::getParams('com_media');
		JFactory::getDocument()->addScriptdeclaration('var jwpfMediaPath=\'/' . $mediaParams->get('file_path', 'images') . '\';');

		JwpagefactoryHelper::addSubmenu('media');
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_JWPAGEFACTORY') . ' - ' . JText::_('COM_JWPAGEFACTORY_MEDIA'), 'fa fa fa-picture-o');
	}
}
