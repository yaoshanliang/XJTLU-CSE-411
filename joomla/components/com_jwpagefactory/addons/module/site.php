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

class JwpagefactoryAddonModule extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;

		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		//Options
		$id = (isset($settings->id) && $settings->id) ? $settings->id : 0;
		$module_type = (isset($settings->module_type) && $settings->module_type) ? $settings->module_type : 'module';
		$position = (isset($settings->position) && $settings->position) ? $settings->position : '';

		if ((($module_type == 'position') && !$position) || (($module_type == 'module') && !$id)) {
			return;
		}

		$modules = self::getModules($module_type, $id, $position);

		if (count((array)$modules)) {

			$output = '<div class="jwpf-addon jwpf-addon-module ' . $class . '">';
			$output .= '<div class="jwpf-addon-content">';
			$output .= ($title) ? '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';

			foreach ($modules as $module) {
				$file = $module->module;
				$custom = substr($file, 0, 4) == 'mod_' ? 0 : 1;
				$module->user = $custom;
				$module->name = $custom ? $module->title : substr($file, 4);
				$module->style = null;
				$module->position = strtolower($module->position);
				$clean[$module->id] = $module;

				if ($module_type == 'position') {
					$output .= JModuleHelper::renderModule($module, array('style' => 'jw_xhtml'));
				} else {
					$output .= JModuleHelper::renderModule($module, array('style' => 'none'));
				}

			}

			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}

		return null;
	}

	// Get all modules
	private static function getModules($module_type = 'module', $id = 0, $position = '')
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$lang = JFactory::getLanguage()->getTag();
		$clientId = (int)$app->getClientId();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('m.id, m.title, m.module, m.position, m.ordering, m.content, m.showtitle, m.params');
		$query->from('#__modules AS m');
		$query->where('m.published = 1');

		if ($module_type == 'position') {
			$query->where($db->quoteName('m.position') . ' = ' . $db->quote($position));
			$query->order('m.ordering ASC');
		} else {
			$query->where('m.id = ' . $id);
		}

		$date = JFactory::getDate();
		$now = $date->toSql();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = ' . $db->Quote($nullDate) . ' OR m.publish_up <= ' . $db->Quote($now) . ')');
		$query->where('(m.publish_down = ' . $db->Quote($nullDate) . ' OR m.publish_down >= ' . $db->Quote($now) . ')');

		$query->where('m.access IN (' . $groups . ')');
		$query->where('m.client_id = ' . $clientId);

		// Filter by language
		if ($app->isSite() && $app->getLanguageFilter()) {
			$query->where('m.language IN (' . $db->Quote($lang) . ',' . $db->Quote('*') . ')');
		}

		// Set the query
		$db->setQuery($query);
		return $db->loadObjectList();
	}

}
