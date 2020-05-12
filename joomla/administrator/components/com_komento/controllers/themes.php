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

class KomentoControllerThemes extends KomentoController
{
	public function __construct()
	{
		parent::__construct();

		$this->registerTask('apply', 'store');
		$this->registerTask('save', 'store');
	}

	/**
	 * Set a default theme on the site
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function setDefault()
	{
		KT::checkToken();

		$theme = $this->input->get('cid', array(), 'array');

		if (!$theme) {
			return JError::raiseError(500, 'Invalid theme selected');
		}

		$model = KT::model('Settings');
		$data = array('layout_theme' => $theme);

		$model->save($data);
			
		$this->info->set('COM_KOMENTO_THEME_SET_DEFAULT_SUCCESS', 'success');
		$this->app->redirect('index.php?option=com_komento&view=themes');
	}

	/**
	 * Saves the contents of a theme file
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function saveFile()
	{
		KT::checkToken();

		$element = $this->input->get('element', '', 'cmd');
		$id = $this->input->get('id', '', 'default');
		$contents = $this->input->get('contents', '', 'raw');

		$model = KT::model('Themes');
		$file = $model->getFile($id, $element);

		// Save the file now
		$state = $model->write($file, $contents);

		if (!$state) {
			$this->info->set(JText::sprintf('COM_KOMENTO_THEMES_SAVE_ERROR', $file->override), 'error');
			$this->app->redirect('index.php?option=com_komento&view=themes&layout=edit&element=' . $element . '&id=' . $id);
		}

		// Document the changes
		$table = KT::table('ThemeOverrides');
		$table->load(array('file_id' => $file->override));
		$table->file_id = $file->override;
		$table->notes = $this->input->get('notes', '', 'default');
		$table->contents = $contents;
		$table->store();

		$this->info->set(JText::sprintf('COM_KOMENTO_THEMES_SAVE_SUCCESS', $file->override), 'success');
		$this->app->redirect('index.php?option=com_komento&view=themes&layout=edit&element=' . $element . '&id=' . $id);
	}

	/**
	 * Allows caller to revert a theme file
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function revert()
	{
		KT::checkToken();

		$element = $this->input->get('element', '', 'cmd');
		$id = $this->input->get('id', '', 'default');
		$contents = $this->input->get('contents', '', 'raw');
		
		$model = KT::model('Themes');
		$file = $model->getFile($id, $element);

		// Save the file now
		$state = $model->revert($file);

		if (!$state) {
			$this->info->set(JText::sprintf('COM_KOMENTO_THEMES_DELETE_ERROR', $file->override), 'error');
			$this->app->redirect('index.php?option=com_komento&view=themes&layout=edit&element=' . $element . '&id=' . $id);
		}

		$this->info->set(JText::sprintf('COM_KOMENTO_THEMES_DELETE_SUCCESS', $file->override), 'success');
		$this->app->redirect('index.php?option=com_komento&view=themes&layout=edit&element=' . $element . '&id=' . $id);
	}

	/**
	 * Save custom.css
	 *
	 * @since   3.0.13
	 * @access  public
	 */
	public function saveCustomCss()
	{
		KT::checkToken();
		
		$model = KT::model('themes');
		$path = $model->getCustomCssTemplatePath();

		$contents = $this->input->get('contents', '', 'raw');

		JFile::write($path, $contents);

		$this->info->set(JText::sprintf('COM_KT_THEMES_CUSTOM_CSS_SAVE_SUCCESS', $path), 'success');

		$redirect = 'index.php?option=com_komento&view=themes&view=themes&layout=custom';

		return $this->app->redirect($redirect);
	}	
}