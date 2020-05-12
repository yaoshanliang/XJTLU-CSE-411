<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
//no direct accees
defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');

jimport('joomla.filesystem.folder');

class JwpagefactoryController extends JControllerLegacy
{

	protected $default_view = 'pages';

	function display($cachable = false, $urlparams = false)
	{
		$view = $this->input->get('view', 'pages');
		$layout = $this->input->get('layout', 'pages');
		$id = $this->input->getInt('id');

		if ($view == 'page' && $layout == 'edit' && !$this->checkEditId('com_jwpagefactory.edit.page', $id)) {
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_jwpagefactory&view=pages', false));

			return false;
		}

		return parent::display();
	}

	public function resetcss()
	{
		$css_folder_path = JPATH_ROOT . '/media/com_jwpagefactory/css';
		if (JFolder::exists($css_folder_path)) {
			JFolder::delete($css_folder_path);
		}
		die();
	}

	public function export()
	{
		$input = JFactory::getApplication()->input;
		$template = $input->get('template', '[]', 'RAW');
		$filename = 'template' . rand(10000, 99999);

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=$filename.json");
		header("Content-Type: application/json");
		header("Content-Transfer-Encoding: binary ");

		echo $template;
		die();
	}
}
