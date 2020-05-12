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

class JwpagefactoryViewIntegrations extends JViewLegacy
{

	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->items = $this->get('Items');

		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
		}

		$this->addToolbar();
		JwpagefactoryHelper::addSubmenu('integrations');
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_JWPAGEFACTORY') . ' - ' . JText::_('COM_JWPAGEFACTORY_INTEGRATIONS'), 'fa fa fa-plug');
	}

}
