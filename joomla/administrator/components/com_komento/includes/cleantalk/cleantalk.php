<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class KomentoCleantalk extends KomentoBase
{
	public $lib = null;
	public $endpoint = 'https://moderate.cleantalk.org/api2.0';

	/**
	 * Determines if cleantalk is enabled
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function isEnabled()
	{
		return $this->config->get('cleantalk_enabled') && !is_null($this->config->get('cleantalk_key'));
	}

	/**
	 * Determines if a comment is spam
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function isSpam(KomentoComment $comment)
	{
		$sender = array('user_agent' => @$_SERVER['HTTP_USER_AGENT'], 'referer' => @$_SERVER['HTTP_REFERER']);
		$params = array(
						'sender_email' => $comment->email,
						'sender_nickname' => $comment->name,
						'sender_ip' => $comment->ip,
						'js_on' => true,
						'message' => $comment->getContent(),
						'submit_time' => time() - KT::session()->getTime(),
						'sender_info' => json_encode($sender)
				);
		$response = $this->connect('check_message', $params);

		// 3 - 100% spam
		// 2 - possible spam
		if ($response->allow == 0 && $response->stop_queue == 1) {
			return KOMENTO_CLEANTALK_SPAM;
		}

		if ($response->allow == 0 && $response->stop_queue == 0 && $response->spam == 1) {
			return KOMENTO_CLEANTALK_POSSIBLE_SPAM;
		}

		return false;
	}

	public function connect($method, $params)
	{
		$params = array_merge($params, array('method_name' => $method, 'auth_key' => $this->config->get('cleantalk_key')));

		$resource = curl_init();
		curl_setopt($resource, CURLOPT_URL, 'https://moderate.cleantalk.org/api2.0');
		curl_setopt($resource, CURLOPT_TIMEOUT, 15);
		curl_setopt($resource, CURLOPT_POST, true);
		curl_setopt($resource, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($resource, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($resource, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($resource, CURLOPT_SSL_VERIFYHOST, false);

		$result = curl_exec($resource);

		curl_close($resource);

		$response = json_decode($result);
		
		return $response;
	}

}
