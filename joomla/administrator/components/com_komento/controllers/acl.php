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

require_once(__DIR__ . '/controller.php');

class KomentoControllerAcl extends KomentoController
{
	public function __construct()
	{
		parent::__construct();

		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
	}

	/**
	 * Allows caller to save acl settings
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function save()
	{
		KT::checkToken();

		// Unset unecessary post data.
		$post = JRequest::get('POST');
		unset($post['task']);
		unset($post['option']);

		$token = KT::token();
		unset($post[$token]);

		$id = $this->input->get('id', 0, 'int');
		$current = $this->input->get('current', '', 'word');

		$model = KT::model('Acl');
		$state = $model->save($post);

		$this->info->set('COM_KOMENTO_ACL_STORE_SUCCESS', 'success');

		$cache = JFactory::getCache('com_komento');
		$cache->clean();

		$task = $this->getTask();

		$url = 'index.php?option=com_komento&view=acl';

		if ($task == 'apply') {
			$url .= '&layout=form&id=' . $id . '&current=' . $current;
		}

		return $this->app->redirect($url);
	}
}
