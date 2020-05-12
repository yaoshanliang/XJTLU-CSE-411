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

class KomentoThemesGrid
{
	/**
	 * Renders a checkbox for each table row
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function id($number, $id , $allowed = true , $checkedOut = false , $name = 'cid')
	{
		$theme = KT::themes();
		$theme->set('allowed', $allowed);
		$theme->set('number', $number);
		$theme->set('name', $name);
		$theme->set('checkedOut', $checkedOut);
		$theme->set('id', $id);

		$contents = $theme->output('admin/helpers/grid/id');

		return $contents;
	}


	/**
	 * Renders publish / unpublish icon
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function published($obj, $controllerName = '' , $key = '' , $tasks = array() , $tooltips = array(), $classes = array(), $allowed = true)
	{
		// If primary key is not provided, then we assume that we should use 'state' as the key property.
		$key = !empty($key) ? $key : 'state';

		// array_replace is only supported php>5.3
		// While array_replace goes by base, replacement
		// Using + changes the order where base always goes last

		$classes += array(
							-1 => 'trash',
							0 => 'unpublish',
							1 => 'publish',
							2 => 'pending'
						);
		
		$tasks += array(
						-1 => 'publish',
						0 => 'publish',
						1 => 'unpublish',
						2 => 'publish'
					);

		$tooltips += array(
							-1 => 'COM_EASYSOCIAL_GRID_TOOLTIP_TRASHED_ITEM',
							0 => 'COM_EASYSOCIAL_GRID_TOOLTIP_PUBLISH_ITEM',
							1 => 'COM_EASYSOCIAL_GRID_TOOLTIP_UNPUBLISH_ITEM',
							2 => 'COM_EASYSOCIAL_GRID_TOOLTIP_PUBLISH_ITEM'
						);

		$class = isset($classes[$obj->$key]) ? $classes[$obj->$key] : '';
		$task = isset($tasks[$obj->$key]) ? $tasks[$obj->$key] : '';
		$tooltip = isset($tooltips[$obj->$key]) ? JText::_($tooltips[$obj->$key]) : '';

		$theme = KT::themes();

		$theme->set('allowed', $allowed);
		$theme->set('tooltip', $tooltip);
		$theme->set('task', $task);
		$theme->set('class', $class);

		return $theme->output('admin/helpers/grid/published');
	}


	/**
	 * Renders feature / unfeature icon.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function featured($obj , $controllerName = '' , $key = '' , $task = '' , $allowed = true , $tooltip = array())
	{
		// If primary key is not provided, then we assume that we should use 'state' as the key property.
		$key = !empty($key) ? $key : 'default';
		$task = !empty($task) ? $task : 'toggleDefault';

		// Default is unfeatured
		$class = 'default';
		$tooltip = isset($tooltip[0]) ? $tooltip[0] : JText::_('COM_EASYSOCIAL_GRID_TOOLTIP_FEATURE_ITEM', true);

		if ($obj->$key == 1) {
			$class = 'featured';
			$tooltip = '';

			if ($allowed) {
				$tooltip = isset($tooltip[1]) ? $tooltip[1] : JText::_('COM_EASYSOCIAL_GRID_TOOLTIP_UNFEATURE_ITEM', true);
			}
		}

		$theme = KT::themes();
		$theme->set('task', $task);
		$theme->set('class', $class);
		$theme->set('tooltip', $tooltip);
		$theme->set('allowed', $allowed);

		return $theme->output('admin/helpers/grid/published');
	}

	public static function selectlist($name, $selected, $arr, $id = '', $attributes = '', $key = 'value', $text = 'text')
	{
		$options = array();

		// array should only contain option list.
		unset($arr['attributes']);


		if (count($arr) > 0) {

			foreach ($arr as $element) {

				if (is_array($element)) {
					$val = (isset($element[$key])) ? $element[$key] : '';
					$txt = (isset($element[$text])) ? $element[$text] : '';
				} else {
					$val = $element->$key;
					$txt = $element->$text;
				}

				// ensure ampersands are encoded
				$val = JFilterOutput::ampReplace($val);
				$txt = JFilterOutput::ampReplace($txt);

				$options[$val] = JText::_($txt);
			}
		}

		// Bind classes
		$class = 'o-form-control';

		if (is_array($attributes) && isset($attributes['class'])) {
			$class .= ' ' . $attributes['class'];

			unset($attributes['class']);
		}

		$isMultiple = false;

		if (is_array($attributes)) {
			if(isset($attributes['multiple']))
			{
				$isMultiple = true;
				unset($attributes['multiple']);
			}

			$attributes	= implode(' ', $attributes);
		}

		$theme = KT::template();
		$theme->set('class', $class);
		$theme->set('options', $options);
		$theme->set('selected', $selected);
		$theme->set('name', $name);
		$theme->set('id', $id);
		$theme->set('attributes', $attributes);
		$theme->set('ismultiple', $isMultiple);

		return $theme->output('admin/helpers/grid/selectlist');
	}

	public static function inputbox($name, $value = '', $id = '', $attributes = '')
	{
		$defaultClass = 'o-form-control';

		if (is_array($attributes) && isset($attributes['class'])) {
			$defaultClass = 'o-form-control ' . $attributes['class'];

			unset($attributes['class']);
		}

		if (is_array($attributes)) {
			$attributes = implode(' ', $attributes);
		}

		// If value is an array, implode it with a comma as a separator
		if (is_array($value)) {
			$value 	= implode(',', $value);
		}

		$theme = KT::template();
		$theme->set('defaultClass', $defaultClass);
		$theme->set('name', $name);
		$theme->set('value', $value);
		$theme->set('id', $id);
		$theme->set('attributes', $attributes);

		$output = $theme->output('admin/helpers/grid/inputbox');

		return $output;
	}

	public static function textarea($name, $value = '', $id = '', $attributes = '')
	{
		if (is_array($attributes)) {
			$attributes	= implode(' ', $attributes);
		}

		// If value is an array, implode it with a comma as a separator
		if (is_array($value)) {
			$value 	= implode(',', $value);
		}

		$theme = KT::template();
		$theme->set('name', $name);
		$theme->set('value', $value);
		$theme->set('id', $id);
		$theme->set('attributes', $attributes);

		return $theme->output('admin/helpers/grid/textarea');
	}

	public static function multilist($configName, $selected = '', $options, $attributes = '')
	{
		if (empty($selected)) {
			$selected = array();
		}

		if (!is_array($selected)) {
			$selected = explode(',', $selected);
		}

		if (is_array($attributes)) {
			$attributes	= implode(' ', $attributes);
		}

		$key = $configName . '[]';

		KomentoThemesGrid::makeListOptions($options);

		return JHTML::_('select.genericlist', $options, $key, 'multiple="multiple" class="o-form-control" size="10" style="height: auto !important;" ' . $attributes, 'value', 'text', $selected);
	}

	public static function makeListOptions(&$options = array())
	{
		if (!is_array($options)) {
			$options = array();
		}

		foreach ($options as &$option) {
			// convert array to object
			if (is_array($option)) {
				$tmp = new stdClass();
				$tmp->id = $option[0];
				$tmp->title = $option[1];
				$option = $tmp;
			}

			if (isset($option->value) && !isset($option->id)) {
				$option->id = $option->value;
			}

			if (isset($option->text) && !isset($option->title)) {
				$option->title = $option->text;
			}

			// if it is a tree item, then treename always take effect
			if (isset($option->treename)) {
				$option->title = $option->treename;
			}
			$option = JHtml::_('select.option', $option->id, JText::_($option->title));
		}
	}

	/**
	 * Renders the ordering in a grid
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function sort($column, $text, $currentOrdering, $direction)
	{
		$text = JText::_($text);
		$theme = KT::template();

		// Ensure that the direction is always in lowercase because we will check for it in the theme file.
		$direction = JString::strtolower($direction);
		$currentOrdering = JString::strtolower($currentOrdering);
		$column = JString::strtolower($column);

		$theme->set('column', $column);
		$theme->set('text', $text);
		$theme->set('currentOrdering', $currentOrdering);
		$theme->set('direction', $direction);

		$contents = $theme->output('admin/helpers/grid/sort');

		return $contents;
	}	
}
