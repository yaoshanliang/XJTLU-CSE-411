<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

// Base this model on the backend version.
require_once JPATH_ADMINISTRATOR . '/components/com_jwpagefactory/models/page.php';

if (!class_exists('JwpagefactoryHelperSite')) {
	require_once JPATH_ROOT . '/components/com_jwpagefactory/helpers/helper.php';
}

/**
 * Content Component Article Model
 *
 * @since  1.5
 */
class JwpagefactoryModelForm extends JwpagefactoryModelPage
{
	protected $_context = 'com_jwpagefactory.page';
	protected $_item = array();

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

	public function getForm($data = array(), $loadData = true)
	{
		return parent::getForm();
	}

	public function save($data)
	{
		$attribs = array();

		if (isset($data['meta_description']) && $data['meta_description']) {
			$attribs['meta_description'] = $data['meta_description'];
		}

		if (isset($data['meta_keywords']) && $data['meta_keywords']) {
			$attribs['meta_keywords'] = $data['meta_keywords'];
		}

		$data['attribs'] = json_encode($attribs);

		return parent::save($data);
	}

	public function getItem($pageId = null)
	{
		$user = JFactory::getUser();

		$pageId = (!empty($pageId)) ? $pageId : (int)$this->getState('page.id');

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

				if (isset($data->attribs)) {
					$attribs = json_decode($data->attribs);
				} else {
					$attribs = new stdClass;
				}

				$data->meta_description = (isset($attribs->meta_description) && $attribs->meta_description) ? $attribs->meta_description : '';
				$data->meta_keywords = (isset($attribs->meta_keywords) && $attribs->meta_keywords) ? $attribs->meta_keywords : '';

				$menu_id = (isset($attribs->menu_id) && $attribs->menu_id) ? $attribs->menu_id : 0;
				$menu = $this->getMenuByPageId($data->id);
				$data->menuid = (isset($menu->id) && $menu->id) ? $menu->id : 0;
				$data->menutitle = (isset($menu->title) && $menu->title) ? $menu->title : '';
				$data->menualias = (isset($menu->alias) && $menu->alias) ? $menu->alias : '';
				$data->menutype = (isset($menu->menutype) && $menu->menutype) ? $menu->menutype : '';
				$data->menuparent_id = (isset($menu->parent_id) && $menu->parent_id) ? $menu->parent_id : 0;
				$data->menuordering = (isset($menu->id) && $menu->id) ? $menu->id : -2;

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

	public function getMenuByPageId($pageId = 0)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from('#__menu as a');
		$query->where('a.link = ' . $db->quote('index.php?option=com_jwpagefactory&view=page&id=' . $pageId));
		$query->where('a.client_id = 0');
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function getMenuById($menuId = 0)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from('#__menu as a');
		$query->where('a.id = ' . $menuId);
		$query->where('a.client_id = 0');
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function getMenuByAlias($alias, $menuId = 0)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.id', 'a.title', 'a.alias', 'a.menutype', 'a.parent_id', 'a.component_id'));
		$query->from('#__menu as a');
		$query->where('a.alias = ' . $db->quote($alias));
		if ($menuId) {
			$query->where('a.id != ' . (int)$menuId);
		}
		$query->where('a.client_id = 0');
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function createNewPage($title)
	{
		$user = JFactory::getUser();
		$date = JFactory::getDate();
		$db = $this->getDbo();
		$page = new stdClass();
		$page->title = $title;
		$page->text = '[]';
		$page->extension = 'com_jwpagefactory';
		$page->extension_view = 'page';
		$page->published = 1;
		$page->created_by = (int)$user->id;
		$page->created_on = $date->toSql();
		$page->language = '*';
		$page->access = 1;
		$db->insertObject('#__jwpagefactory', $page);

		return $db->insertid();
	}

	public function deletePage($id = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$conditions = array(
			$db->quoteName('id') . ' = ' . $id
		);
		$query->delete($db->quoteName('#__jwpagefactory'));
		$query->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
		return $result;
	}

	public function getPageItem($id = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('extension', 'extension_view', 'view_id', 'catid'));
		$query->from($db->quoteName('#__jwpagefactory'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));
		$db->setQuery($query);
		$result = $db->loadObject();

		if (count((array)$result)) {
			return $result;
		}

		return false;
	}

	public function addArticleFullText($id, $data)
	{
		$article = new stdClass();
		$article->id = $id;
		$article->fulltext = JwpagefactoryHelperSite::getPrettyText($data);
		$result = JFactory::getDbo()->updateObject('#__content', $article, 'id');
	}

}
