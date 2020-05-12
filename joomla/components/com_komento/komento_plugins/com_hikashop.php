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

class KomentoComHikashop extends KomentoExtension
{
	public $_item;
	public $_map = array(
		'id' => 'product_id',
		'title' => 'product_name',
		'hits' => 'viewed',
		'created_by' => 'created_by',
		'catid' => 'category_id',
		'permalink' => 'permalink'
		);

	public function __construct($component)
	{
		$this->addFile(JPATH_ROOT.'/administrator/components/com_hikashop/helpers/helper.php');

		parent::__construct($component);
	}

	public function onBeforeLoad( $eventTrigger, $context, &$article, &$params, &$page, &$options )
	{
		$this->_item = $article;

		return true;
	}

	/**
	 * Loads a product from Hikashop
	 *
	 * @since	3.0. 6
	 * @access	public
	 */
	public function load($cid)
	{
		static $instances = array();

		if (!isset($instances[$cid])) {

			$filters = array('a.product_id=' . $cid);
			$query = 'SELECT a.*, b.product_category_id, b.category_id, b.ordering FROM '.hikashop_table('product').' AS a LEFT JOIN '.hikashop_table('product_category').' AS b ON a.product_id = b.product_id WHERE '.implode(' AND ',$filters). ' LIMIT 1';

			$db = JFactory::getDBO();
			$db->setQuery($query);

			$this->_item = $db->loadObject();

			$this->_item->permalink = hikashop_contentLink('product&task=show&cid=' . $this->_item->product_id.'&name='.$this->_item->product_alias, $this->_item);

			// Since Hikashop does not store the creator, we need to map it ourselves by finding the first super admin on the site
			$admins = KT::getSAUsersIds();
			$this->_item->created_by = $admins[0];

			$instances[$cid] = $this->_item;
		}

		$this->_item = $instances[$cid];

		return $this;
	}

	public function getContentIds( $categories = '' )
	{
		$db		= KT::getDBO();
		$query = '';

		if( empty( $categories ) )
		{
			$query = 'SELECT `product_id` FROM ' . $db->nameQuote( '#__aceshop_product_to_category' ) . ' ORDER BY `product_id`';
		}
		else
		{
			if( is_array( $categories ) )
			{
				$categories = implode( ',', $categories );
			}

			$query = 'SELECT `product_id` FROM ' . $db->nameQuote( '#__aceshop_product_to_category' ) . ' WHERE `category_id` IN (' . $categories . ') ORDER BY `product_id`';
		}

		$db->setQuery( $query );
		return $db->loadResultArray();
	}

	public function getCategories()
	{
	}

	public function isListingView()
	{
		$state = (($this->input->get('view') == 'category') || ($this->input->get('route') == 'product/category'));

		return $state;
	}

	public function isEntryView()
	{
		$state = (($this->input->get('ctrl') == 'product') && (JRequest::getString('task') == 'show'));

		return $state;
	}

	public function onExecute(&$article, $html, $view, $options = array())
	{
		if ($view == 'listing') {
			return $html;
		}

		if ($view == 'entry') {
			return $html;
		}
	}

	public function onRollBack($eventTrigger, $context, &$article, &$params, &$page, &$options)
	{
		$article = $article->text;

		return true;
	}

	public function getContentPermalink()
	{
		$link = $this->prepareLink($this->_item->permalink);
		
		return $link;
	}
}
