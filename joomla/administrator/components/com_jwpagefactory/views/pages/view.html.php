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

class JwpagefactoryViewPages extends JViewLegacy
{

	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');

		//Joomla Component Helper
		jimport('joomla.application.component.helper');
		$this->params = JComponentHelper::getParams('com_jwpagefactory');

		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
		}

		$this->addToolbar();
		JwpagefactoryHelper::addSubmenu('pages');
		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	protected function addToolBar()
	{
		$state = $this->get('State');
		$canDo = JHelperContent::getActions('com_jwpagefactory');
		$user = JFactory::getUser();
		$bar = JToolBar::getInstance('toolbar');

		JToolBarHelper::title(JText::_('COM_JWPAGEFACTORY') . ' - ' . JText::_('COM_JWPAGEFACTORY_PAGES'), 'fa fa fa-list-ul');

		if ($canDo->get('core.create')) {
			JToolBarHelper::preferences('com_jwpagefactory');
		}

		// new page button
		if ($canDo->get('core.admin') || $canDo->get('core.create')) {
			JToolbarHelper::addNew('page.add');
		}

		// edit button
		if ($canDo->get('core.edit')) {
			JToolbarHelper::editList('page.edit');
		}

		// publish and unpublish button
		if ($canDo->get('core.edit.state')) {
			JToolbarHelper::publish('pages.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('pages.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::checkin('pages.checkin');
		}

		// delete and trush button
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			JToolbarHelper::deleteList('', 'pages.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state') && $canDo->get('core.delete')) {
			JToolbarHelper::trash('pages.trash');
		}

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_published',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false)), 'value', 'text', $this->state->get('filter.published'), true)
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
			JHtml::_('select.options', JHtml::_('category.options', 'com_jwpagefactory'), 'value', 'text', $this->state->get('filter.category_id'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);
	}

	protected function getSortFields()
	{
		return array(
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.title' => JText::_('JGLOBAL_TITLE'),
			'a.access' => JText::_('JGRID_HEADING_ACCESS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
