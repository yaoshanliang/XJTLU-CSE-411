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

jimport('joomla.application.component.modelitem');


class JwpagefactoryModelPage extends JModelItem
{

	protected $_context = 'com_jwpagefactory.page';


	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		$pageId = $app->input->getInt('id');
		$this->setState('page.id', $pageId);

		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_jwpagefactory')) && (!$user->authorise('core.edit', 'com_jwpagefactory'))) {
			$this->setState('filter.published', 1);
		}

		$this->setState('filter.language', JLanguageMultilang::isEnabled());
	}

	public function getItem($pageId = null)
	{
		$pageId = (!empty($pageId)) ? $pageId : (int)$this->getState('page.id');

		if ($this->_item == null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$pageId])) {
			try {
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select('a.*')
					->from('#__jwpagefactory as a')
					->where('a.id = ' . (int)$pageId);

				$query->select('l.title AS language_title')
					->leftJoin($db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

				$query->select('ua.name AS author_name')
					->leftJoin('#__users AS ua ON ua.id = a.created_by');

				// Filter by published state.
				$published = $this->getState('filter.published');

				if (is_numeric($published)) {
					$query->where('a.published = ' . (int)$published);
				} elseif ($published === '') {
					$query->where('(a.published IN (0, 1))');
				}

				if ($this->getState('filter.language')) {
					$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
				}

				$db->setQuery($query);
				$data = $db->loadObject();
				if (empty($data)) {
					return JError::raiseError(404, JText::_('COM_JWPAGEFACTORY_ERROR_PAGE_NOT_FOUND'));
				}

				if ($access = $this->getState('filter.access')) {
					$data->access_view = true;
				} else {
					$user = JFactory::getUser();
					$groups = $user->getAuthorisedViewLevels();

					$data->access_view = in_array($data->access, $groups);
				}

				$this->_item[$pageId] = $data;
			} catch (Exception $e) {
				if ($e->getCode() == 404) {
					JError::raiseError(404, $e->getMessage());
				} else {
					$this->setError($e);
					$this->_item[$pageId] = false;
				}
			}
		}

		return $this->_item[$pageId];
	}

	// Get form
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_users.profile', 'profile', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Check for username compliance and parameter set
		$isUsernameCompliant = true;

		if ($this->loadFormData()->username) {
			$username = $this->loadFormData()->username;
			$isUsernameCompliant = !(preg_match('#[<>"\'%;()&\\\\]|\\.\\./#', $username) || strlen(utf8_decode($username)) < 2 || trim($username) != $username);
		}

		$this->setState('user.username.compliant', $isUsernameCompliant);

		if (!JComponentHelper::getParams('com_users')->get('change_login_name') && $isUsernameCompliant) {
			$form->setFieldAttribute('username', 'class', '');
			$form->setFieldAttribute('username', 'filter', '');
			$form->setFieldAttribute('username', 'description', 'COM_USERS_PROFILE_NOCHANGE_USERNAME_DESC');
			$form->setFieldAttribute('username', 'validate', '');
			$form->setFieldAttribute('username', 'message', '');
			$form->setFieldAttribute('username', 'readonly', 'true');
			$form->setFieldAttribute('username', 'required', 'false');
		}

		// When multilanguage is set, a user's default site language should also be a Content Language
		if (JLanguageMultilang::isEnabled()) {
			$form->setFieldAttribute('language', 'type', 'frontend_language', 'params');
		}

		// If the user needs to change their password, mark the password fields as required
		if (JFactory::getUser()->requireReset) {
			$form->setFieldAttribute('password1', 'required', 'true');
			$form->setFieldAttribute('password2', 'required', 'true');
		}

		return $form;
	}

	/**
	 * Increment the hit counter for the page.
	 *
	 * @param   integer $pk Optional primary key of the page to increment.
	 *
	 * @return  boolean  True if successful; false otherwise and internal error set.
	 */
	public function hit($pk = 0)
	{
		$pk = (!empty($pk)) ? $pk : (int)$this->getState('page.id');
		$table = JTable::getInstance('Page', 'JwpagefactoryTable');
		$table->load($pk);
		$table->hit($pk);

		return true;
	}

	public function getMySections()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id', 'title', 'section')));
		$query->from($db->quoteName('#__jwpagefactory_sections'));
		//$query->where($db->quoteName('profile_key') . ' LIKE '. $db->quote('\'custom.%\''));
		$query->order('id ASC');
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return json_encode($results);
	}

	public function deleteSection($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// delete all custom keys for user 1001.
		$conditions = array(
			$db->quoteName('id') . ' = ' . $id
		);

		$query->delete($db->quoteName('#__jwpagefactory_sections'));
		$query->where($conditions);

		$db->setQuery($query);

		return $db->execute();
	}

	public function saveSection($title, $section)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$obj = new stdClass();
		$obj->title = $title;
		$obj->section = $section;
		$obj->created = JFactory::getDate()->toSql();
		$obj->created_by = $user->get('id');

		$db->insertObject('#__jwpagefactory_sections', $obj);

		return $db->insertid();
	}

	public function getMyAddons()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id', 'title', 'code')));
		$query->from($db->quoteName('#__jwpagefactory_addons'));

		$query->order('id ASC');
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return json_encode($results);
	}

	public function saveAddon($title, $addon)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$obj = new stdClass();
		$obj->title = $title;
		$obj->code = $addon;
		$obj->created = JFactory::getDate()->toSql();
		$obj->created_by = $user->get('id');

		$db->insertObject('#__jwpagefactory_addons', $obj);

		return $db->insertid();
	}

	public function deleteAddon($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// delete all custom keys for user 1001.
		$conditions = array(
			$db->quoteName('id') . ' = ' . $id
		);

		$query->delete($db->quoteName('#__jwpagefactory_addons'));
		$query->where($conditions);
		$db->setQuery($query);

		return $db->execute();
	}

	public function getMyPages()
	{
		$user = JFactory::getUser();
		$authorised = $user->authorise('core.create', 'com_jwpagefactory') || (count((array)$user->getAuthorisedCategories('com_jwpagefactory', 'core.create')));
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id', 'a.title', 'a.published', 'a.catid', 'a.created_on', 'a.language')));
		$query->from($db->quoteName('#__jwpagefactory', 'a'));
		$query->select('c.title AS category_title, c.alias AS category_alias')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');
		if (!$authorised) {
			$query->where($db->quoteName('a.created_by') . ' = ' . (int)$user->id);
		}
		$query->where($db->quoteName('a.published') . ' != ' . -2);
		$query->where($db->quoteName('a.extension') . ' = ' . $db->quote('com_jwpagefactory'));
		$query->order('ordering ASC');
		$db->setQuery($query);

		$categories = array();
		$categories['all'] = array(
			'alias' => 'all',
			'title' => 'Select Category'
		);
		$items = $db->loadObjectList();
		$siteApp = JApplication::getInstance('site');
		$siteRouter = $siteApp->getRouter();

		if (is_array($items) && count($items)) {
			foreach ($items as $key => &$item) {
				if (!isset($item->category_alias)) {
					$item->category_alias = 'all';
					$item->category_title = JText::_('COM_JWPAGEFACTORY_SELECT_CATEGORY');
				}
				$item->created_date = JHtml::_('date', $item->created_on, 'DATE_FORMAT_LC3');
				// get menu id
				$Itemid = $this->getMenuId($item->id);
				$item->link = 'index.php?option=com_jwpagefactory&task=page.edit&id=' . $item->id;
				// Get item language code
				$lang_code = (isset($item->language) && $item->language && explode('-', $item->language)[0]) ? explode('-', $item->language)[0] : '';
				// check language filter plugin is enable or not
				$enable_lang_filter = JPluginHelper::getPlugin('system', 'languagefilter');
				// get joomla config
				$conf = JFactory::getConfig();

				// Preview URL
				$preview = 'index.php?option=com_jwpagefactory&view=page&id=' . $item->id . $Itemid;
				$sefURI = str_replace('/administrator', '', $siteRouter->build($preview));
				if ($lang_code && $lang_code !== '*' && $enable_lang_filter && $conf->get('sef')) {
					$sefURI = str_replace('/index.php/', '/index.php/' . $lang_code . '/', $sefURI);
				} elseif ($lang_code && $lang_code !== '*') {
					$sefURI = $sefURI . '&lang=' . $lang_code;
				}
				$item->preview = $sefURI;

				// Frontend Editing URL
				$front_link = 'index.php?option=com_jwpagefactory&view=form&tmpl=component&layout=edit&id=' . $item->id . $Itemid;
				$sefURI = str_replace('/administrator', '', $siteRouter->build($front_link));
				if ($lang_code && $lang_code !== '*' && $enable_lang_filter && $conf->get('sef')) {
					$sefURI = str_replace('/index.php/', '/index.php/' . $lang_code . '/', $sefURI);
				} elseif ($lang_code && $lang_code !== '*') {
					$sefURI = $sefURI . '&lang=' . $lang_code;
				}
				$item->frontend_edit = $sefURI;

				if (isset($item->category_title) && $item->category_title) {
					$categories[$item->category_alias] = array(
						'alias' => $item->category_alias,
						'title' => $item->category_title
					);
				}
			}

			$newCcategories = array();
			foreach ($categories as $category) {
				$newCcategories[] = $category;
			}

			echo json_encode(array(
				'status' => true,
				'pages' => $items,
				'categories' => $newCcategories
			));
			die();

		}

		echo json_encode(array(
			'status' => false
		));
		die();
	}

	public function getMenuId($pageId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__menu'));
		$query->where($db->quoteName('link') . ' LIKE ' . $db->quote('%option=com_jwpagefactory&view=page&id=' . $pageId . '%'));
		$query->where($db->quoteName('published') . ' = ' . $db->quote('1'));
		$db->setQuery($query);
		$result = $db->loadResult();

		if ($result) {
			return '&Itemid=' . $result;
		}

		return '';
	}

}