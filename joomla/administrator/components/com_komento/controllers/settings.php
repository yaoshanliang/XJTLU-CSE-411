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

require_once(__DIR__ . '/controller.php');

class KomentoControllerSettings extends KomentoController
{
	/**
	 * Saves the settings
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function apply()
	{
		KT::checkToken();

		$layout = $this->input->get('current', '', 'word');
		$active = strtolower($this->input->get('active', '', 'word'));

		// Get the settings model
		$model = KT::model('Settings');

		// Get the post data from the form
		$post = JRequest::get('POST');

		$this->cleanup($post);

		foreach ($post as $index => &$value) {
			if (is_array($value)) {
				$value = array_filter($value);
			}
		}

		// Save custom logo for emails
		if (isset($post['custom_email_logo']) && $post['custom_email_logo']) {

			// Get logo
			$file = $this->input->files->get('email_logo', '');

			// Store logo
			if (!empty($file['tmp_name'])) {
				$model->updateEmailLogo($file);
			}
		}

		if (!$model->save($post)) {
			$this->info->set('COM_KOMENTO_SETTINGS_STORE_ERROR', 'error');
		} else {
			$this->info->set('COM_KOMENTO_SETTINGS_STORE_SUCCESS', 'success');
		}

		// Clear the component's cache
		$cache = JFactory::getCache('com_komento');
		$cache->clean();

		$redirect = 'index.php?option=com_komento&view=settings';

		if ($layout) {
			$redirect .= '&layout=' . $layout;
		}

		if ($active) {
			$redirect .= '&active=' . $active;
		}

		$this->app->redirect($redirect);
	}

	/**
	 * Clean up the post data
	 *
	 * @since   3.0
	 * @access  public
	 */
	public function cleanup(&$post)
	{
		unset($post['active']);
		unset($post['activechild']);
		unset($post['current']);
		unset($post['controller']);
		unset($post['option']);
		unset($post['task']);
		unset($post['component']);

		$token = KT::token();
		unset($post[$token]);
	}

	public function cancel()
	{
		$this->app->redirect('index.php?option=com_komento');
	}

	public function save()
	{
		$this->apply();
	}

	/**
	 * Delete email logo
	 *
	 * @since	3.0.7
	 * @access	public
	 */
	public function restoreEmailLogo()
	{
		$notification = KT::notification();
		$notification->restoreEmailLogo();

		return KT::ajax()->resolve();
	}
}
