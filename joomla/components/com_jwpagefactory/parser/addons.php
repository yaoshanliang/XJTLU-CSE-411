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

jimport('joomla.filesystem.file');

abstract class JwpagefactoryAddons
{

	public function __construct($addon)
	{

		if (!$addon) {
			return false;
		}

		$this->addon = $addon;
	}

	/**
	 * Check placeholder file path for each media image
	 */
	protected function get_image_placeholder($src)
	{
		$config = JComponentHelper::getParams('com_jwpagefactory');
		$lazyload = $config->get('lazyloadimg', '0');

		if ($lazyload) {
			$filename = basename($src);
			$mediaPath = 'media/com_jwpagefactory/placeholder';
			$basePath = JPATH_ROOT . '/' . $mediaPath . '/' . $filename;
			if (JFile::exists($basePath)) {
				return $mediaPath . '/' . $filename;
			} else {
				return $config->get('lazyplaceholder', '');
			}
		}
		return false;
	}

	/**
	 * Get any valid image dimension
	 * @return Array
	 */
	protected function get_image_dimension($src)
	{
		preg_match('/\__(.*?)\./', $src, $match);
		if (count($match) > 1) {
			$dimension = explode('x', $match[1]);
			return ['width="' . $dimension[0] . '"', 'height="' . $dimension[1] . '"'];
		}
		return [];
	}

}
