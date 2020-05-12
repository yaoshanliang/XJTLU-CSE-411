<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class KomentoSubscription extends KomentoBase
{
	public $type = null;

	// This contains the error message.
	public $error = null;

	public function __construct($type = 'comment')
	{
		parent::__construct();

		$this->type = $type;
	}

	/**
	 * Allows caller to insert a new subscription for a particular item
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function add($component, $cid, $data)
	{
		$userid = isset($data['userid']) && $data['userid'] ? $data['userid'] : 0;
		$email = isset($data['email']) && $data['email'] ? $data['email'] : '';

		// perform basic validation.
		$exists = $this->exists($component, $cid, $userid, $email);
		if ($exists) {
			$this->setError('You have already subscribed and you should not be able to subscribe again');
			return false;
		}

		// comment id
		$commentId = isset($data['commentId']) && $data['commentId'] ? $data['commentId'] : '0';

		$table = KT::table('Subscription');

		$table->type = $this->type;
		$table->component = $component;
		$table->cid = $cid;
		$table->userid = $userid;
		$table->fullname = isset($data['fullname']) && $data['fullname'] ? $data['fullname'] : '';
		$table->email = $email;
		$table->created = JFactory::getDate()->toSql();
		$table->published = 1;

		if ($this->config->get('subscription_confirmation')) {
			$table->published = 0;
		}

		$state = $table->store();

		if (!$state) {
			$this->setError($table->getError());
			return false;
		}

		// lets check if we need to notify the subscriber or not.
		if ($this->config->get('subscription_confirmation')) {
			KT::notification()->push('confirm', 'me', array('component' => $component, 'cid' => $cid, 'subscribeId' => $table->id, 'commentId' => $commentId));
		}

		return true;
	}

	/**
	 * Allows caller to remove a subscription
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function remove($component, $cid, $userId = 0, $userEmail = '')
	{
		// perform validation first.
		$exists = $this->exists($component, $cid, $userId, $userEmail);

		if ($exists === null) {
			$this->setError('Subscription not found.');
			return false;
		}

		$model = KT::model('Subscription');
		$state = $model->unsubscribe($component, $cid, $userId, $userEmail, $this->type);

		if (!$state) {
			$this->setError($model->getError());
			return false;
		}

		return true;
	}

	/**
	 * Determines if a subscription exists on the site
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function exists($component, $cid, $userId = 0, $userEmail = '')
	{
		if (!$userId && !$userEmail) {
			$this->setError('Invalid subscriber details.');
			return false;
		}

		$exists = false;
		$model = KT::model('Subscription');

		if ($userId) {
			$exists = $model->checkSubscriptionExist($component, $cid, $userId, '', $this->type);
		} else {
			// user email to check
			$exists = $model->checkSubscriptionExist($component, $cid, '', $userEmail, $this->type);

		}

		return $exists;
	}


	/**
	 * Sets an error message
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function setError($message = '')
	{
		$this->error = JText::_($message);
	}

	/**
	 * Get an error message
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function getError($message = '')
	{
		return $this->error;
	}

	/**
	 * Allows caller to remove a subscription from email
	 *
	 * @since	3.0.11
	 * @access	public
	 */
	public function removeSubscriptionFromEmail($data)
	{
		// decode that hash data
		$data = base64_decode($data);
		$data = json_decode($data);

		// perform validation first.
		$exists = $this->exists($data->component, $data->cid, $data->id, $data->email);

		if ($exists === null) {
			echo JText::_('COM_KOMENTO_SUBSCRIPTION_NOT_FOUND');
			exit;
		}

		$subscription = KT::table('Subscription');
		$subscription->load($data->subscriptionid);

		// Verify if this user has access to unsubscribe for guest user
		if (!$subscription->id) {
			echo JText::_('COM_KOMENTO_NOT_ALLOWED');
			exit;
		}

		// Ensure that the registered user is allowed to unsubscribe.
		if ($subscription->userid && $this->my->id != $subscription->userid && !KT::isSiteAdmin()) {
			echo JText::_('COM_KOMENTO_NOT_ALLOWED');
			exit;
		}

		// Ensure that unsubscribe token is match
		if ($data->token != md5($subscription->id . $subscription->created)) {
			echo JText::_('COM_KOMENTO_NOT_ALLOWED');
			exit;
		}

		$model = KT::model('Subscription');
		$state = $model->unsubscribe($data->component, $data->cid, $data->id, $data->email, $this->type);

		if (!$state) {
			$errorMessage = $model->getError();
			echo $errorMessage;
			exit;
		}

		// Get the item permalink so that we can redirect user to a proper page
		$model = KT::model('Comments');
		$itemPermalink = $model->getItemPermalink($data->component, $data->cid);

		$this->app->enqueueMessage(JText::_('COM_KOMENTO_UNSUBSCRIBED_SUCCESSFULLY'));
		return $this->app->redirect($itemPermalink);
	
	}
}
