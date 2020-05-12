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
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'controller.php');

class KomentoControllerBackgrounds extends KomentoController
{
	public function __construct()
	{
		parent::__construct();

		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('save2new', 'save');
		$this->registerTask('publish', 'togglePublish');
		$this->registerTask('unpublish', 'togglePublish');
	}

	/**
	 * Toggles publishing states for backgrounds
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function togglePublish()
	{
		// Check for request forgeries
		KT::checkToken();

		$ids = $this->input->get('cid', array(0));
		$redirect = 'index.php?option=com_komento&view=backgrounds';
		
		if (!$ids) {
			$this->info->set('COM_KT_BACKGROUNDS_INVALID_ID', 'error');
			$this->app->redirect($redirect);
		}

		$task = $this->getTask() == 'publish' ? 1 : 0;

		foreach ($ids as $id) {
			$table = KT::table('Backgrounds');
			$table->load($id);

			$table->published = $task;
			$table->store();
		}

		$message = $task == 'unpublish' ? 'COM_KT_BACKGROUNDS_UNPUBLISHED' : 'COM_KT_BACKGROUNDS_PUBLISHED';

		$this->info->set(JText::_($message), 'success');
		$this->app->redirect($redirect);
	}


	public function remove()
	{
		KT::checkToken();

		$ids = $this->input->get('cid', '', 'POST');
		$redirect = 'index.php?option=com_komento&view=backgrounds';

		if (empty($ids)) {
			$this->info->set('COM_KT_BACKGROUNDS_INVALID_ID', 'error');
			$this->app->redirect($redirect);
		}

		foreach ($ids as $id) {

			$table = KT::table('Backgrounds');
			$table->load($id);

			$state = $table->delete();

			if (!$state) {
				$this->info->set('COM_KT_BACKGROUNDS_DELETE_FAILED', 'error');
				$this->app->redirect($redirect);
			}
		}

		$this->info->set('COM_KT_BACKGROUNDS_DELETE_SUCCESSFULLY', 'success');
		$this->app->redirect($redirect);
	}

	/**
	 * Saves the editor preset
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function save()
	{
		KT::checkToken();

		$id = $this->input->get('id', 0, 'int');

		$preset = KT::table('Backgrounds');
		$preset->load($id);

		$redirect = 'index.php?option=com_komento&view=backgrounds';

		// Get the task
		$task = $this->getTask();

		$color1 = $this->input->get('color1', '', 'string');
		$color2 = $this->input->get('color2', '', 'string');
		$fontColor = $this->input->get('fontcolor', '', 'string');
		$title = $this->input->get('title', '', 'string');
		$type = $this->input->get('type', '', 'string');
		$published = $this->input->get('published', '', 'bool');

		$params = new JRegistry();
		$params->set('color1', $color1);
		$params->set('color2', $color2);
		$params->set('fontcolor', $fontColor);

		// validation
		if (!isset($title) || trim($title) == "") {

			$message = JText::_('Preset title cannot be empty');
			$this->info->set($message, 'error');

			$redirect = 'index.php?option=com_komento&view=backgrounds&layout=form';

			if ($preset->id) {
				$redirect .= '&id=' . $preset->id;
			}

			return $this->app->redirect($redirect);
		}

		if (!$id) {
			$preset->created = KT::date()->toSql();
		}

		$preset->title = $title;
		$preset->params = $params->toString();
		$preset->type = $type;
		$preset->published = $published;

		// Save the preset
		$preset->store();

		if ($task == 'save2new') {
			$redirect = 'index.php?option=com_komento&view=backgrounds&layout=form';
		}

		if ($task == 'apply') {
			$redirect = 'index.php?option=com_komento&view=backgrounds&layout=form&id=' . $preset->id;
		}

		// Display message
		$this->info->set('Successfully saved preset', 'success');

		return $this->app->redirect($redirect);
	}
}