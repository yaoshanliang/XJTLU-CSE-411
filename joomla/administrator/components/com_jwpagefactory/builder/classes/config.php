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
defined ('_JEXEC') or die ('Restricted access');

class JwAddonsConfig {

	public static $addons = array();

	private static function str_replace_first($from, $to, $subject) {
		$from = '/'.preg_quote($from, '/').'/';
		return preg_replace($from, $to, $subject, 1);
	}

	public static function addonConfig( $attributes ) {
		if (empty($attributes['addon_name']) || empty($attributes) || empty($attributes['attr']) ) {
			return false;
		} else {
			$addon = self::str_replace_first('jw_', '', $attributes['addon_name']);

			$app = JFactory::getApplication();
			$com_option = $app->input->get('option','','STR');
			$com_view = $app->input->get('view','','STR');
			$com_id = $app->input->get('id',0,'INT');

			if($app->isAdmin() || ( $com_option == 'com_jwpagefactory' && $com_view == 'form' && $com_id)){
				if (!isset($attributes['icon']) || !$attributes['icon']) {
					$attributes['icon'] = self::getIcon($addon);
				}
			}

			if(is_array($attributes['attr'])) {
				if(!isset($attributes['attr']['general'])) {
					foreach ($attributes['attr'] as $key => $attr) {
						if(isset($attributes['attr'][$key]) && $attributes['attr'][$key]) {
							unset($attributes['attr'][$key]);
						}
						$attributes['attr']['general'][$key] = $attr;
					}
				}
			}

			self::$addons[$addon] = $attributes;
		}
	}

	public static function getIcon( $addon ) {

		$template_name = self::getTemplateName();
		$template_path = JPATH_ROOT . '/templates/' . $template_name . '/jwpagefactory/addons/' . $addon . '/assets/images/icon.png';
		$com_file_path = JPATH_ROOT . '/components/com_jwpagefactory/addons/' . $addon . '/assets/images/icon.png';

		if ( file_exists($template_path) ) {
			$icon = JURI::root(true) . '/templates/' . $template_name . '/jwpagefactory/addons/' . $addon . '/assets/images/icon.png';
		} else if ( file_exists($com_file_path) ) {
			$icon = JURI::root(true) . '/components/com_jwpagefactory/addons/' . $addon . '/assets/images/icon.png';
		} else {
			$icon = JURI::root(true) . '/administrator/components/com_jwpagefactory/assets/img/addon-default.png';
		}

		return $icon;

	}

	private static function getTemplateName() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('template')));
		$query->from($db->quoteName('#__template_styles'));
		$query->where($db->quoteName('client_id') . ' = 0');
		$query->where($db->quoteName('home') . ' = 1');
		$db->setQuery($query);

		return $db->loadObject()->template;
	}
}
