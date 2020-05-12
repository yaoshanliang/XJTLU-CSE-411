<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class KomentoGitHub extends KomentoBase
{
	/**
	 * Creates a new gist anonymously
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function createGist($contents, $language)
	{
		$files = array('snippet.' . $language => array('content' => $contents));
		$params = array('files' => $files, 'description' => JText::sprintf('COM_KOMENTO_GIST_SNIPPET_SHARED_FROM', JURI::root()), 'public' => true);
		$paramsStr = json_encode($params);

		// Set a due date?
		$ch = curl_init('https://api.github.com/gists');

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $paramsStr);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Accept: application/json',
			'Content-Type: application/json',
			'User-Agent: Komento For Joomla',
			'Content-Length: ' . strlen($paramsStr)
			));
		$output = curl_exec($ch);
		curl_close($ch);

		if (!$output) {
			return false;
		}

		$result = json_decode($output);

		if (!$result || !isset($result->html_url)) {
			return false;
		}

		return $result->html_url;
	}
}
