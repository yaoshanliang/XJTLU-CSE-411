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
defined('_JEXEC') or die ('restricted access');

class ModJPagefactoryHelper
{
	public static function getData($id, $params)
	{
		$data = self::pageFactoryData($id);

		if (isset($data->text) && $data->text) {
			return $data->text;
		} else {
			$content = $params->get('content', '[]');
			if (!self::isJson($content)) {
				$content = '[]';
			}
		}

		return $content;
	}

	private static function pageFactoryData($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__jwpagefactory'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote('mod_jwpagefactory'));
		$query->where($db->quoteName('extension_view') . ' = ' . $db->quote('module'));
		$query->where($db->quoteName('view_id') . ' = ' . $db->quote($id));
		$db->setQuery($query);
		$item = $db->loadObject();

		return $item;
	}

	private static function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}
