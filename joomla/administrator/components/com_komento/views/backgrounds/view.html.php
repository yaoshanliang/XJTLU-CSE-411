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

class KomentoViewBackgrounds extends KomentoAdminView
{
	/**
	 * Renders the preset's listing
	 *
	 * @since	3.1
	 * @access	public
	 */
	public function display($tpl = null)
	{		
		$this->heading('COM_KT_TITLE_THEMES_EDITOR_PRESETS');

		JToolbarHelper::addNew();
		JToolBarHelper::divider();
		JToolbarHelper::deleteList();

		$model = KT::model('Backgrounds');
		$presets = $model->getPresets();

		$this->set('presets', $presets);
		parent::display('backgrounds/default');
	}

	/**
	 * Preset item
	 *
	 * @since   3.1
	 * @access  public
	 */
	public function form()
	{
		$this->heading('COM_KT_TITLE_THEMES_EDITOR_PRESETS');
		
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolbarHelper::save2new();
		JToolBarHelper::cancel();

		$id = $this->input->get('id', '', 'default');
		$preset = KT::table('Backgrounds');
		$preset->load($id);
		
		if (!$preset->id) {
			$preset->published = true;
		}

		$params = $preset->getParams();
		$return = base64_encode('index.php?option=com_komento&view=backgrounds');

		$this->set('preset', $preset);
		$this->set('params', $params);
		$this->set('return', $return);
		parent::display('backgrounds/form');
	}	
}