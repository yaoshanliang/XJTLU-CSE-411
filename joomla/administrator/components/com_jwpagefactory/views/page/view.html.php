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

// import Joomla view library
jimport('joomla.application.component.view');

class JwpagefactoryViewPage extends JViewLegacy
{
	public function display($tpl = null)
	{
		$form = $this->get('Form');
		$item = $this->get('Item');

		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		$this->form = $form;
		$this->item = $item;

		//Load Language
		$db = JFactory::getDbo();
		$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
		$db->setQuery($query);
		$defaultemplate = $db->loadResult();

		$lang = JFactory::getLanguage();
		$lang->load('tpl_' . $defaultemplate, JPATH_SITE, $lang->getTag(), true);

		$this->addToolBar();
		parent::display($tpl);
	}

	protected function addToolBar()
	{
		$input = JFactory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);

		$title = JText::_('COM_JWPAGEFACTORY') . ' - ' . ($isNew ? JText::_('COM_JWPAGEFACTORY_NEW_PAGE') : JText::_('COM_JWPAGEFACTORY_EDIT_PAGE'));
		JToolBarHelper::title($title);
		JToolBarHelper::apply('page.apply');
		JToolBarHelper::save('page.save');

		if (!$isNew) {
			JToolbarHelper::save2copy('page.save2copy');
		}

		JToolBarHelper::cancel('page.cancel', $isNew ? 'Cancel' : 'Close');
	}
}