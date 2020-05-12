<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */

defined('_JEXEC') or die('Restricted access');

/**
* Jwpagefactory search plugins
*
* @since 1.0.4
*/
class PlgSearchJwpagefactory extends JPlugin
{

	protected $autoloadLanguage = true;

	protected $app;

	/**
	 * Determine areas searchable by this plugin.
	 *
	 * @return  array  An array of search areas.
	 *
	 */

	public function onContentSearchAreas()
	{
		static $areas = array(
			'jwpagefactory' => 'JW_PAGEFACTORY_SEARCH_AREAS'
		);

		return $areas;
	}

	/**
	 * Search content.
	 *
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   mixed   $areas     An array if the search is to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 *
	 */

	public function onContentSearch( $text, $phrase = '', $ordering = '', $areas = null )
	{
		$db 	= JFactory::getDbo();
		$limit 	= $this->params->def('search_limit', 50);
		$tag    = JFactory::getLanguage()->getTag();

		if (is_array($areas)) {
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas()))) {
				return array();
			}
		}

		$text = trim($text);

		if ($text == '') {
			return array();
		}

		JLoader::register('JwpagefactoryRouter', JPATH_SITE . '/components/com_jwpagefactory/route.php');

		switch ($phrase)
		{
			case 'exact':
			case 'all':
			case 'any':
			default:
				$text = $db->quote('%' . $db->escape($text, true) . '%', false);
				$wheres1 = array();
				$wheres1[] = 's.title LIKE ' . $text;
				$wheres1[] = 's.text LIKE ' . $text;
				$where = '((' . implode(') OR (', $wheres1) . ')) AND s.published = 1';
				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$order = 's.created_time ASC';
				break;

			case 'alpha':
				$order = 's.title ASC';
				break;

			case 'newest':
			case 'category':
			case 'popular':
			default:
				$order = 's.created_on DESC';
				break;
		}

		$query = $db->getQuery(true);

		if ( $limit > 0 ) {
			$query->clear();
			$query->select('s.id as id, s.title AS title, s.created_on as created, s.language as language');
			$query->from($db->quoteName('#__jwpagefactory', 's'));
			$query->where($db->quoteName('s.extension') . ' = '  . $db->quote('com_jwpagefactory'));
			$query->where($where);

			if ($this->app->isClient('site') && JLanguageMultilang::isEnabled())
			{
				$query->where('s.language in (' . $db->quote($tag) . ',' . $db->quote('*') . ')');
			}

			$query->order($order);

			$db->setQuery($query, 0, $limit);
		}
	
		try
		{
			$list = $db->loadObjectList();

			if (isset($list))
			{
				foreach ($list as $key => $item)
				{
					$menuItem = $this->getActiveMenu($item->id);
					// if(isset($menuItem->title) && $menuItem->title) {
					// 	$list[$key]->title = $menuItem->title;
					// }
					$itemId = '';
					if(isset($menuItem->id) && $menuItem->id) {
						$itemId = '&Itemid=' . $menuItem->id;
					}
					$list[$key]->href = JRoute::_('index.php?option=com_jwpagefactory&view=page&id='.$item->id.((($item->language != '*'))? '&lang='.$item->language:'') . $itemId, false);
				}
			}
		}
		catch (RuntimeException $e)
		{
			$list = array();
			$this->app->enqueueMessage(JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');
		}

		return $list;
	}

	public static function getActiveMenu($pageId) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('title, id'));
		$query->from($db->quoteName('#__menu'));
		$query->where($db->quoteName('link') . ' LIKE '. $db->quote('%option=com_jwpagefactory&view=page&id='. $pageId .'%'));
		$query->where($db->quoteName('published') . ' = '. $db->quote('1'));
		$db->setQuery($query);
		$item = $db->loadObject();

		return $item;
	}
}
