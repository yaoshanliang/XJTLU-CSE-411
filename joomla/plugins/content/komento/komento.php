<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

/************************************************************************************************
DEVELOPER'S NOTE - To integrate com_komento to yours, simply refer to the follwing samples:
*************************************************************************************************

2 LINES SIMPLE VERSION:

require_once(JPATH_ROOT . '/components/com_komento/bootstrap.php');
KT::commentify('com_yourextension', $content, array( 'params' => ''));

************************************************************************************************/

jimport('joomla.plugin.plugin');

class plgContentKomento extends JPlugin
{
	private $extension = null;

	/**
	 * Loads Komento's bootstrap codes
	 *
	 * @since	2.0.9
	 * @access	public
	 */
	private function loadBootstrap()
	{
		static $loaded = null;

		if (is_null($loaded)) {

			$file = JPATH_ROOT . '/components/com_komento/bootstrap.php';

			jimport('joomla.filesystem.file');

			// Check if komento exists
			if (!JFile::exists($file)) {
				$loaded = false;
			} else {

				require_once($file);

				$loaded = true;
			}
		}

		return $loaded;
	}

	/**
	 * com_redshop
	 */
	public function onAfterDisplayProduct( &$template_desc, $params = false, $data = 0)
	{
		if ($this->extension != 'com_redshop') {
			return;
		}

		return $this->execute( __FUNCTION__, null, $template_desc, $params, $data );
	}

	/**
	 * com_jshopping
	 */
	public function onBeforeDisplayProductView(&$view)
	{
		if ($this->extension != 'com_jshopping') {
			return;
		}

		$jshopConfig = JSFactory::getConfig();
		$product = $view->product;

		$contents = $this->execute(__FUNCTION__, 'jshopping_products', $product, $jshopConfig, '');

		$view->_tmp_product_html_before_review = $contents;
	}

	/**
	 * com_k2
	 */
	public function onK2CommentsBlock( &$item, &$params, $limitstart )
	{
		return $this->execute( __FUNCTION__, 'k2block', $item, $params, $limitstart );
	}

	public function onK2CommentsCounter( &$item, &$params, $limitstart )
	{
		$this->extension = 'com_k2';
		return $this->execute( __FUNCTION__, 'k2counter', $item, $params, $limitstart );
	}

	public function onK2BeforeDisplayContent(&$item, &$params, $limitstart)
	{
		$this->extension = 'com_k2';
		return $this->execute( __FUNCTION__, 'k2counter', $item, $params, $limitstart );
	}

	/**
	 * com_easyblog
	 */
	public function onDisplayComments( &$blog , &$articleParams )
	{
		return $this->execute( __FUNCTION__, null, $blog, $articleParams, 0 );
	}

	/**
	 * This trigger entry point is used for com_ohanah and com_ohanah's venue
	 * We are now use this trigger instead because of some issue in Ohanah integration
	 *
	 * @since 2.0.9
	 */
	public function onContentBeforeDisplay($context, &$article, &$params, $page = 0)
	{
		return $this->execute(__FUNCTION__, $context, $article, $params, $page);
	}

	public function onBeforeDisplayContent( &$article, &$articleParams, $limitstart, $page = 0 )
	{
		return $this->execute(__FUNCTION__, null, $article, $params, $page);
	}

	/**
	 * This trigger entry point is used for com_content, com_flexicontent, com_virtuemart
	 *
	 * @since 2.0.9
	 */
	public function onContentAfterDisplay($context, &$article, &$params, $page = 0)
	{
		return $this->execute(__FUNCTION__, $context, $article, $params, $page);
	}

	/**
	 * This trigger entry point is used for Ohanah Venue's
	 *
	 * @since 2.0.9
	 */
	public function onAfterDisplayContent(&$article, &$articleParams, $limitstart, $page = 0)
	{
		return $this->execute(__FUNCTION__, null, $article, $params, $page);
	}

	/**
	 * com_tz_portfolio
	 *
	 */
	public function onTZPortfolioCommentDisplay($context, &$article, $params)
	{
		return $this->execute(__FUNCTION__, $context, $article, $params);
	}

	/**
	 * com_jblance
	 *
	 */
	public function onJBlanceCommentDisplay($context, &$article, $params)
	{
		return $this->execute(__FUNCTION__, $context, $article, $params);
	}

	/**
	 * com_jdownloads
	 */
	public function onContentPrepare( $context, &$article, &$params, $page = 0 )
	{

		return $this->execute( __FUNCTION__, $context, $article, $params, $page );
	}

	public function onPrepareContent( &$article, &$params, $limitstart, $page = 0 )
	{
		return $this->execute( __FUNCTION__, null, $article, $params, $page );
	}

	/**
	 * Main execution code for Komento
	 *
	 * @since	3.1
	 * @access	public
	 */
	private function execute($eventTrigger, $context = 'none', &$article, &$params, $page = 0)
	{
		// Load bootstrap
		if (!$this->loadBootstrap()) {
			return;
		}

		// If unknown extension, try to get it from the REQUEST
		if (!$this->extension) {
			$this->extension = JFactory::getApplication()->input->getCmd('option');
		}

		// We cannot render hikashop on the description
		if ($this->extension == 'com_hikashop') {
			return;
		}

		// Fix flexicontent's mess as they are trying to reset the option=com_flexicontent to com_content.
		if (JRequest::getVar('isflexicontent')) {
			$this->extension = 'com_flexicontent';
		}

		// Ohanah Venue plugin
		if ($context == 'com_ohanah.venue') {
			$this->extension = 'com_ohanahvenue';
		}

		// @task: trigger onAfterEventTriggered
		$renderExtension = KT::onAfterEventTriggered(__CLASS__, $eventTrigger, $this->extension, $context, $article, $params);

		if (!$renderExtension) {
			return false;
		}

		// Passing in the data
		$options = array();
		$options['trigger']	= $eventTrigger;
		$options['context']	= $context;
		$options['params'] = $params;
		$options['page'] = $page;

		// Get the contents and return the html codes
		$contents = KT::commentify($this->extension, $article, $options);

		return $contents;
	}
}
