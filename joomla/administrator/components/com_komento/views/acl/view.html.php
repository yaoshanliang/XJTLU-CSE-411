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
defined('_JEXEC') or die('Unauthorized Access');

KT::import('admin:/views/views');

class KomentoViewAcl extends KomentoAdminView
{
	/**
	 * Acl View
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function display($tpl = null)
	{
		// Check for access
		$this->checkAccess('komento.manage.acl');

		$this->heading('COM_KOMENTO_SETTINGS_HEADING_ACL');
		$this->registerToolbar();

		$usergroups = KT::getUsergroups();

		$this->set('usergroups', $usergroups);

		parent::display('acl/default');
	}

	/**
	 * ACL form
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function form()
	{
		if (!$this->my->authorise('komento.manage.acl', 'com_komento')) {
			$this->app->redirect('index.php', JText::_('JERROR_ALERTNOAUTHOR'), 'error');
			$this->app->close();
		}

		$this->registerToolbar();

		$id	= $this->app->getUserStateFromRequest('com_komento.acl.id', 'id', '0');
		$type = $this->input->get('type', 'usergroup');

		$usergroup	= '';

		if ($type == 'usergroup') {
			$usergroup = KT::getUsergroupById($id);
		}

		$this->heading(JText::sprintf('COM_KOMENTO_SETTINGS_ACL_HEADING', $usergroup), 'COM_KOMENTO_SETTINGS_ACL_HEADING_DESC');

		$model = KT::model('acl', true);
		$model->updateUserGroups();

		$rulesets = $model->getData($type, $id);
		$tabs = array();

		// For now we will assume that id 1 is the public group
		$guestGroup = JComponentHelper::getParams('com_users')->get('guest_usergroup');

		if ($rulesets) {
			foreach ($rulesets as $key => $value) {
				$tab = new stdClass();
				$tab->title = JText::_('COM_KOMENTO_ACL_TAB_' . strtoupper($key));
				$tab->id = str_ireplace(array('.', ' ', '_'), '-', strtolower($key));

				$tabs[] = $tab;
			}

			// check for the public and guest user group
			if ($guestGroup == $id || $id == 1) {

				// Ensure that shouldn't render the like comment in ACL rule
				if (isset($rulesets->basic->like_comment)) {
					unset($rulesets->basic->like_comment);
				}
			}
		}

		$current = $this->input->get('current', 'basic', 'word');

		$this->set('current', $current);
		$this->set('rulesets', $rulesets);
		$this->set('id', $id);
		$this->set('type', $type);
		$this->set('usergroup', $usergroup);
		$this->set('tabs', $tabs);

		parent::display('acl/form');
	}

	/**
	 * Display action toolbar
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function registerToolbar()
	{
		$id = $this->input->get('id', 0, 'int');

		if ($id == '') {
			JToolBarHelper::title(JText::_('COM_KOMENTO_ACL'), 'acl');
		} else {
			JToolBarHelper::title(JText::_('COM_KOMENTO_ACL'));
		}

		if ($this->getLayout() == 'form') {
			JToolBarHelper::divider();
			JToolBarHelper::apply('apply');
			JToolBarHelper::save();
			JToolBarHelper::divider();
			JToolBarHelper::cancel();
		}
	}
}