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

require_once(__DIR__ . '/base.php');

class KomentoControllerDashboard extends KomentoControllerBase
{
	/**
	 * Submit request to download information
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function download()
	{
		// Check for request forgeries
		KT::checkToken();

		// Ensure that the user is logged in
		if (!$this->my->id) {
			return JError::raiseError(500, JText::_('COM_KT_PLEASE_LOGIN_INFO'));
		}

		$table = KT::table('download');
		$exists = $table->load(array('userid' => $this->my->id));

		if ($exists) {
			return JError::raiseError(500, JText::_('COM_KT_GDPR_DOWNLOAD_ERROR_MULTIPLE_REQUEST'));
		}

		$params = array();

		$table->userid = $this->my->id;
		$table->state = KOMENTO_DOWNLOAD_REQ_NEW;
		$table->params = json_encode($params);
		$table->created = KT::date()->toSql();
		$table->store();

		$redirect = JRoute::_('index.php?option=com_komento&view=dashboard&layout=download', false);
		
		$this->app->enqueueMessage(JText::_('COM_KT_GDPR_REQUEST_DATA_SUCCESS'));

		return $this->app->redirect($redirect);
	}
}
