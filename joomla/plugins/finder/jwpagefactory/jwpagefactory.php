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

JLoader::register('FinderIndexerAdapter', JPATH_ADMINISTRATOR . '/components/com_finder/helpers/indexer/adapter.php');

if (!class_exists('JwpagefactoryHelperSite')) {
	require_once JPATH_ROOT . '/components/com_jwpagefactory/helpers/helper.php';
}

class PlgFinderJwpagefactory extends FinderIndexerAdapter
{
	/**
	 * The plugin identifier.
	 */
	protected $context = 'Jwpagefactory';

	/**
	 * The extension name.
	 */
	protected $extension = 'com_jwpagefactory';

	/**
	 * The sublayout to use when rendering the results.
	 */
	protected $layout = 'page';

	/**
	 * The type of content that the adapter indexes.
	 */
	protected $type_title = 'Page';

	/**
	 * The table name.
	 */
	protected $table = '#__jwpagefactory';

	/**
	 * The field the published state is stored in.
	 */
	protected $state_field = 'published';

	/**
	 * Load the language file on instantiation.
	 */
	protected $autoloadLanguage = true;

	/**
	 * Method to remove the link information for items that have been deleted.
	 */
	public function onFinderAfterDelete($context, $table)
	{
		if ($context === 'com_jwpagefactory.page') {
			$id = $table->id;
		} elseif ($context === 'com_finder.index') {
			$id = $table->link_id;
		} else {
			return true;
		}

		// Remove the items.
		return $this->remove($id);
	}

	/**
	 * Method to determine if the access level of an item changed.
	 */
	public function onFinderAfterSave($context, $row, $isNew)
	{
		if ($context === 'com_jwpagefactory.page') {
			if (!$isNew && $this->old_access != $row->access) {
				$this->itemAccessChange($row);
			}

			$this->reindex($row->id);
		}

		return true;
	}

	/**
	 * Method to reindex the link information for an item that has been saved.
	 * This event is fired before the data is actually saved so we are going
	 * to queue the item to be indexed later.
	 */
	public function onFinderBeforeSave($context, $row, $isNew)
	{
		if ($context === 'com_jwpagefactory.page') {
			if (!$isNew) {
				$this->checkItemAccess($row);
			}
		}

		return true;
	}

	/**
	 * Method to update the link information for items that have been changed
	 * from outside the edit screen. This is fired when the item is published,
	 * unpublished, archived, or unarchived from the list view.
	 */
	public function onFinderChangeState($context, $pks, $value)
	{
		if ($context === 'com_jwpagefactory.page') {
			$this->itemStateChange($pks, $value);
		}

		if ($context === 'com_plugins.plugin' && $value === 0) {
			$this->pluginDisable($pks);
		}
	}

	/**
	 * Method to index an item. The item must be a FinderIndexerResult object.
	 */
	protected function index(FinderIndexerResult $item, $format = 'html')
	{
		// Check if the extension is enabled
		if (JComponentHelper::isEnabled($this->extension) === false) {
			return;
		}

		$menuItem = self::getActiveMenu($item->id);
		$item->setLanguage();
		$item->url = $this->getUrl($item->id, $this->extension, $this->layout);
		$item->body = JwpagefactoryHelperSite::getPrettyText($item->body);

		$link = 'index.php?option=com_jwpagefactory&view=page&id=' . $item->id;

		if ($item->language && $item->language !== '*' && JLanguageMultilang::isEnabled()) {
			$link .= '&lang=' . $item->language;
		}

		if (isset($menuItem->id) && $menuItem->id) {
			$link .= '&Itemid=' . $menuItem->id;
		}

		$item->route = $link;

		$item->path = $item->route;

		if (isset($menuItem->title) && $menuItem->title) {
			$item->title = $menuItem->title;
		}

		// Handle the page author data.
		$item->addInstruction(FinderIndexer::META_CONTEXT, 'user');

		// Add the type taxonomy data.
		$item->addTaxonomy('Type', 'Page');

		// Add the language taxonomy data.
		$item->addTaxonomy('Language', $item->language);

		// Index the item.
		$this->indexer->index($item);
	}

	/**
	 * Method to setup the indexer to be run.
	 */
	protected function setup()
	{
		JLoader::register('JwpagefactoryRouter', JPATH_SITE . '/components/com_jwpagefactory/route.php');

		return true;
	}

	/**
	 * Method to get the SQL query used to retrieve the list of page items.
	 */
	protected function getListQuery($query = null)
	{
		$db = JFactory::getDbo();

		// Check if we can use the supplied SQL query.
		$query = $query instanceof JDatabaseQuery ? $query : $db->getQuery(true)
			->select('a.id, a.view_id, a.title AS title, a.text AS body, a.created_on AS start_date')
			->select('a.created_by, a.modified, a.modified_by, a.language')
			->select('a.access, a.catid, a.extension, a.extension_view, a.published AS state, a.ordering')
			->select('u.name')
			->from('#__jwpagefactory AS a')
			->join('LEFT', '#__users AS u ON u.id = a.created_by')
			->where($db->quoteName('a.extension') . ' = ' . $db->quote('com_jwpagefactory'));


		return $query;
	}

	public static function getActiveMenu($pageId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('title, id'));
		$query->from($db->quoteName('#__menu'));
		$query->where($db->quoteName('link') . ' LIKE ' . $db->quote('%option=com_jwpagefactory&view=page&id=' . $pageId . '%'));
		$query->where($db->quoteName('published') . ' = ' . $db->quote('1'));
		$db->setQuery($query);
		$item = $db->loadObject();

		return $item;
	}
}
