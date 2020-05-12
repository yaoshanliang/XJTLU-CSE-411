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

class KomentoPush extends KomentoBase
{
	public $lib = null;

	/**
	 * Determines if cleantalk is enabled
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function isEnabled()
	{
		$enabled = $this->config->get('onesignal_enabled');
		$appId = $this->config->get('onesignal_app_id');
		$apiKey = $this->config->get('onesignal_api_key');

		if ($enabled && $appId && $apiKey) {
			return true;
		}

		return false;
	}

	/**
	 * Generates the necessary script to activate subscription
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function generateScripts()
	{
		$subdomain = $this->config->get('onesignal_subdomain', '');

		if ($subdomain && stristr($subdomain, 'https://') === false) {
			$subdomain = 'https://' . $subdomain;
		}


		$theme = KT::themes();
		$theme->set('subdomain', $subdomain);
		$output = $theme->output('site/push/onesignal');

		return $output;
	}

	/**
	 * Create a filter rule
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function createFilter($field, $key, $relation, $value)
	{
		$filter = new stdClass();
		$filter->field = $field;
		$filter->key = $key;
		$filter->relation = $relation;
		$filter->value = $value;

		return $filter;
	}

	/**
	 * Create an operator rule
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function createOperator($operator)
	{
		$filter = new stdClass();
		$filter->operator = $operator;

		return $filter;
	}

	/**
	 * Notifies the push api (onesignal)
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function notify(KomentoComment $comment)
	{
		$sendBatch = false;
		$subscribers = array();

		// get the count 1st.
		$subModel = KT::model('subscription');
		$subCount = $subModel->getSubscriberCount($comment->component, $comment->cid);

		// Nobody to push to
		if (!$subCount) {
			return;
		}

		if ($subCount > KOMENTO_PUSH_NOTIFICATION_THRESHOLD) {
			$sendBatch = true;
		}

		// Prepare the contents to be pushed
		$heading = array("en" => JText::sprintf('COM_KOMENTO_PUSH_TITLE', $comment->getAuthorName()));
		$content = array("en" => $comment->getContent(120));
		$filters = array();

		$fields = array(
						'app_id' => $this->config->get('onesignal_app_id'),
						'headings' => $heading,
						'contents' => $content,
						'url' => $comment->getPermalink(),
						'chrome_web_icon' => $comment->getAuthor()->getAvatar()
				);

		if ($sendBatch) {
			// process batch processing here.
			$this->addQueue($comment->component, $comment->cid, $fields);
			return true;
		}

		// Get a list of subscribers
		$subscribers = KT::notification()->getSubscribers('', array('userOnly' => true, 'component' => $comment->component, 'cid' => $comment->cid));

		$i = 0;
		$total = count($subscribers);

		foreach ($subscribers as $subscriber) {

			if ($this->my->id && $subscriber->id == $this->my->id) {
				continue;
			}

			$filters[] = $this->createFilter('tag', 'id', '=', $subscriber->id);

			if ($i + 1 != $total) {
				$filters[] = $this->createOperator('OR');
			}

			$i++;
		}

		if ($filters) {
			$fields['filters'] = $filters;
		} else {
			$fields['included_segments'] = 'All';
		}

		$fields = json_encode($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic ' . $this->config->get('onesignal_api_key')));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	/**
	 * Notifies the push api (onesignal)
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function notifyQueue($max = 5)
	{

		$model = KT::model('pushq');
		$items = $model->getPending($max);

		if (!$items) {
			// nothing to process
			return true;
		}

		// now lets group the parent message and users
		$messages = array();
		$users = array();
		$ids = array();

		foreach ($items as $item) {
			$key = $item->component . '-' . $item->cid;

			if (! isset($messages[$key])) {
				$messages[$key] = json_decode($item->data);
			}

			$users[$key][] = $item->userid;
			$ids[] = $item->id;
		}

		// lets update the records.
		$model->markSent($ids);

		foreach ($messages as $key => $data) {

			$filters = array();

			$fields = KT::makeArray($data);
			$fields['app_id'] = $this->config->get('onesignal_app_id');

			$subscribers = $users[$key];

			if ($subscribers) {
				$total = count($subscribers);
				$i = 0;

				foreach ($subscribers as $id) {

					$filters[] = $this->createFilter('tag', 'id', '=', $id);

					if ($i + 1 != $total) {
						$filters[] = $this->createOperator('OR');
					}

					$i++;
				}

				$fields['filters'] = $filters;

			} else {
				$fields['included_segments'] = 'All';
			}

			$fields = json_encode($fields);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
													   'Authorization: Basic ' . $this->config->get('onesignal_api_key')));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

			$response = curl_exec($ch);
			curl_close($ch);

		}

		return true;
	}


	/**
	 * Add push notification into queue
	 *
	 * @since	3.0.11
	 * @access	public
	 */
	public function addQueue($component, $cid, $fields)
	{
		// we do not want to store the api key.
		if (isset($fields['app_id'])) {
			unset($fields['app_id']);
		}

		$model = KT::model('pushq');
		$model->add($component, $cid, $fields);

		return true;
	}

}
