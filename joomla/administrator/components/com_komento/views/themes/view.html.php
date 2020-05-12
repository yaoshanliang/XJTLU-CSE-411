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

KT::import('admin:/views/views');

class KomentoViewThemes extends KomentoAdminView
{
	/**
	 * Renders the theme's listing
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function display($tpl = null)
	{
		// Check for access
		$this->checkAccess('komento.manage.themes');

		JToolBarHelper::title(JText::_('COM_KOMENTO_THEMES'));
		JToolBarHelper::custom('setDefault', 'default' ,'', JText::_('COM_KOMENTO_TOOLBAR_TITLE_BUTTON_MAKE_DEFAULT'), false);
		JToolbarHelper::custom('edit', 'edit', '', JText::_('COM_KOMENTO_EDIT_TEMPLATE_FILES'), false);

		$this->heading('COM_KOMENTO_SETTINGS_HEADING_THEMES');

		// Get all the themes
		$model = KT::model('themes');
		$themes = $model->getThemes();
		
		$this->set('default', $this->config->get('layout_site_theme'));
		$this->set('themes', $themes);

		parent::display('themes/defaults/default');

	}

	/**
	 * Renders the editor to allow user to edit the theme file
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function edit()
	{
		$this->heading('COM_KOMENTO_TOOLBAR_TITLE_THEMES_EDITOR');

		$element = $this->input->get('element', '', 'cmd');
		$id = $this->input->get('id', '', 'default');

		if (!$element) {
			// $this->setMessage('COM_KOMENTO_THEMES_PLEASE_SELECT_THEME_TO_BE_EDITED', 'error');
			// $this->info->set($this->getMessage());

			return $this->redirect('index.php?option=com_komento&view=themes');
		}

		// Get a list of theme files from this template file
		$model = KT::model('Themes');
		$files = $model->getFiles($element);

		$item = null;
		$table = KT::table('ThemeOverrides');

		if ($id) {
			$item = $model->getFile($id, $element, true);

			JToolBarHelper::apply('saveFile');

			if ($item->modified) {
				JToolBarHelper::trash('revert', JText::_('COM_KOMENTO_REVERT_CHANGES'), false);

				$table->load(array('file_id' => $item->override));
			}

			JToolBarHelper::cancel();
		}

		// Use codemirror editor
		$editor = JFactory::getEditor('codemirror');

		$this->set('table', $table);
		$this->set('id', $id);
		$this->set('editor', $editor);
		$this->set('item', $item);
		$this->set('element', $element);
		$this->set('files', $files);

		parent::display('themes/edit/default');
	}

	/**
	 * Allows site admin to insert custom css codes
	 *
	 * @since   3.0.13
	 * @access  public
	 */
	public function custom()
	{
		$this->heading('COM_KT_TITLE_THEMES_CUSTOM_CSS');

		$editor = JFactory::getEditor('codemirror');

		JToolbarHelper::apply('saveCustomCss');

		$model = KT::model('Themes');
		$path = $model->getCustomCssTemplatePath();
		$contents = '';

		if (JFile::exists($path)) {
			$contents = JFile::read($path);
		}

		$this->set('contents', $contents);
		$this->set('editor', $editor);

		parent::display('themes/custom/default');
	}
}