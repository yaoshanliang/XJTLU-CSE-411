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
defined ('_JEXEC') or die ('Restricted access');

if(!class_exists('JwpagefactoryHelperSite')) {
	require_once JPATH_ROOT . '/components/com_jwpagefactory/helpers/helper.php';
}

jimport('joomla.application.component.view');

class JwpagefactoryViewMedia extends JViewLegacy {
	public function display( $tpl = null ) {
		$input = JFactory::getApplication()->input;
		$layout = $input->get('layout', 'browse', 'STRING');
		$this->date = $input->post->get('date', NULL, 'STRING');
		$this->start = $input->post->get('start', 0, 'INT');
		$this->search = $input->post->get('search', NULL, 'STRING');
		$this->limit= 18;

		$model = $this->getModel();

		if(($layout == 'browse') || ($layout == 'modal')) {
			$this->items = $model->getItems();
			$this->filters = $model->getDateFilters($this->date, $this->search);
			$this->total = $model->getTotalMedia($this->date, $this->search);
			$this->categories = $model->getMediaCategories();
		} else {
			$this->media = $model->getFolders();
		}

		JwpagefactoryHelperSite::loadLanguage();

		parent::display($tpl);
	}
}
