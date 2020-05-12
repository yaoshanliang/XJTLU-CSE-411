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

class KomentoStylesheet extends KomentoBase
{
	static $loaded;

	/**
	 * Allows caller to attach stylesheets of Komento on the page
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function attach()
	{
		static $loaded = null;

		if (is_null($loaded)) {
			$loaded = true;
		}

		return $loaded;
	}

	/**
	 * API to add necessary assets files into the head of the page
	 *
	 * @since	2.0.9
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function load($list, $type = 'js', $location = 'themes')
	{
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();

		$config = KT::getConfig();
		$kApp = KT::loadApplication();

		$files = explode(',', $list);

		// Get the absolute url to the assets folder
		$dir = JURI::root() . 'components/com_komento/assets';
		$pathdir = KOMENTO_ASSETS;

		// Get the current theme to use
		$theme = $config->get('layout_theme');
		$version = str_ireplace('.', '', KT::komentoVersion());

		if ($location != 'assets') {
			$dir = JURI::root() . 'components/com_komento/themes/' . $theme;
			$pathdir = KOMENTO_THEMES . '/' . $theme;
		}

		foreach ($files as $file) {

			if ($type == 'js') {
				$file .= '.js?' . $version;
			}

			if ($type == 'css') {
				$file .= '.css';
			}

			$path = '';

			if ($location == 'themes') {

				$checkOverride	= JPATH_ROOT . '/templates/' . $app->getTemplate() . '/html/com_komento/' . $type . '/' . $file;
				$checkComponent	= $kApp->getComponentThemePath() . '/' . $type . '/' . $file;
				$checkSelected	= KOMENTO_THEMES . '/' . $theme . '/' . $type . '/' . $file;
				$checkDefault	= KOMENTO_THEMES . '/kuro/' . $type . '/' . $file;

				$overridePath	= JURI::root() . 'templates/' . $app->getTemplate() . '/html/com_komento/' . $type . '/' . $file;
				$componentPath	= $kApp->getComponentThemeURI() . '/' . $type . '/' . $file;
				$selectedPath	= $dir . '/' . $type . '/' . $file;
				$defaultPath	= JURI::root() . 'components/com_komento/themes/kuro/' . $type . '/' . $file;

				// 1. Check if template override exists
				if (JFile::exists($checkOverride)) {
					$path = $overridePath;
					$pathdir = $checkOverride;

				} elseif (JFile::exists($checkSelected)) {

					// 2. Selected themes

					$path = $selectedPath;
					$pathdir = $checkSelected;
				} else {
					// 3. Default system theme
					$path = $defaultPath;
					$path = $checkDefault;
				}
			} else {
				$path = $dir . '/' . $type . '/' . $file;
				$pathdir = $pathdir . '/' . $type . '/' . $file;
			}

			if ($type == 'js') {
				$doc->addScript($path);
			}

			// Otherwise we assume that the user is trying to add css
			if ($type == 'css') {
				$doc->addStylesheet($path);
			}
		}
	}

	/**
	 * Allows caller to detect specific css files from site's template
	 * and load it into the headers if necessary.
	 *
	 * @param	string $fileName
	 */
	public static function addTemplateCss( $fileName )
	{
		$document		= JFactory::getDocument();
		$document->addStyleSheet( rtrim(JURI::root(), '/') . '/components/com_komento/assets/css/' . $fileName );

		$mainframe		= JFactory::getApplication();
		$templatePath	= JPATH_ROOT . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $mainframe->getTemplate() . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'com_komento' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . $fileName;

		if( JFile::exists($templatePath) )
		{
			$document->addStyleSheet( rtrim(JURI::root(), '/') . '/templates/' . $mainframe->getTemplate() . '/html/com_komento/assets/css/' . $fileName );

			return true;
		}

		return false;
	}

	/*
	 * Method for broswer detection
	 */
	public static function getBrowserUserAgent()
	{
		$browser = new stdClass;

		// set to lower case to avoid errors, check to see if http_user_agent is set
		$navigator_user_agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';

		// run through the main browser possibilities, assign them to the main $browser variable
		if (stristr($navigator_user_agent, "opera"))
		{
			$browser->userAgent = 'opera';
			$browser->dom = true;
		}
		elseif (stristr($navigator_user_agent, "msie 8"))
		{
			$browser->userAgent = 'msie8';
			$browser->dom = false;
		}
		elseif (stristr($navigator_user_agent, "msie 7"))
		{
			$browser->userAgent = 'msie7';
			$browser->dom = false;
		}
		elseif (stristr($navigator_user_agent, "msie 4"))
		{
			$browser->userAgent = 'msie4';
			$browser->dom = false;
		}
		elseif (stristr($navigator_user_agent, "msie"))
		{
			$browser->userAgent = 'msie';
			$browser->dom = true;
		}
		elseif ((stristr($navigator_user_agent, "konqueror")) || (stristr($navigator_user_agent, "safari")))
		{
			$browser->userAgent = 'safari';
			$browser->dom = true;
		}
		elseif (stristr($navigator_user_agent, "gecko"))
		{
			$browser->userAgent = 'mozilla';
			$browser->dom = true;
		}
		elseif (stristr($navigator_user_agent, "mozilla/4"))
		{
			$browser->userAgent = 'ns4';
			$browser->dom = false;
		}
		else
		{
			$browser->dom = false;
			$browser->userAgent = 'Unknown';
		}

		return $browser;
	}

	// Add canonical URL to satify Googlebot. Incase they think it's duplicated content.
	public static function addCanonicalURL( $extraFishes = array() )
	{
		if (empty( $extraFishes ))
		{
			return;
		}

		$juri		= JURI::getInstance();

		foreach( $extraFishes as $fish )
		{
			$juri->delVar( $fish );
		}

		$preferredURL	= $juri->toString();

		$document	= JFactory::getDocument();
		$document->addHeadLink( $preferredURL, 'canonical', 'rel');
	}

	public static function getBaseUrl()
	{
		static $url;

		if (isset($url)) return $url;

		if( KT::joomlaVersion() >= '1.6' )
		{
			$uri 		= JFactory::getURI();
			$language	= $uri->getVar( 'lang' , 'none' );
			$app		= JFactory::getApplication();
			$jconfig	= JFactory::getConfig();
			$router		= $app->getRouter();
			$url		= rtrim( JURI::root() , '/' ) . '/index.php?option=com_komento&lang=' . $language;

			if( $router->getMode() == JROUTER_MODE_SEF && JPluginHelper::isEnabled("system","languagefilter") )
			{
				$rewrite	= $jconfig->get('sef_rewrite');

				$base		= str_ireplace( JURI::root( true ) , '' , $uri->getPath() );
				$path		= $rewrite ? $base : JString::substr( $base , 10 );
				$path		= JString::trim( $path , '/' );
				$parts		= explode( '/' , $path );

				if( $parts )
				{
					// First segment will always be the language filter.
					$language	= reset( $parts );
				}
				else
				{
					$language	= 'none';
				}

				if( $rewrite )
				{
					$url		= rtrim( JURI::root() , '/' ) . '/' . $language . '/?option=com_komento';
					$language	= 'none';
				}
				else
				{
					$url		= rtrim( JURI::root() , '/' ) . '/index.php/' . $language . '/?option=com_komento';
				}
			}
		}
		else
		{

			$url		= rtrim( JURI::root() , '/' ) . '/index.php?option=com_komento';
		}

		$menu	= JFactory::getApplication()->getMenu();
		$item	= $menu->getActive();
		if( isset( $item->id) )
		{
			$url    .= '&Itemid=' . $item->id;
		}

		// Some SEF components tries to do a 301 redirect from non-www prefix to www prefix.
		// Need to sort them out here.
		$currentURL		= isset( $_SERVER[ 'HTTP_HOST' ] ) ? $_SERVER[ 'HTTP_HOST' ] : '';

		if( !empty( $currentURL ) )
		{
			// When the url contains www and the current accessed url does not contain www, fix it.
			if( stristr($currentURL , 'www' ) === false && stristr( $url , 'www') !== false )
			{
				$url	= str_ireplace( 'www.' , '' , $url );
			}

			// When the url does not contain www and the current accessed url contains www.
			if( stristr( $currentURL , 'www' ) !== false && stristr( $url , 'www') === false )
			{
				$url	= str_ireplace( '://' , '://www.' , $url );
			}
		}

		return $url;
	}
}
