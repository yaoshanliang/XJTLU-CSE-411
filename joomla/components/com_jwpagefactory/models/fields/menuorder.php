<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldMenuOrder extends JFormFieldList
{
	protected $type = 'MenuOrder';

	protected function getOptions()
	{
		$options = array();

		// Get the parent
		$parent_id = $this->form->getValue('menuparent_id', 0);

		if (empty($parent_id))
		{
			return false;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.title AS text, a.client_id AS ' . $db->quoteName('clientId'))
			->from('#__menu AS a')

			->where('a.published >= 0')
			->where('a.parent_id =' . (int) $parent_id);

		if ($menuType = $this->form->getValue('menutype'))
		{
			$query->where('a.menutype = ' . $db->quote($menuType));
		}
		else
		{
			$query->where('a.menutype != ' . $db->quote(''));
		}

		$query->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		// Allow translation of custom admin menus
		foreach ($options as &$option)
		{
			if ($option->clientId != 0)
			{
				$option->text = JText::_($option->text);
			}
		}

		$options = array_merge(
			array(array('value' => '-1', 'text' => JText::_('COM_JWPAGEFACTORY_ITEM_FIELD_ORDERING_VALUE_FIRST'))),
			$options,
			array(array('value' => '-2', 'text' => JText::_('COM_JWPAGEFACTORY_ITEM_FIELD_ORDERING_VALUE_LAST')))
		);

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	protected function getInput()
	{
		if ($this->form->getValue('id', 0) == 0)
		{
			return '<span class="readonly">' . JText::_('COM_JWPAGEFACTORY_ITEM_FIELD_ORDERING_TEXT') . '</span>';
		}
		else
		{
			return parent::getInput();
		}
	}
}
