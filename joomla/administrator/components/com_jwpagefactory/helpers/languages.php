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

class JwpagefactoryHelperLanguages
{

	public static function language_list()
	{
		$cParams = JComponentHelper::getParams('com_jwpagefactory');
		$version = JwpagefactoryHelper::getVersion();
		$lang = JFactory::getLanguage()->getTag();
		$domain = isset($_SERVER['HTTP_HOST']) ? urlencode($_SERVER['HTTP_HOST']) : '';
		$veriargs = '&email=' . $cParams->get('joomworker_email') . '&api_key=' . $cParams->get('joomworker_license_key') . '&api_host=' . $domain . '&version=' . $version . '&language=' . $lang;

		$language_api = API_SITE . '/index.php?option=com_pagefactoryservice&task=api.language' . $veriargs;

		if (ini_get('allow_url_fopen')) {
			$components = json_decode(file_get_contents($language_api));
		} else if (extension_loaded('curl')) {
			$components = json_decode(self::getCurlData($language_api));
		} else {
			$report['message'] = JText::_('Please enable \'cURL\' or url_fopen in PHP or Contact with your Server or Hosting administrator.');
			die(json_encode($report));
		}

		$languages = new stdClass;
		foreach ($components as $key => $component) {
			$languages->$key = $component;
		}

		return $languages;
	}

	private static function getCurlData($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

}
