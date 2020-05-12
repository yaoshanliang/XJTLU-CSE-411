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

class KomentoViewMigrators extends KomentoAdminView
{
	public function display($tpl = null)
	{
		// Check for access
		$this->checkAccess('komento.manage.migrators');

		$this->heading('COM_KOMENTO_HEADING_MIGRATORS');

		$layout = $this->getLayout();

		$htmlContent = '';

		if ($layout != 'default') {
			$htmlContent = $this->getContent($layout);
		}

		$this->set('htmlContent', $htmlContent);

		$componentMigrators = array('easyblog', 
								   'zoo',
								   'k2',
								   'slicomments',
								   'jcomments',
								   'jacomment',
								   'rscomments');

		$this->set('componentLib', KT::components());
		$this->set('componentMigrators', $componentMigrators);

		parent::display('migrators/default');
	}

	public function getContent($layout)
	{
		$this->heading('COM_KOMENTO_MIGRATORS_' . strtoupper($layout));

		$theme = KT::themes();
		$categories = '';

		// Some layouts, we don't need categories selection
		$excludeLayouts = array('jcomments', 'jacomment', 'rscomments');
		
		if ($layout != 'custom' && !in_array($layout, $excludeLayouts)) {

			// Slicomment has a com_content categories selection
			if ($layout == 'slicomments') {
				$componentObj = KT::loadApplication('com_content');
			} else {
				$componentObj = KT::loadApplication('com_' . $layout);
			}

			$categories = $componentObj->getCategories();
			
			if ($categories) {
				$categories = $this->getCategoriesDropdown($categories);
			}
		}

		// prepare table selection for custom migrator
		$tables = KT::db()->getTables();

		$tableSelection = array();

		foreach ($tables as $table) {
			$tableSelection[] = JHtml::_('select.option', $table, $table);
		}

		$components = KT::components()->getAvailableComponents();
		$componentSelection = array();

		foreach ($components as $component) {
			$componentSelection[] = JHtml::_('select.option', $component, $component);
		}
		
		$theme->set('tableSelection', $tableSelection);
		$theme->set('componentSelection', $componentSelection);
		$theme->set('categories', $categories);
		$theme->set('publishingState', $this->getPublishingState());
		$output = $theme->output('admin/migrators/adapters/' . $layout);

		return $output;
	}

	public function getCategoriesDropdown($categories)
	{
		$categoriesDropdown = array();
		$categoriesDropdown[] = JHtml::_('select.option', 'all', JText::_('COM_KOMENTO_MIGRATORS_CATEGORIES_DROPDOWN_ALL'));

		foreach ($categories as $category) {
			$categoriesDropdown[] = JHtml::_('select.option', $category->id, $category->name);
		}
		
		return $categoriesDropdown;
	}

	public function getPublishingState()
	{
		$publishingState = array();
		$publishingState[] = JHtml::_('select.option', 'inherit', JText::_('COM_KOMENTO_MIGRATORS_PUBLISHING_STATE_INHERIT'));
		$publishingState[] = JHtml::_('select.option', '1', JText::_('COM_KOMENTO_MIGRATORS_PUBLISHING_STATE_PUBLISHED'));
		$publishingState[] = JHtml::_('select.option', '0', JText::_('COM_KOMENTO_MIGRATORS_PUBLISHING_STATE_UNPUBLISHED'));

		return $publishingState;
	}


}
