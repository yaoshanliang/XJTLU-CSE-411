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

abstract class requestHelper
{

	/**
	 * Get by curl
	 * @param string $url
	 * @param array $params
	 * @return string content
	 */
	public static function http_get($url, $params = array())
	{
		$curl = curl_init();
		if (stripos($url, 'https://') !== false) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
		// ignore result
		if (isset($params['ignore_result']) && $params['ignore_result']) {
			curl_setopt($curl, CURLOPT_TIMEOUT, 1);
		}
		if ($strGET = http_build_query($params)) {
			$url .= (preg_match('/[?]/', $url) ? '&' : '?') . $strGET;
		}
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$sContent = curl_exec($curl);
		$aStatus = curl_getinfo($curl);
		curl_close($curl);
		if (intval($aStatus['http_code']) == 200) {
			if (is_string($sContent)) {
				$json = json_decode($sContent, true);
				if ($json) {
					return $json;
				} else {
					return $sContent;
				}
			}
			return $sContent;
		} else {
			return false;
		}
	}

	/**
	 * Post by curl
	 * @param string $url
	 * @param array $params
	 * @param boolean $post_file
	 * @return string content
	 */
	public static function http_post($url, $params = array(), $post_file = false)
	{
		$curl = curl_init();
		if (stripos($url, 'https://') !== false) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
		// ignore result
		if (isset($params['ignore_result']) && $params['ignore_result']) {
			curl_setopt($curl, CURLOPT_TIMEOUT, 1);
		}
		if (PHP_VERSION_ID >= 50500 && class_exists('\CURLFile')) {
			$is_curlFile = true;
		} else {
			$is_curlFile = false;
			if (defined('CURLOPT_SAFE_UPLOAD')) {
				curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
			}
		}
		if ($post_file && $is_curlFile) {
			foreach ($params as $key => $val) {
				if (is_string($val) && substr($val, 0, 1) == '@') {
					$params[$key] = new \CURLFile(realpath(substr($val, 1)));
				}
			}
		} else {
			$params = json_encode($params, JSON_UNESCAPED_UNICODE);
		}
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		$sContent = curl_exec($curl);
		$aStatus = curl_getinfo($curl);
		curl_close($curl);
		if (intval($aStatus['http_code']) == 200) {
			if (is_string($sContent)) {
				$json = json_decode($sContent, true);
				if ($json) {
					return $json;
				} else {
					return $sContent;
				}
			}

			return $sContent;
		} else {
			return false;
		}
	}

}