<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(__DIR__ . '/parent.php');

class KomentoTableMailq extends KomentoParentTable
{
	public $id = null;
	public $mailfrom = null;
	public $fromname = null;
	public $recipient = null;
	public $subject = null;
	public $body = null;
	public $created = null;
	public $type = null;
	public $status = null;
	public $template = null;
	public $data = null;
	public $params = null;

	public function __construct(&$db)
	{
		parent::__construct('#__komento_mailq', 'id', $db);
	}

	public function processTemplateContent()
	{
		$content = '';

		if ($this->template) {

			$notti = KT::notification();

			$data = '';
			$unsubscribe = false;

			if ($this->data) {
				$data = json_decode($this->data);
			}

			$obj = new stdClass();
			$obj->id = 0;
			$obj->fullname = '';
			$obj->email = '';

			if ($this->params) {
				$params = json_decode($this->params);

				if (isset($params->subscriptionid) && $params->subscriptionid) {

					// construct user object
					$obj->id = $params->id;
					$obj->fullname = $params->name;
					$obj->email = $params->email;

					$unsubscribeData = array(
										'subscriptionid' => $params->subscriptionid,
										'component' => $params->component,
										'id' => $params->id, // user id
										'email' => $params->email,
										'cid' => $params->cid, // article id
										'token' => $params->token
									);

					// Generate the unsubscribe hash
					$hash = base64_encode(json_encode($unsubscribeData));
					$unsubscribe = rtrim(JURI::root(), '/') . '/index.php?option=com_komento&controller=subscriptions&task=unSubscribeFromEmail&data=' . $hash;

				} else {
					// construct user object
					$obj->id = $params->id;
					$obj->fullname = $params->name;
					$obj->email = $params->email;
				}
			}

			$content = $notti->getTemplateContents($this->template, $data, array('recipient' => $obj), $unsubscribe);
		}

		return $content;
	}

}
