<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(__DIR__ . '/abstract.php');

class KomentoComtzportfolioplus extends KomentoExtension
{
	public $_item;
	public $_map = array(
		'id' => 'id',
		'title' => 'title',
		'hits' => 'hits',
		'created_by' => 'created_by'
		);

	public function __construct($component)
	{
		$this->addFile(JPATH_ROOT . '/components/com_tz_portfolio_plus/helpers/route.php');

		parent::__construct($component);
	}

	public function load($cid)
	{
		static $instances = array();

		if (!isset($instances[$cid])) {
			$db	= KT::db();
			$query	= 'SELECT a.id, a.title, a.alias, a.created_by, a.created_by_alias, a.hits,' //a.attribs
					. ' c.id, c.title AS category_title, c.alias AS category_alias, cm.catid,'
					. ' u.name AS author,'
					. ' parent.id AS parent_id, parent.alias AS parent_alias'
					. ' FROM ' . $db->nameQuote('#__tz_portfolio_plus_content') . ' AS a'
					. ' LEFT JOIN ' . $db->nameQuote('#__tz_portfolio_plus_content_category_map') . ' AS cm ON cm.contentid = a.id'
					. ' LEFT JOIN ' . $db->nameQuote('#__tz_portfolio_plus_categories') . ' AS c ON c.id = cm.catid'
					. ' LEFT JOIN ' . $db->nameQuote('#__users') . ' AS u ON u.id = a.created_by'
					. ' LEFT JOIN ' . $db->nameQuote('#__tz_portfolio_plus_categories') . ' AS parent ON parent.id = c.parent_id'
					. ' WHERE a.id = ' . $db->quote((int) $cid);
			$db->setQuery($query);

			if (!$result = $db->loadObject()) {
				return $this->onLoadArticleError($cid);
			}

			$instances[$cid] = $result;
		}

		$this->_item = $instances[$cid];

		return $this;
	}

	public function getContentIds($categories = '')
	{
		$db	= KT::db();
		$query = '';

		if (empty($categories)) {
			$query = 'SELECT `id` FROM ' . $db->nameQuote('#__tz_portfolio_plus_content') . ' ORDER BY `id`';
		} else {
			if (is_array($categories)) {
				$categories = implode(',', $categories);
			}

			$query = 'SELECT a.id FROM ' . $db->nameQuote('#__tz_portfolio_plus_content') . ' AS a'
					. ' LEFT JOIN ' . $db->nameQuote('#__tz_portfolio_plus_content_category_map') . ' AS cm ON cm.contentid = a.id'
					. ' WHERE cm.catid IN (' . $categories . ') ORDER BY a.id';
		}

		$db->setQuery($query);
		return $db->loadResultArray();
	}

	public function getCategories()
	{
		$db	= KT::db();
		$query	= 'SELECT a.id, a.title, a.level, a.parent_id'
				. ' FROM `#__tz_portfolio_plus_categories` AS a'
				. ' WHERE a.extension = ' . $db->quote('com_tz_portfolio_plus')
				. ' AND a.parent_id > 0'
				. ' ORDER BY a.lft';

		$db->setQuery($query);
		$categories = $db->loadObjectList();

		foreach ($categories as &$row) {
			$repeat = ($row->level - 1 >= 0) ? $row->level - 1 : 0;
			$row->treename = str_repeat('.&#160;&#160;&#160;', $repeat) . ($row->level - 1 > 0 ? '|_&#160;' : '') . $row->title;
		}

		return $categories;
	}

	public function isListingView()
	{
		$views = array('featured', 'category', 'categories', 'archive', 'frontpage');

		return in_array($this->input->get('view'), $views);
	}

	public function isEntryView()
	{
		return $this->input->get('view') == 'article';
	}

	public function onExecute(&$article, $html, $view, $options = array())
	{
		if ($view == 'listing') {
			$config = KT::config('com_tz_portfolio');

			if ($config->get('layout_frontpage_readmore_button') != 'joomla') {
				$article->readmore = false;
			} else {
				if ($config->get('layout_frontpage_readmore') == 2) {
					$article->readmore = true;
				}

				if ($config->get('layout_frontpage_readmore') == 0) {
					$article->readmore = false;
				}
			}

			return $html;
		}

		if ($view == 'entry') {

			return $html;
		}
	}

	public function getEventTrigger()
	{
		$entryTrigger = 'onContentAfterDisplay';

		return $entryTrigger;
	}

	public function getContext()
	{
		// Entry view's context is definitely com_content.article
		if ($this->isEntryView()) {
			return 'com_tz_portfolio_plus.article';
		}

		// Due to a change in the latest Joomla (Joomla 2.5.14 and Joomla 3.1.5)
		// The context in listing pages is no longer com_content.article
		// Return array with all 3 context here to support prior Joomla version, as well as the latest Joomla version
		if ($this->isListingView()) {
			return array('com_tz_portfolio_plus.article', 'com_tz_portfolio_plus.category', 'com_tz_portfolio_plus.featured');
		}

		return false;
	}

	public function getAuthorName()
	{
		return $this->_item->created_by_alias ? $this->_item->created_by_alias : $this->_item->author;
	}

	public function getContentPermalink()
	{
		$slug = $this->_item->alias ? ($this->_item->id.':'.$this->_item->alias) : $this->_item->id;
		$catslug = $this->_item->category_alias ? ($this->_item->catid.':'.$this->_item->category_alias) : $this->_item->catid;
		$parent_slug = $this->_item->category_alias ? ($this->_item->parent_id.':'.$this->_item->parent_alias) : $this->_item->parent_id;

		$link = TZ_Portfolio_PlusHelperRoute::getArticleRoute($slug, $catslug);

		$link = $this->prepareLink($link);

		return $link;
	}

	public function onBeforeLoad($eventTrigger, $context, &$article, &$params, &$page, &$options)
	{
		if ($this->isEntryView()) {
			$config = KT::config('com_tz_portfolio_plus');

			if ($config->get('pagebreak_load') == 'all' || JRequest::getInt('showall', 0) == 1) {
				return true;
			}

			$regex = '#<hr(.*)class="system-pagebreak"(.*)\/>#iU';

			$matches = array();
			$count = 0;

			preg_match_all($regex, $article->introtext, $matches, PREG_SET_ORDER);
			$count += count($matches);

			preg_match_all($regex, $article->fulltext, $matches, PREG_SET_ORDER);
			$count += count($matches);

			preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
			$count += count($matches);

			if ($count === 0) {
				return true;
			} else {
				if ($config->get('pagebreak_load') == 'first' && $page == 0) {
					return true;
				}

				if ($config->get('pagebreak_load') == 'last' && $count == $page) {
					return true;
				}

				return false;
			}
		} else {
			return true;
		}
	}
}
