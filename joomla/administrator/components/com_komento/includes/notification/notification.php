<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

jimport('joomla.mail.helper');

class KomentoNotification extends KomentoBase
{
	public $logoName = 'email-logo.png';

	private $mailfrom = null;
	private $fromname = null;

	public function __construct()
	{
		parent::__construct();

		$this->mailfrom = KT::jconfig()->get('mailfrom', '');
		$this->fromname = KT::jconfig()->get('fromname', '');
	}

	/**
	 * Push the email notification to MailQ
	 * @since	3.0
	 * @access	public
	 */
	public function push($type, $recipients, $options = array())
	{
		if (!empty($options['commentId'])) {

			$comment = KT::comment($options['commentId']);
			$options['comment'] = $comment;
			$options['component'] = $comment->component;
			$options['cid'] = $comment->cid;
			$options['comment'] = $comment;

			unset($options['commentId']);
		}

		if (!isset($options['component']) || !isset($options['cid'])) {
			return;
		}

		// Determine the type of the comment
		if ($type == 'new' && $options['comment']->parent_id) {
			$type = 'reply';
		}

		// Determine whether system need to notify to the subscriber
		$notifySubscribers = $this->config->get('notification_to_subscribers');

		$recipients	= explode(',', $recipients);
		$rows = array();
		$excludes = array();
		$skipMe = true;

		$batchType = array();

		// process requested recipients first
		foreach ($recipients as $recipient) {

			// if recipient is subscribers or usergroup, we will skip here.
			if ($recipient == 'usergroups' || $recipient == 'subscribers') {
				$batchType[] = $recipient;
				continue;
			}

			// since we already pass in usergroup and subscribers into the batchtype
			// this will always call getAuthor or getMe method
			$recipient = 'get' . ucfirst(strtolower(trim($recipient)));

			$exists = method_exists($this, $recipient);

			if (!$exists) {
				continue;
			}

			if ($recipient == 'getMe') {
				$skipMe = false;
			}

			$result = $this->$recipient($type, $options);

			$rows = array_merge($result, $rows);
		}

		$excludes = $rows;

		// Merge the result set
		// $rows = array_merge($rows, $this->getUsergroups($type));

		if ($type == 'report') {
			$admins = $this->getAdmins();

			foreach ($admins as $admin) {
				if (isset($rows[$options['comment']->email]) && $options['comment']->email === $admin->email) {
					$skipMe = false;
				}
			}
		}

		// If nothing to process, skip everything
		if (empty($rows) && $type != 'pending' && empty($batchType)) {
			return;
		}

		// Do not send to the commentor/actor
		if ($skipMe) {
			$obj = new stdClass();
			$obj->id = $options['comment']->created_by;
			$obj->fullname = $options['comment']->name;
			$obj->email = $options['comment']->email;

			$excludes[$obj->email] = $obj;

			if (isset($rows[$options['comment']->email]) && $rows[$options['comment']->email]) {
				unset($rows[$options['comment']->email]);
			}
		}

		$lang = JFactory::getLanguage();

		// Load English first as fallback
		$config = KT::config();

		if ($config->get('enable_language_fallback')) {
			$lang->load('com_komento', JPATH_ROOT, 'en-GB', true);
		}

		$lang->load('com_komento', JPATH_ROOT, $lang->getDefault(), true);
		$lang->load('com_komento', JPATH_ROOT, null, true);

		$jconfig = JFactory::getConfig();
		$data = $this->prepareData($type, $options);
		$template = $this->getTemplateNamespace($type, $options);
		$subject = $this->prepareTitle($type, $options);

		// Pending e-mails should also be processed
		if (!empty($rows) && $type == 'pending') {
			foreach ($rows as $row) {
				$unsubscribe = false;

				$state = $this->insertMailQueue($subject, $template, $data, $row, $unsubscribe, $options);

				if (!$state) {
					continue;
				}
			}
		}

		// Storing notifications into mailq
		if (!empty($rows) && $type != 'pending') {

			foreach ($rows as $row) {
				$unsubscribe = false;

				// assign unsubscription link into email template
				if (($type == 'reply' || $type == 'comment' || $type == 'new') && isset($row->subscriptionid) && $row->subscriptionid) {

					$unsubscribeData = array(
											'subscriptionid' => $row->subscriptionid,
											'component' => $row->component,
											'id' => $row->id, // user id
											'email' => $row->email,
											'cid' => $row->cid, // article id
											'token' => md5($row->subscriptionid.$row->created)
										);

					// Generate the unsubscribe hash
					$hash = base64_encode(json_encode($unsubscribeData));

					$unsubscribe = rtrim(JURI::root(), '/') . '/index.php?option=com_komento&controller=subscriptions&task=unSubscribeFromEmail&data=' . $hash;
				}

				$state = $this->insertMailQueue($subject, $template, $data, $row, $unsubscribe, $options);

				if (!$state) {
					continue;
				}
			}
		}

		// process the email batch
		if ($batchType) {

			$ids = array();

			foreach ($batchType as $batch) {
				// Do not notify to the subscriber if the setting is turn off
				if ($batch == 'subscribers' && !$notifySubscribers) {
					continue;
				}

				if ($batch == 'usergroups') {
					$ids = $this->getUsergroups($type, true);
				}

				$this->addMailqBatch($batch, $options['component'], $options['cid'], $subject, $this->mailfrom, $this->fromname, $template, $data, $ids, $excludes);
			}
		}
	}

	/**
	 * Insert into the mail queue
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public function insertMailQueue($subject, $template, $data, $recipient, $unsubscribe, $options = array())
	{
		if (!empty($options)) {
			$triggerResult = KT::trigger('onBeforeSendNotification', array('component' => $options['component'], 'cid' => $options['cid'], 'recipient' => &$recipient));
			
			if ($triggerResult === false) {
				return false;
			}
		}
		
		$validEmail = JMailHelper::isEmailAddress($recipient->email);

		if (empty($recipient->email) || !$validEmail) {
			return false;
		}

		$contents = $this->getTemplateContents($template, $data, array('recipient' => $recipient), $unsubscribe);

		$table = KT::table('mailq');
		$table->mailfrom = $this->mailfrom;
		$table->fromname = $this->fromname;
		$table->recipient = $recipient->email;
		$table->subject = $subject;
		$table->body = $contents;
		$table->created = KT::date()->toSql();
		$table->type = 'html';
		$table->status = 0;

		$result = $table->store();

		return $result;
	}

	/**
	 * Retrieve the contents of the template file
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function addMailqBatch($type, $component, $cid, $subject, $mailfrom, $fromname, $template, $data, $ids = array(), $exclude = array())
	{
		$db = KT::db();
		$insertDate = KT::date()->toMySQL();

		if ($type == 'usergroups' && !$ids) {
			// empty user groups. nothing to send.
			return true;
		}

		$excludeIds = array();

		if ($exclude) {
			foreach ($exclude as $targetEmail => $item) {
				if ($item->id) {
					$excludeIds[] = $item->id;
				}
			}
		}

		$query = "insert into `#__komento_mailq` (`recipient`, `mailfrom`, `fromname`, `subject`, `created`, `type`, `status`, `template`, `data`, `params`)";
		$query .= " SELECT a.`email`," . $db->Quote($mailfrom) . "," . $db->Quote($fromname) . "," . $db->Quote($subject) . "," . $db->Quote($insertDate) . ", " . $db->Quote('html') . "," . $db->Quote('0');
		$query .= ", " . $db->Quote($template) . "," . $db->Quote(json_encode($data));


		if ($type == 'subscribers') {

			$query .= ", concat('{\"subscriptionid\":\"', a.`id`, '\",\"component\":\"', a.`component`, '\", \"id\":\"', a.userid,'\",\"name\":\"', a.`fullname`,'\",\"email\":\"', a.`email`, '\",\"cid\":\"', a.`cid`, '\", \"token\":\"', MD5(concat(a.id,a.created)) , '\"}')";
			$query .= " from `#__komento_subscription` as a";

			$query .= " where a.`component` = " . $db->Quote($component);
			$query .= " and a.`cid` = " . $db->Quote($cid);
			$query .= " and a.`published` = " . $db->Quote(1);

			if ($excludeIds) {
				$query .= "	and a.`userid` NOT IN (" . implode(',', $excludeIds) . ")";
			}
		}

		if ($type == 'usergroups') {
			$query .= ", concat('{\"id\":\"', a.id,'\",\"name\":\"', a.`name`,'\",\"email\":\"', a.`email`, '\"}')";

			$query .= " from `#__users` as a";
			$query .= "   inner join `#__user_usergroup_map` as ag on a.`id` = ag.`user_id`";
			$query .= "     where a.`block` = " . $db->quote(0);
			$query .= "	    and ag.`group_id` IN (" . implode(',', $ids) . ")";

			if ($excludeIds) {
				$query .= "	and a.`id` NOT IN (" . implode(',', $excludeIds) . ")";
			}

			// Get super admin group id
			$saIds = KT::getSAIds();

			// Exclude super admin that turn off the system email
			$query .= ' AND a.`id` NOT IN (';
			$query .= '  SELECT u.`id` FROM `#__users` as u';
			$query .= '  inner join `#__user_usergroup_map` as ug on u.`id` = ug.`user_id`';
			$query .= '  where ug.`group_id` IN(' . implode(',', $saIds) . ') and u.`sendEmail` = ' . $db->Quote(0);
			$query .= ' )';
		}

		$db->setQuery($query);
		$db->query();
	}


	/**
	 * Retrieve the contents of the template file
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getTemplateContents($namespace, $data, $params = array(), $unsubscribe = false)
	{
		// Get the body of the template
		$body = $this->getTemplateBuffer($namespace, $data, $params);

		// Get email logo
		$logo = $this->getLogo();

		// Fetch the template structure
		$theme = KT::themes();
		$theme->set('unsubscribe', $unsubscribe);
		$theme->set('contents', $body);
		$theme->set('logo', $logo);

		$output = $theme->output('site/emails/template');

		return $output;
	}

	/**
	 * Retrieves the content from the template
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getTemplateBuffer($namespace, $data, $params = array())
	{
		$theme = KT::themes();

		foreach ($data as $key => $val) {
			$theme->set($key, $val);
		}

		$theme->set('data', $data);
		$theme->set('options', $params);
		$theme->set('document', $this->doc);

		$contents = $theme->output($namespace);

		return $contents;
	}

	/**
	 * Method to prepare the data used by email template.
	 *
	 * @since	3.0
	 * @access	private
	 */
	private function prepareData($type = 'new', $options)
	{
		KT::import('admin:/includes/date/date');

		$data = array();
		$data['contentTitle'] = '';

		// New method to get item's title
		if (isset($options['comment']) && $options['comment'] instanceof KomentoComment) {
			$data['contentTitle'] = $options['comment']->getItemTitle();
		}

		// Legacy method
		if (isset($options['comment']->contenttitle)) {
			$data['contentTitle'] = $options['comment']->contenttitle;
		}


		if ($type === 'confirm') {

			// If the content title is empty, we get from the application
			if (!$data['contentTitle']) {
				$application = KT::loadApplication($options['component']);
				$application->load($options['cid']);

				$data['contentTitle'] = $application->getContentTitle();
			}

			$subscribeTable = KT::getTable('subscription');
			$subscribeTable->load($options['subscribeId']);

			$profile = KT::getProfile($subscribeTable->userid);

		} else {
			$profile = KT::getProfile($options['comment']->created_by);

			// New method
			if (isset($options['comment']) && $options['comment'] instanceof KomentoComment) {
				$data['contentPermalink'] = $options['comment']->getItemPermalink();
				$commentPermalink = $options['comment']->getPermalink();
			}

			// Legacy
			if (isset($options['comment']->pagelink)) {
				$data['contentPermalink'] = $options['comment']->pagelink;
				$commentPermalink = $data['contentPermalink'] . '#comment-' . $options['comment']->id;
			}

			$data['commentAuthorName'] = $options['comment']->name;
			$data['commentAuthorAvatar'] = $profile->getAvatar();
		}

		$config = KT::config();

		switch($type)
		{
			case 'confirm':
				$hashkeys = KT::getTable('hashkeys');
				$hashkeys->uid = $options['subscribeId'];
				$hashkeys->type = 'subscribe';
				$hashkeys->store();

				$key = $hashkeys->key;

				$data['confirmLink'] = rtrim(JURI::root(), '/') . '/index.php?option=com_komento&task=confirmSubscription&token=' . $key;
				break;
			case 'pending':
			case 'moderate':
				$hashkeys = KT::getTable('hashkeys');
				$hashkeys->uid = $options['comment']->id;
				$hashkeys->type = 'comment';
				$hashkeys->store();

				$key = $hashkeys->key;

				$data['attachments'] = $options['comment']->getAttachments('all');
				$data['commentPermalink'] = $commentPermalink;
				$data['approveLink'] = rtrim(JURI::root(), '/') . '/index.php?option=com_komento&task=approveComment&token=' . $key;
				$data['deleteLink'] = rtrim(JURI::root(), '/') . '/index.php?option=com_komento&task=deleteComment&token=' . $key;
				$data['commentContent'] = $options['comment']->getContent();
				break;
			case 'report':
				$action = KT::getTable('actions');
				$action->load($options['actionId']);
				$actionUser = $action->action_by;

				$data['actionUser'] = KT::user($actionUser);

				$data['commentPermalink'] = $commentPermalink;
				$data['commentContent'] = $options['comment']->getContent();
				break;
			case 'reply':
			case 'comment':
			case 'new':
			default:
				$data['commentPermalink'] = $commentPermalink;
				$data['commentContent'] = $options['comment']->getContent();
				break;
		}

		return $data;
	}

	/**
	 * Determines the template file to be used in the mail
	 *
	 * @since	3.0
	 * @access	public
	 */
	private function getTemplateNamespace($type = 'new')
	{
		// Default file would be new comment template
		$file = 'comment.new';

		if ($type == 'reply') {
			$file = 'comment.reply';
		}

		if ($type == 'pending' || $type == 'moderate') {
			$file = 'comment.moderate';
		}

		if ($type == 'confirm') {
			$file = 'subscription.confirm';
		}

		if ($type == 'report') {
			$file = 'comment.report';
		}

		$file = 'site/emails/' . $file;

		return $file;
	}

	/**
	 * Prepares the e-mail subject
	 *
	 * @since	3.0
	 * @access	public
	 */
	private function prepareTitle($type = 'new', $options = array())
	{
		$subject = '';

		switch ($type) {
			case 'pending':
			case 'moderate':
				$subject = JText::_('COM_KOMENTO_NOTIFICATION_PENDING_COMMENT_SUBJECT') . ' (' . $options['comment']->getItemTitle() . ')';
				break;
			case 'confirm':
				$title = isset($options['comment']) ? $options['comment']->getItemTitle() : '';
				$subject = JText::_('COM_KOMENTO_NOTIFICATION_CONFIRM_SUBSCRIPTION_SUBJECT') . ' (' . $title . ')';
				break;
			case 'report':
				$subject = JText::_('COM_KOMENTO_NOTIFICATION_REPORT_COMMENT_SUBJECT') . ' (' . $options['comment']->getItemTitle() . ')';
				break;
			case 'reply':
				$subject = JText::_('COM_KOMENTO_NOTIFICATION_NEW_REPLY_SUBJECT') . ' (' . $options['comment']->getItemTitle() . ')';
				break;
			case 'comment':
			case 'new':
			default:
				$subject = JText::_('COM_KOMENTO_NOTIFICATION_NEW_COMMENT_SUBJECT') . ' (' . $options['comment']->getItemTitle() . ')';
				break;
		}

		return $subject;
	}

	/**
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getMe($type, $options)
	{
		$obj = new stdClass();
		$my = JFactory::getUser();

		if (empty($my->id)) {
			if ($type === 'confirm' && isset($options['subscribeId'])) {
				$subscribeTable = KT::getTable('subscription');
				$subscribeTable->load($options['subscribeId']);

				$obj->id = 0;
				$obj->fullname = $subscribeTable->fullname;
				$obj->email = $subscribeTable->email;

				return array($obj->email => $obj);
			}

			return array();
		}

		$obj->id = $my->id;
		$obj->fullname = JText::_($my->name);
		$obj->email = $my->email;

		return array($my->email => $obj);
	}

	/**
	 * Retrieves author of the content
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getAuthor($type, $options)
	{
		$config = KT::getConfig();
		if (!$config->get('notification_to_author')) {
			return array();
		}

		$application = KT::loadApplication($options['component'])->load($options['cid']);

		if ($application === false) {
			$application = KT::getErrorApplication($options['component'], $options['cid']);
		}

		$userid = $application->getAuthorId();

		$obj = new stdClass();
		$user = JFactory::getUser($userid);
		$obj->id = $user->id;
		$obj->fullname = JText::_($user->name);
		$obj->email = $user->email;

		return array($user->email => $obj);
	}

	/**
	 * Retrieves a list of subscribers on the site
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getSubscribers($type = '', $options)
	{
		if (!$this->config->get('notification_to_subscribers')) {
			return array();
		}

		// Normalize options
		$component = isset($options['component']) ? $options['component'] : '';
		$cid = isset($options['cid']) ? $options['cid'] : '';
		$usersOnly = isset($options['usersOnly']) ? $options['usersOnly'] : false;

		if (!$component || !$cid) {
			return array();
		}

		$sql = KT::sql();
		$sql->select('#__komento_subscription')
			->column('id', 'subscriptionid')
			->column('component')
			->column('userid', 'id')
			->column('fullname')
			->column('email')
			->column('cid')
			->column('created')
			->where('component', $options['component'])
			->where('cid', $options['cid'])
			->where('published', 1);

		if ($usersOnly) {
			$sql->where('userid', '', '!=');
		}

		$subscribers = $sql->loadObjectList();

		$result = array();

		if (!$subscribers) {
			return $result;
		}

		foreach ($subscribers as $subscriber) {
			$result[$subscriber->email] = $subscriber;
		}

		return $result;
	}

	/**
	 * Retrieves a list of users by user groups
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getUsergroups($type, $gidOnly = false)
	{
		$config = KT::getConfig();

		$gids = '';

		switch($type)
		{
			case 'confirm':
				break;
			case 'pending':
			case 'moderate':
				$gids = $config->get('notification_to_usergroup_pending');
				break;
			case 'report':
				$gids = $config->get('notification_to_usergroup_reported');
				break;
			case 'reply':
				$gids = $config->get('notification_to_usergroup_reply');
				break;
			case 'comment':
			case 'new':
			default:
				$gids = $config->get('notification_to_usergroup_comment');
				break;
		}

		if (!empty($gids)) {
			if (!is_array($gids)) {
				$gids = explode(',', $gids);
			}

			if ($gidOnly) {
				return $gids;
			}

			$users = array();
			$ids = array();

			foreach ($gids as $gid) {
				$results = KT::getUsersByGroup($gid);

				foreach ($results as $id) {

					$tmp = JFactory::getUser($id);

					$user = array(
						'id' => $tmp->id,
						'fullname' => $tmp->name,
						'email' => $tmp->email
					);

					$users[$tmp->email] = (object) $user;

				}
			}

			return $users;
		}

		return array();
	}

	/**
	 * Retrieve a list of admin emails
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function getAdmins()
	{
		if (!$this->config->get('notification_to_admins')) {
			return array();
		}


		$saUsersIds	= KT::getSAUsersIds();

		$sql = KT::sql();
		$sql->select('#__users')
			->column('id')
			->column('name', 'fullname')
			->column('email');

		if ($saUsersIds) {
			$sql->where('id', $saUsersIds, 'in');
		}

		$sql->where('sendEmail', '1');

		$admins	= $sql->loadObjectList();
		$result = array();

		if (!$admins) {
			return $result;
		}

		foreach ($admins as $admin) {
			$result[$admin->email] = $admin;
		}

		return $result;
	}

	/**
	 * Retrieves e-mail logo
	 *
	 * @since	3.0.7
	 * @access	public
	 */
	public function getLogo($forceDefault = false)
	{
		$logo = rtrim(JURI::root(), '/') . '/media/com_komento/images/' . $this->logoName;

		if ($forceDefault) {
			return $logo;
		}

		if ($this->hasOverrideLogo() && $this->config->get('custom_email_logo')) {
			$override = $this->getOverridePath();
			$override = rtrim(JURI::root(), '/') . $override;
			return $override;
		}

		return $logo;
	}

	/**
	 * Determine if custom logo is exists
	 *
	 * @since	3.0.7
	 * @access	public
	 */
	public function hasOverrideLogo()
	{
		$path = JPATH_ROOT . $this->getOverridePath();

		if (JFile::exists($path)) {
			return true;
		}

		return false;
	}

	/**
	 * Get override path for email logo
	 *
	 * @since	3.0.7
	 * @access	public
	 */
	public function getOverridePath()
	{
		// Get current template
		$defaultJoomlaTemplate = KT::template()->getCurrentTemplate();

		$path = '/templates/' . $defaultJoomlaTemplate . '/html/com_komento/emails/' . $this->logoName;

		return $path;
	}

	/**
	 * Store email logo
	 *
	 * @since	3.0.7
	 * @access	public
	 */
	public function storeEmailLogo($file)
	{
		// Do not proceed if image doesn't exist.
		if (empty($file) || !isset($file['tmp_name'])) {
			return false;
		}

		$source = $file['tmp_name'];

		$path = JPATH_ROOT . $this->getOverridePath();

		// Try to upload the image
		$state = JFile::upload($source, $path);

		if (!$state) {
			$this->setError(JText::_('COM_KOMENTO_EMAIL_LOGO_UPLOAD_ERROR'));
			return false;
		}

		return true;
	}

	/**
	 * Restore Email Logo
	 *
	 * @since	3.0.7
	 * @access	public
	 */
	public function restoreEmailLogo()
	{
		if (!$this->hasOverrideLogo()) {
			return false;
		}

		// Get override path
		$path = JPATH_ROOT . $this->getOverridePath();

		// Let's delete it
		JFile::delete($path);

		return true;
	}
}
