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

// import Joomla view library
jimport('joomla.application.component.view');

if(!class_exists('JwpagefactoryHelperSite')) {
	require_once JPATH_ROOT . '/components/com_jwpagefactory/helpers/helper.php';
}

class JwpagefactoryViewForm extends JViewLegacy
{
	protected $form;
	protected $item;

	function display( $tpl = null )
	{

		$user = JFactory::getUser();
		$app  = JFactory::getApplication();

		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		if ( !$user->id ) {
			$uri = JFactory::getURI();
			$pageURL = $uri->toString();
			$return_url = base64_encode($pageURL);
			$joomlaLoginUrl = 'index.php?option=com_users&view=login&return=' . $return_url;

			$app->redirect(JRoute::_($joomlaLoginUrl, false), JText::_('JERROR_ALERTNOAUTHOR'), 'message');
			return false;
		}

		$input 		 = JFactory::getApplication()->input;
		$pageid 	 = $input->get('id', '', 'INT');
		$item_info  = JwpagefactoryModelPage::getPageInfoById($pageid);
		$authorised = $user->authorise('core.edit', 'com_jwpagefactory.page.' . $pageid) || ($user->authorise('core.edit.own',   'com_jwpagefactory.page.' . $pageid) && $item_info->created_by == $user->id);

		if ($authorised !== true)
		{
			$app->enqueueMessage(JText::_('COM_JWPAGEFACTORY_ERROR_EDIT_PERMISSION'), 'error');
			$app->setHeader('status', 403, true);
			return false;
		}

		// Check for errors.
		if (count($errors = (array) $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));

			return false;
		}

		$this->_prepareDocument($this->item->title);
		JwpagefactoryHelperSite::loadLanguage();
		parent::display($tpl);
	}

	protected function _prepareDocument($title = '')
	{
		$config 	= JFactory::getConfig();
		$app 		= JFactory::getApplication();
		$doc 		= JFactory::getDocument();
		$menus   	= $app->getMenu();
		$menu 		= $menus->getActive();

		if(isset($menu)) {
			if($menu->params->get('page_title', '')) {
				$title = $menu->params->get('page_title');
			} else {
				$title = $menu->title;
			}
		}

		//Include Site title
		$sitetitle = $title;
		if($config->get('sitename_pagetitles')==2) {
			$sitetitle = JText::sprintf('JPAGETITLE', $sitetitle, $app->get('sitename'));
		} elseif ($config->get('sitename_pagetitles')==1) {
			$sitetitle = JText::sprintf('JPAGETITLE', $app->get('sitename'), $sitetitle);
		}

		$doc->setTitle($sitetitle);

	}
}
