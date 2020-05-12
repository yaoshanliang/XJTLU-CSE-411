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

// Include bootstrap file
if (!defined('KOMENTO_CLI')) {
	require_once(JPATH_ROOT . '/components/com_komento/bootstrap.php');
}

require_once(__DIR__ . '/dependencies.php');

class KT
{
	public static $package = 'paid';

	public static $component;
	public static $application;

	private static $messages = array();
	static private $views = array();
	static private $scripts = array();

	/**
	 * Allows caller to store scripts that needs to be added on the page
	 *
	 * @since	3.1.3
	 * @access	public
	 */
	public static function addScript($contents)
	{
		self::$scripts[] = $contents;
	}

	/**
	 * Allows caller to retrieve scripts that needs to be added on the page
	 *
	 * @since	3.1.3
	 * @access	public
	 */
	public static function getScripts()
	{
		if (empty(self::$scripts)) {
			return false;
		}
		
		return implode(' ', self::$scripts);
	}

	public static function getPackage()
	{
		return self::$package;
	}

	/**
	 * Retrieves Joomla's config
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function jconfig()
	{
		return JFactory::getConfig();
	}

	/**
	 * Retrieves the base URL of the site
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function getBaseUrl()
	{
		$app = JFactory::getApplication();
		$config = KT::jconfig();
		$uri = JFactory::getURI();
		$language = $uri->getVar('lang', 'none');
		$router = $app->getRouter();
		$baseUrl = rtrim(JURI::base(), '/') . '/index.php?option=com_komento&lang=' . $language;

		$itemId = JRequest::getVar('Itemid', '');

		if ($itemId) {
			$itemId = '&Itemid=' . $itemId;
		}

		if ($router->getMode() == JROUTER_MODE_SEF && JPluginHelper::isEnabled('system', 'languagefilter')) {

			$sefs = JLanguageHelper::getLanguages('sef');
			$lang_codes = JLanguageHelper::getLanguages('lang_code');

			$plugin = JPluginHelper::getPlugin('system', 'languagefilter');
			$params = new JRegistry();
			$params->loadString(empty($plugin) ? '' : $plugin->params);
			$removeLangCode = is_null($params) ? 'null' : $params->get('remove_default_prefix', 'null');

			$rewrite = $config->get('sef_rewrite');

			if ($removeLangCode) {

				$defaultLang = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
				$currentLang = $app->input->cookie->getString(JApplicationHelper::getHash('language'), $defaultLang);

				$defaultSefLang = $lang_codes[$defaultLang]->sef;
				$currentSefLang = $lang_codes[$currentLang]->sef;

				if ($defaultSefLang == $currentSefLang) {
					$language = '';
				} else {
					$language = $currentSefLang;
				}

			} else {

				$base = str_ireplace(JURI::root(true), '', $uri->getPath());
				$path = $rewrite ? $base : JString::substr($base , 10);
				$path = trim($path , '/' );
				$parts = explode( '/' , $path );

				if ($parts) {
					// First segment will always be the language filter.
					$language = reset($parts);
				} else {
					$language = 'none';
				}
			}
			
			if ($language) {
				$language .= '/';
			}

			if ($rewrite) {
				$baseUrl = rtrim(JURI::base(), '/') . '/' . $language . '?option=com_komento';
			} else {
				$baseUrl = rtrim(JURI::base(), '/') . '/index.php/' . $language . '?option=com_komento';
			}
		}

		return $baseUrl . $itemId;
	}

	/**
	 * Initializes Komento by rendering the necessary scripts and css
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function initialize($location = 'site')
	{
		static $loaded = array();

		if (!isset($loaded[$location])) {

			$app = JFactory::getApplication();
			$config = KT::config();

			$location = $app->isAdmin() ? 'admin' : 'site';

			$theme = $location == 'admin' ? 'default' : strtolower($config->get('layout_theme'));

			// Run initialization codes for javascript side of things.
			$input = $app->input;

			// Only for Komento
			$kt = $input->get('option', '', 'default');

			// Compile scripts
			if ($kt == 'com_komento' && $input->get('compile', false, 'bool') != false && KT::isSiteAdmin()) {

				// Determines if we should minify the output.
				$minify = $input->get('minify', false, 'bool');

				$compiler = KT::compiler();
				$result = array();

				// Compile with jquery.komento.js
				$result['standard'] = $compiler->compile($location, $minify);

				header('Content-type: text/x-json; UTF-8');
				echo json_encode($result);
				exit;
			}

			// Compile stylesheet
			if ($input->get('compileCss', false, 'bool') != false && KT::isSiteAdmin()) {
				$stylesheet = KT::stylesheet($location, $theme);
				$result = $stylesheet->build('full');

				header('Content-type: text/x-json; UTF-8');
				echo json_encode($result);
				exit;
			}

			// Attach the scripts
			$scripts = KT::scripts();
			$scripts->attach();

			// Attach css files
			$stylesheet = KT::stylesheet($location, $theme);
			$stylesheet->attach();

			$loaded[$location] = true;
		}

		return $loaded[$location];
	}

	/**
	 * Includes a file given a particular namespace in POSIX format.
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string	$file	Eg: admin:/includes/model will include /administrator/components/com_komento/includes/model.php
	 * @return	boolean			True on success false otherwise
	 */
	public static function import($namespace)
	{
		static $locations	= array();

		if (!isset($locations[$namespace])) {
			// Explode the parts to know exactly what to lookup for
			$parts = explode(':', $namespace);

			// Non POSIX standard.
			if (count($parts) <= 1) {
				return false;
			}

			$base = $parts[0];

			switch($base) {
				case 'admin':
					$basePath = KOMENTO_ADMIN_ROOT;
				break;
				case 'themes':
					$basePath = KOMENTO_THEMES;
				break;
				case 'site':
				default:
					$basePath = KOMENTO_ROOT;
				break;
			}

			// Replace / with proper directory structure.
			$path = str_ireplace('/', DIRECTORY_SEPARATOR, $parts[1]);

			// Get the absolute path now.
			$path = $basePath . $path . '.php';

			// Include the file now.
			include_once($path);

			$locations[$namespace] = true;
		}

		return true;
	}



	/**
	 * Loads a library dynamically
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function __callStatic($method, $arguments)
	{
		$file = dirname(__FILE__) . '/' . strtolower($method) . '/' . strtolower($method) . '.php';

		require_once($file);

		$class = 'Komento' . ucfirst($method);

		if (count($arguments) == 1) {
			$arguments = $arguments[0];
		}

		$obj = null;

		if (!$arguments) {
			$obj = new $class();
		} else {
			$obj = new $class($arguments);
		}

		return $obj;
	}

	/**
	 * Renders the stylesheet library
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function stylesheet($location, $name=null, $useOverride=false)
	{
		return KT::get('Stylesheet', $location, $name, $useOverride);
	}

	// public static function stylesheet($location, $name=null, $useOverride=false)
	// {
	// 	require_once(__DIR__ . '/stylesheet/stylesheet.php');

	// 	$lib = new KomentoStylesheet($location, $name);

	// 	return $lib;
	// }

	/**
	 * Loads a library
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function load($library)
	{
		// We do not need to use JString here because files are not utf-8 anyway.
		$library = strtolower($library);
		$obj = false;

		$path = __DIR__ . '/' . $library . '/' . $library . '.php';

		include_once($path);
	}

	/**
	 * Loads the default languages for Komento
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function loadLanguage($path = JPATH_ROOT)
	{
		static $loaded = array();

		if (!isset($loaded[$path])) {
			$lang = JFactory::getLanguage();

			// Load site's default language file.
			$lang->load('com_komento', $path);

			$loaded[$path] = true;
		}

		return $loaded[$path];
	}

	/**
	 * This is a simple wrapper method to access a particular library in EasySocial. This method will always
	 * instantiate a new class based on the given class name.
	 *
	 * @param	string	$item		Defines what item this method should load
	 **/
	public static function get($lib = '')
	{
		// Try to load up the library
		self::load($lib);

		$class = 'Komento' . ucfirst($lib);

		$args = func_get_args();

		// Remove the first argument because we know the first argument is always the library.
		if (isset($args[0])) {
			unset($args[0]);
		}

		return KT::factory($class, $args);
	}

	/**
	 * Creates a new object given the class.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function factory( $class , $args = array() )
	{
		// Reset the indexes
		$args 		= array_values( $args );
		$numArgs	= count($args);

		// It's too bad that we have to write these cods but it's much faster compared to call_user_func_array
		if($numArgs < 1)
		{
			return new $class();
		}

		if($numArgs === 1)
		{
			return new $class($args[0]);
		}

		if($numArgs === 2)
		{
			return new $class($args[0], $args[1]);
		}

		if($numArgs === 3 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] );
		}

		if($numArgs === 4 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] );
		}

		if($numArgs === 5 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] );
		}

		if($numArgs === 6 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] , $args[ 5 ] );
		}

		if($numArgs === 7 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] , $args[ 5 ] , $args[ 6 ] );
		}

		if($numArgs === 8 )
		{
			return new $class($args[0], $args[1] , $args[ 2 ] , $args[ 3 ] , $args[ 4 ] , $args[ 5 ] , $args[ 6 ] , $args[ 7 ]);
		}

		return call_user_func_array($fn, $args);
	}

	/**
	 * Retrieve specific helper objects.
	 *
	 * @param	string	$helper	The helper class. Class name should be the same name as the file. e.g KomentoXXXHelper
	 * @return	object	Helper object.
	 **/
	public static function getHelper($name)
	{
		static $helpers	= array();

		if( empty( $helpers[ $name ] ) )
		{
			$file	= KOMENTO_HELPERS . DIRECTORY_SEPARATOR . JString::strtolower( $name ) .'/'. JString::strtolower( $name ) . '.php';

			if( JFile::exists( $file ) )
			{
				require_once( $file );
				$classname	= 'Komento' . ucfirst( $name ) . 'Helper';

				$helpers[ $name ] = class_exists($classname) ? new $classname() : false;
			}
			else
			{
				$helpers[ $name ]	= false;
			}
		}

		return $helpers[ $name ];
	}

	/**
	 * Use KT::table instead
	 *
	 * @deprecated	3.0
	 **/
	public static function getTable($tableName, $prefix = 'KomentoTable')
	{
		return KT::table($tableName, $prefix);
	}

	/**
	 * Retrieves the current version of EasyDiscuss
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public static function getLocalVersion()
	{
		static $version = null;

		if (is_null($version)) {

			$manifest = JPATH_ADMINISTRATOR . '/components/com_komento/komento.xml';

			$parser = JFactory::getXML($manifest, true);

			$version = (string) $parser->version;
		}

		return $version;
	}

	/**
	 * Alternative method to get a table
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function table($tableName, $prefix = 'KomentoTable')
	{
		require_once(KOMENTO_TABLES . '/parent.php');
		$table = KomentoParentTable::getInstance($tableName, $prefix);

		return $table;
	}

	/**
	 * Retrieves the model for Komento
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function model($name, $config = array())
	{
		static $models = array();

		$key = md5($name);

		if (!isset($models[$key])) {

			$file = KOMENTO_ADMIN_ROOT . '/models/' . strtolower($name) . '.php';

			require_once($file);

			$classname = 'KomentoModel' . ucfirst($name);

			$models[$key] = new $classname($config);
		}

		return $models[$key];
	}

	/**
	 * Deprecated
	 *
	 * Retrieve Model objects.
	 *
	 * @param	string	$name	The model name.
	 * @return	object	model object.
	 **/
	public static function getModel( $name, $backend = false )
	{
		return KT::model($name);
	}

	/**
	 * Retrieve Class objects.
	 *
	 * @param	string	$filename	File name of the class.
	 * @param	string	$classname	Class name.
	 * @return	object	class object.
	 **/
	public static function getClass( $filename, $classname )
	{
		static $classes	= array();

		$sig	= md5(serialize(array($filename,$classname)));

		if ( empty($classes[$sig]) )
		{
			$file	= KOMENTO_CLASSES . DIRECTORY_SEPARATOR . JString::strtolower( $filename ) . '.php';

			if( JFile::exists($file) )
			{
				require_once( $file );

				$classes[ $sig ] = class_exists($classname) ? new $classname() : false;
			}
			else{
				$classes[ $sig ] = false;
			}
		}

		return $classes[ $sig ];
	}


	/**
	 * Retrieve Komento's configuration.
	 *
	 * @return	object	JParameter object.
	 **/
	public static function config($component = '', $default = true)
	{
		static $config = null;

		if (!$config) {

			$default = KOMENTO_ADMIN_ROOT . '/defaults/configuration.json';
			$raw = JFile::read($default);

			$config = KT::getRegistry($raw);
			$config->default = clone $config->toObject();

			// @task: Now we need to get the user defined configuration that is stored in the database.
			if (!defined('KOMENTO_CLI')) {
				//get config stored in db
				$table = KT::table('Configs');
				$table->load('config');

				$stored = KT::getRegistry($table->params);

				$config->merge($stored);
			}
		}

		return $config;
	}

	/**
	 * Deprecated. Use KT::config()
	 *
	 * @deprecated	3.0
	 **/
	public static function getConfig($component = '', $default = true)
	{
		return KT::config($component, $default);
	}

	/**
	 * Deprecated. Use KT::getKonfig()
	 *
	 * @deprecated	3.0
	 **/
	public static function getKonfig($component = '', $default = true)
	{
		return KT::config($component, $default);
	}

	/**
	 * Retrieves a token generated by the platform.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function token()
	{
		return JFactory::getSession()->getFormToken();
	}

	/**
	 * If the current user is a super admin, allow them to change the environment via the query string
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public static function checkEnvironment()
	{
		if (!KT::isSiteAdmin()) {
			return;
		}

		$app = JFactory::getApplication();
		$environment = $app->input->get('kt_env', '', 'word');
		$allowed = array('production', 'development');

		// Nothing has changed
		if (!$environment || !in_array($environment, $allowed)) {
			return;
		}

		// We also need to update the database value
		$model = KT::model('Settings');
		$model->save(array('komento_environment' => $environment));

		KT::info()->set('Updated system environment to <b>' . $environment . '</b> mode', 'success');

		return $app->redirect('index.php?option=com_komento');
	}

	/**
	 * Check for token forgeries
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function checkToken()
	{
		JRequest::checkToken('request') or die('Invalid Token');
	}

	public static function getACL()
	{
		$my			= JFactory::getUser();
		$userId		= $my->id;

		KT::import('admin:/includes/acl/acl');

		$acl		= KT::ACL()->getRules( $userId, self::$component );
		$acl		= JArrayHelper::toObject( $acl );

		return $acl;
	}

	/**
	 * Retrieve a list of user groups from the site.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function getUserGroups()
	{
		$db	= KT::db();

		$sql = $db->sql();

		$sql->select('#__usergroups', 'a');
		$sql->column('a.*');
		$sql->column('b.id', 'level', 'count distinct');
		$sql->join('#__usergroups', 'b');
		$sql->on('a.lft', 'b.lft', '>');
		$sql->on('a.rgt', 'b.rgt', '<');
		$sql->group('a.id', 'a.title', 'a.lft', 'a.rgt', 'a.parent_id');
		$sql->order('a.lft', 'ASC');

		$db->setQuery($sql);

		$result = $db->loadObjectList();

		if (!$result) {
			return $result;
		}

		foreach($result as &$row) {
			$sql->clear();

			$sql->select('#__user_usergroup_map');
			$sql->where('group_id', $row->id);

			$db->setQuery($sql->getTotalSql());

			$row->total = $db->loadResult();
		}

		return $result;
	}

	/**
	 * Converts an argument into an array.
	 *
	 * @since	3.0
	 * @param	mixed	An object or string.
	 * @param	string	If a delimeter is provided for string, use that as delimeter when exploding.
	 * @return	Array	Converted into an array.
	 */
	public static function makeArray($item , $delimeter = null )
	{
		// If this is already an array, we don't need to do anything here.
		if (is_array($item)) {
			return $item;
		}

		// Test if source is a SocialRegistry/JRegistry object
		if ($item instanceof KomentoRegistry || $item instanceof JRegistry) {
			return $item->toArray();
		}

		// Test if source is an object.
		if (is_object($item)) {
			return JArrayHelper::fromObject($item);
		}

		if (is_integer($item)) {
			return array($item);
		}

		// Test if source is a string.
		if (is_string($item)) {
			if ($item == '') {
				return array();
			}

			// Test for comma separated values.
			if (!is_null($delimeter) && stristr($item , $delimeter) !== false) {
				$data = explode($delimeter , $item);

				return $data;
			}

			// Test for JSON array string
			$pattern = '#^\s*//.+$#m';
			$item = trim(preg_replace($pattern, '', $item));

			if ((substr($item, 0, 1) === '[' && substr($item, -1, 1) === ']')) {
				return json_decode($item);
			}

			// Test for JSON object string, but convert it into array
			if ((substr($item, 0, 1) === '{' && substr($item, -1, 1) === '}')) {
				$result = json_decode($item);

				return JArrayHelper::fromObject($result);
			}

			return array($item);
		}

		return false;
	}

	/**
	 * Creates a new template object
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string	Explicitly load specific theme
	 * @param	Array	An array of theme options
	 * @return
	 */
	public static function template($theme = false, $options = array())
	{
		require_once(dirname(__FILE__) . '/themes/themes.php');

		$theme = new KomentoThemes($theme, $options);

		return $theme;
	}

	public static function getTemplate($theme = false, $options = array())
	{
		return KT::template($theme, $options);
	}

	/**
	 * Retrieve the user's profile object
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function user($id = null)
	{
		KT::load('user');

		return KomentoUser::getUser($id);
	}

	/**
	 * Use KT::user($id) instead
	 *
	 * @deprecated	3.0
	 */
	public static function getProfile($id = null)
	{
		return KT::user($id);
	}

	public static function getComment( $id = 0, $process = 0, $admin = 0 )
	{
		static $commentsObj = array();

		if( empty( $commentsObj[$id] ) )
		{
			$comment = new KomentoComment($id);

			if( $comment->getError() )
			{
				return false;
			}

			$commentsObj[$id] = $comment;
		}

		if( $process )
		{
			self::import('admin:/includes/comment/comment');
			$commentsObj[$id] = KomentoCommentHelper::process( $commentsObj[$id], $admin );
		}

		return $commentsObj[$id];
	}

	/**
	 * Retrieves the captcha library
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function captcha()
	{
		static $captcha = null;

		if (is_null($captcha)) {
			require_once(__DIR__ . '/captcha/captcha.php');

			$captcha = new KomentoCaptcha();
		}

		return $captcha;
	}

	public static function getDBO()
	{
		return KT::db();
	}

	/**
	 * Returns a new registry object
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function registry($contents = '', $isFile = false)
	{
		$registry = new JRegistry($contents, $isFile);

		return $registry;
	}

	/**
	 * Gets the registry object
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function getRegistry($contents = '', $isFile = false)
	{
		return KT::registry($contents, $isFile);
	}

	public static function getXML($contents, $isFile = false)
	{
		$class = 'SimpleXMLElement';

		if (class_exists('JXMLElement')) {
			$class = 'JXMLElement';
		}

		if ($isFile) {
			// Try to load the XML file
			$xml = simplexml_load_file($contents, $class);

		} else {
			// Try to load the XML string
			$xml = simplexml_load_string($contents, $class);
		}

		if ($xml === false) {
			foreach (libxml_get_errors() as $error) {
				echo "\t", $error->message;
			}
		}

		return $xml;
	}

	public static function getDate( $time = '', $offset = null )
	{
		static $current;

		KT::import('site:/classes/date');

		if( $time == '' )
		{
			if( !$current )
			{
				$current = new KomentoDate( $time, $offset );
			}

			return $current;
		}

		$date = new KomentoDate( $time, $offset );

		return $date;
	}

	/**
	 * A model to get data from a component's content item
	 */
	public static function loadApplication($component = '')
	{
		static $instances = null;

		if( is_numeric($instances) )
		{
			$instances = array();
		}

		$component = preg_replace('/[^A-Z0-9_\.-]/i', '', $component);

		$properInstance = true;
		$komentoPlugin = true;

		// Create a copy of the name so that the original $component won't get affected
		$componentName = $component;

		// If component is empty, then try to load it from getCurrentComponent
		if( empty( $componentName ) )
		{
			$componentName = KT::getCurrentComponent();

			// If component is still empty, then assign it as error
			if( empty( $componentName ) )
			{
				$componentName = 'error';
			}
		}

		// Check for pro components
		// $package = KT::getPackage();
		// if( $package !== 'paid' && in_array( $component, KT::getPaidComponents() ) )
		// {
		// 	$componentName = 'error';
		// }

		// It is possible that even getCurrentComponent doesn't have a data
		if( empty($instances[$componentName]) )
		{
			// Require the base abstract class first
			require_once( KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'komento_plugins' . DIRECTORY_SEPARATOR . 'abstract.php' );

			$classObject = '';

			// Get the component's object first
			$file = JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . $componentName . DIRECTORY_SEPARATOR . 'komento_plugin.php';

			// If it doesn't exist in component path, then look for Komento's native plugin
			if( !JFile::exists($file) )
			{
				// Load from Komento's plugin folder
				$file = KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'komento_plugins' . DIRECTORY_SEPARATOR . $componentName . '.php';

				if ( !JFile::exists($file) )
				{
					$komentoPlugin = false;
				}
			}

			// If Komento plugin is found, then initialise it
			if( $komentoPlugin )
			{
				require_once( $file );

				// Load the class
				$className = 'Komento' . ucfirst( strtolower( preg_replace( '/[^A-Z0-9]/i', '', $componentName ) ) );

				if( class_exists( $className ) )
				{
					$classObject = new $className( $component );

					// If there are any errors in initialising the class
					if( !($classObject instanceof KomentoExtension) || !$classObject->state )
					{
						$properInstance = false;
					}
					else
					{
						$instances[$componentName] = $classObject;
					}
				}
				else
				{
					$properInstance = false;
				}
			}
		}

		// If there are any errors
		if( !$komentoPlugin || !$properInstance || empty( $componentName ) )
		{
			require_once( KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'komento_plugins' . DIRECTORY_SEPARATOR . 'error.php' );
			$classObject = new KomentoError( $component );

			if( empty( $componentName ) )
			{
				$componentName = 'error';
			}

			$instances[$componentName] = $classObject;
		}

		return $instances[$componentName];
	}

	/**
	 * Document library can only be instantiated once
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function document()
	{
		static $doc = null;

		if (is_null($doc)) {
			require_once(__DIR__ . '/document/document.php');

			$doc = new KomentoDocument();
		}

		return $doc;
	}

	public static function getErrorApplication( $component, $cid )
	{
		static $componentInstances	= array();
		static $cidInstances		= array();

		if( empty( $componentInstances[$component] ) )
		{
			require_once( KOMENTO_ROOT . DIRECTORY_SEPARATOR . 'komento_plugins' . DIRECTORY_SEPARATOR . 'error.php' );
			$componentInstances[$component] = new KomentoError( $component );
		}

		if( empty( $cidInstances[$component][$cid] ) )
		{
			$cidInstances[$component][$cid] = $componentInstances[$component]->load( $cid );
		}

		return $cidInstances[$component][$cid];
	}

	/**
	 * Prerequisites check, right after an event is triggered.
	 * (Forced hack on Komento side to work with multiple components properly because sometimes component doesn't care if their plugin file conflicts with other things or not)
	 *
	 * @param	$plugin			string
	 * @param	$eventTrigger	string
	 * @param	$extension		string
	 * @param	$context		string
	 * @return 	boolean
	 */
	public static function onAfterEventTriggered( $plugin, $eventTrigger, $extension, $context, $article, $params )
	{
		if( $extension === 'com_k2' )
		{
			return true;
		}

		// modules check, generally, don't run komento within modules
		if( !empty( $context ) && stristr( $context , 'mod_' ) !== false )
		{
			return false;
		}

		if ($params instanceof JRegistry) {

			$modSfx = $params->get('moduleclass_sfx', '');
			// exception to ohanah
			if (($extension != 'com_ohanah' || $extension != 'com_ohanahvenue') && $modSfx) {
				return false;
			}
		}

		return true;
	}

	private static function verifyContext( $context, $source )
	{
		if( is_null( $context ) )
		{
			return true;
		}

		if( empty( $source ) )
		{
			return false;
		}
		elseif( is_array( $source ) )
		{
			return in_array( $context, $source );
		}
		elseif( is_string( $source ) )
		{
			return $context === $source;
		}
		elseif( is_bool( $source ) )
		{
			return $source;
		}
		else
		{
			return true;
		}
	}

	private static function verifyEventTrigger( $trigger, $source )
	{
		if( is_null( $trigger ) )
		{
			return true;
		}

		if( empty( $source ) )
		{
			return false;
		}
		elseif( is_array( $source ) )
		{
			return in_array( $trigger, $source );
		}
		elseif( is_string( $source ) )
		{
			return $trigger === $source;
		}
		elseif( is_bool( $source ) )
		{
			return $source;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Allows caller to normalize a key from an array
	 *
	 * @since	2.0.9
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function normalize($data, $key, $default)
	{
		if (!is_array($data)) {
			return $default;
		}

		$exists = array_key_exists($key, $data);

		if ($exists) {
			return $data[$key];
		}

		return $default;
	}

	/**
	 * Normalize directory separator
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function normalizeSeparator($path)
	{
		$path = str_ireplace(array( '\\' ,'/' ) , '/' , $path);

		return $path;
	}

	/**
	 * This is where the integration happens where Komento prepares the html output
	 * that can be incuded by the extension
	 *
	 * @param	$component	string
	 * @param	$article	object
	 * @param	$options	array
	 */
	public static function commentify($component, &$article, $options = array())
	{
		$eventTrigger = KT::normalize($options, 'trigger', null);
		$context = KT::normalize($options, 'context', null);
		$params = KT::normalize($options, 'params', array());
		$page = KT::normalize($options, 'page', 0);
		$app = JFactory::getApplication();

		// We only load komento in frontend
		if ($app->isAdmin()) {
			return false;
		}

		// TODO: Allow string/int: see line 662
		// Sometimes people pass in $article as an array, we convert it to object
		if (is_array($article)) {
			$article = (object) $article;
		}

		// Check if there is a valid component
		if (empty($component)) {
			return false;
		}

		// Prepare data and checking on plugin level
		$application = KT::loadApplication($component);

		// Do not proceed if there are errors when loading the application.
		if ($application instanceof KomentoError || !$component) {
			return false;
		}

		// We verify context and trigger first before going into onBeforeLoad because onBeforeLoad already expects the article to be what Komento want to integrate
		$verifiedContext = KT::verifyContext($context, $application->getContext());

		if (!$verifiedContext) {
			return false;
		}

		// Verify if event trigger is correct
		$verifiedTrigger = KT::verifyEventTrigger($eventTrigger, $application->getEventTrigger());

		if (!$verifiedTrigger) {
			return false;
		}

		// @trigger: onBeforeLoad
		// we do this checking before load because in some cases,
		// article is not an object and the article id might be missing.
		$continueRendering = $application->onBeforeLoad($eventTrigger, $context, $article, $params, $page, $options);

		if (!$continueRendering) {
			return false;
		}

		// Set the current component
		self::setCurrentComponent($component);

		// Get all the configuration
		$config = KT::config();

		// Check if komento is enabled for the current extension
		if (!$config->get('enable_komento')) {

			// Special case for RedShop, we need to rollback the changes made on the article
			if ($component == 'com_redshop') {
				$application->onRollBack($eventTrigger, $context, $article, $params, $page, $options);
			}

			return false;
		}

		// Disable Komento in tmpl=component mode such as print mode
		if (JRequest::getString('tmpl', '') === 'component') {
			return false;
		}

		// We accept $article as an int
		// For $article as a string, onBeforeLoad should already prepare the $article object properly
		if (is_string($article) || is_int($article)) {
			$cid = $article;
		} else {
			// Set cid based on application mapping keys because some component might have custom keys (not necessarily always $article-id)
			$cid = $article->{$application->_map['id']};
		}

		// Don't proceed if $cid is empty
		if (empty($cid)) {
			return false;
		}

		// Process in-content parameters
		self::processParameter($article, $options);

		// Terminate if it's disabled
		if ($options['disable']) {

			// Should we disable the comments
			if (!$application->onParameterDisabled($eventTrigger, $context, $article, $params, $page, $options)) {
				return false;
			}
		}

		// Loading article infomation with defined get methods
		if (!$application->load($cid)) {
			return false;
		}

		// If enabled flag exists, bypass category check
		if (array_key_exists('enable', $options) && !$options['enable']) {

			// @task: category access check
			$categories = $config->get('allowed_categories_' . $component . '_settings');

			if (!is_array($categories)) {
				$categories	= explode(',', $categories);
			}

			// categories mode
			switch ($config->get('mode_categories_' . $component . '_settings')) {
				// selected categories
				case 1:
					if (empty($categories)) {
						return false;
					} else {
						// @task: For some reason $article->catid might not be set. If it it's not set, just return false.
						$catid = $application->getCategoryId();

						if (!$catid) {
							if (!$application->onRollBack($eventTrigger, $context, $article, $params, $page, $options)) {
								// raise error
							}
							return false;
						}

						if (!in_array($catid, $categories)) {
							if (!$application->onRollBack($eventTrigger, $context, $article, $params, $page, $options)) {
								// raise error
							}

							return false;
						}
					}
					break;

				// except selected categories
				case 2:
					if (!empty($categories)) {
						// @task: For some reason $article->catid might not be set. If it it's not set, just return false.
						$catid = $application->getCategoryId();

						if (!$catid) 	{
							if (!$application->onRollBack($eventTrigger, $context, $article, $params, $page, $options)) {
								// raise error
							}
							return false;
						}

						if (in_array($catid , $categories)) {
							if (!$application->onRollBack($eventTrigger, $context, $article, $params, $page, $options)) {
								// raise error
							}
							return false;
						}
					}
					break;

				// no categories
				case 3:
					return false;
					break;

				// all categories
				case 0:
				default:
					break;
			}
		}

		// 3rd party APIs might want to prevent loading of comments here
		if (!$application->onAfterLoad($eventTrigger, $context, $article, $params, $page, $options)) {
			return false;
		}

		// Send mail on page load
		if ($config->get('notification_sendmailonpageload')) {
			KT::mailer()->sendOnPageLoad();
		}

		// Clear captcha database
		if ($config->get('database_clearcaptchaonpageload')) {
			self::clearCaptcha();
		}

		/**********************************************/
		// Run Komento!

		// Unknown views
		if (!$application->isListingView() && !$application->isEntryView()) {
			return;
		}

		// Start collecting page objects.
		KT::document()->start();


		$commentsModel = KT::model('Comments');

		$comments = '';
		$return = false;

		$commentCount = $commentsModel->getCount($component, $cid);
		$paginationCount = 0;

		$totalRating = 0;
		$totalRatingCount = 0;

		$needRating = false;

		// Get total ratings
		if ($config->get('enable_ratings')) {

			$needRating = true;

			if ($application->isListingView() && !$config->get('layout_frontpage_ratings')) {
				$needRating = false;
			}

			if ($needRating) {
				$ratings = $commentsModel->getOverallRatings($component, $cid);

				if ($ratings) {
					$totalRating = $ratings->value;
					$totalRatingCount = $ratings->total;
				}
			}

		}

		// Listing view
		if ($application->isListingView()) {
			$html = '';

			if ($needRating || $config->get('layout_frontpage_comment') || $config->get('layout_frontpage_hits') || $config->get('layout_frontpage_preview')) {
				// Load necessary css and javascript files.
				KT::initialize();
			}

			if (!array_key_exists('skipBar', $options)) {

				if ($config->get('layout_frontpage_preview')) {
					$commentOptions = array();

					$commentOptions['threaded'] = 0;
					$commentOptions['limit'] = $config->get( 'preview_count', '3' );

					$previewSort = $config->get('preview_sort', 'latest');

					// since we are in the listing page, we need this sort value so that the sorting can be referenced by comment->getPermalink() method.
					$jinputs = KT::request();
					$jinputs->set('sort', $previewSort);

					$commentOptions['sort'] = $config->get('preview_sort', 'latest');
					$commentOptions['parentid'] = $config->get('preview_parent_only', false) ? 0 : 'all';
					$commentOptions['sticked'] = $config->get('preview_sticked_only', false) ? true : 'all';
					$commentOptions['showRepliesCount'] = false;

					// Since this is a listing view, we don't need to cater for pagination
					$commentOptions['itemListing'] = true;

					if ($commentOptions['sort'] == 'popular') {
						$comments = $commentsModel->getPopularComments( $component, $cid, $commentOptions );
					} else {
						$comments = $commentsModel->getComments( $component, $cid, $commentOptions);
					}

					if ($comments) {
						$comments = KT::formatter('comment', $comments, $commentOptions);
					}
				}

				$showReadmore = false;

				if ($config->get('layout_frontpage_readmore') != 0) {
					$showReadmore = true;

					// We need to respect article params
					if  ($component == 'com_content') {
						$articleParams = KT::registry($article->params);

						if (!$articleParams->get('show_readmore') && !$article->readmore) {
							$showReadmore = false;
						}

						// If user chose to use Joomla readmore button, don't show Komento's
						if ($config->get('layout_frontpage_readmore_button') == 'joomla') {
							$showReadmore = false;
						}
					}
				}

				$theme = KT::themes();

				$theme->set('application', $application);
				$theme->set('totalRatingCount', $totalRatingCount);
				$theme->set('totalRating', $totalRating);
				$theme->set('showReadmore', $showReadmore);
				$theme->set('commentCount', $commentCount);
				$theme->set('componentHelper', $application);
				$theme->set('component', $component);
				$theme->set('cid', $cid);
				$theme->set('comments', $comments);
				$theme->set('article', $article);

				$html = $theme->output('site/listings/default');
			}

			$return	= $application->onExecute($article, $html, 'listing', $options);
		}

		// Entry view is where we render the comment html output
		if ($application->isEntryView()) {
			
			// Load necessary css and javascript files.
			KT::initialize();

			$jinputs = KT::request();

			// Initialize the session time
			if (KT::cleantalk()->isEnabled()) {
				KT::session()->setTime();
			}

			// check for escaped_fragment (google ajax crawler)
			$fragment = $jinputs->get('_escaped_fragment_', '', 'default');

			if ($fragment != '') {
				$tmp = explode( '=', $fragment );
				$fragment = array( $tmp[0] => $tmp[1] );

				if (isset($fragment['kmt-start'])) {
					$options['limitstart'] = $fragment['kmt-start'];
				}
			}

			// Sort comments oldest first by default.
			if (!isset($options['sort'])) {
				$options['sort'] = $jinputs->get('kmt-sort', $config->get('default_sort'), 'default');
			}

			if (!isset($options['limit'])) {
				$options['limit'] = $config->get( 'max_comments_per_page');
			}

			$options['threaded'] = $config->get('enable_threaded');

			$profile = KT::getProfile();

			$my	= JFactory::getUser();

			if (!$profile->allow('read_others_comment')) {
				$options['userid'] = $my->id;
			}

			// always retrieve parent comments.
			$options['parentid'] = '0';

			// since this is entry page, we need to laod the replies
			$options['loadreplies'] = true;

			// attach the replies count into the query.
			$options['showRepliesCount'] = true;

			$options['sticked'] = '0';

			// For pagebreak, we need to reset the limitstart
			// so that every page will show all comments
			if ($config->get('pagebreak_load') == 'all' ) {
				$options['limitstart'] = 0;
			}

			if ($profile->allow('read_comment')) {
				$comments = $commentsModel->getComments($component, $cid, $options);
				$paginationCount = $commentsModel->getTotal();

				if ($comments) {
					$comments = KT::formatter('comment', $comments, $options);
				}
			}

			// Retrieve pinned comment
			$pinnedOptions = array('sticked' => true, 'loadreplies' => true, 'sort' => 'default');
			$pinnedComments = $commentsModel->getComments($component, $cid, $pinnedOptions);

			if ($pinnedComments) {
				$pinnedComments = KT::formatter('comment', $pinnedComments, $pinnedOptions);
			}

			$contentLink = $application->getContentPermalink();

			// Determines if the headers should be shown in the comment form
			$showHeaders = false;

			if (($config->get('show_name') == 2 || $config->get('show_email') == 2 || $config->get('show_website') == 2) ||
				($my->guest && ($config->get('show_name') == 1 || $config->get('show_email') == 1 || $config->get('show_website') == 1))
				) {
				$showHeaders = true;
			}

			$showCaptcha = false;

			if ($config->get('antispam_captcha_enable')) {
				$captchaGroup = $config->get('show_captcha');

				if (!is_array($captchaGroup)) {
					$captchaGroup = explode(',', $captchaGroup);
				}

				$usergids = $profile->getUsergroups();

				foreach ($usergids as $gid) {
					if (in_array($gid, $captchaGroup)) {
						$showCaptcha = true;
						break;
					}
				}
			}

			$showNameField = KT::showFormField($component, 'show_name');
			$requireNameField = KT::requireFormField($component, 'require_name');

			$showEmailField = KT::showFormField($component, 'show_email');
			$requireEmailField = KT::requireFormField($component, 'require_email');

			$showWebsiteField = KT::showFormField($component, 'show_website');
			$requireWebsiteField = KT::requireFormField($component, 'require_website');

			$showTerms = false;
			$tncGroup = $config->get('show_tnc', '');

			if (!is_array($tncGroup)) {
				$tncGroup = explode(',', $tncGroup);
			}

			$usergids = $profile->getUsergroups();

			foreach ($usergids as $gid) {
				if (in_array($gid, $tncGroup)) {
					$showTerms = true;
					break;
				}
			}

			$showSubscribe = false;
			$subscription = null;

			if ($config->get('enable_subscription') && (!$my->guest || $config->get('show_email') > 0) && !$config->get('subscription_auto')) {
				$showSubscribe = true;
				$subscription = KT::model('Subscription')->checkSubscriptionExist($component, $cid, $my->id);
			}


			// Determines if the more button should be visible
			$showMoreButton = false;
			$moreStartCount = $config->get('max_comments_per_page');

			if ($profile->allow('read_comment') && $paginationCount) {
				$showMoreButton = true;

				if (isset($options['limitstart'])) {
					$moreStartCount = $options['limitstart'] + $config->get('max_comments_per_page');
				}

				if ($paginationCount <= $moreStartCount) {
					$showMoreButton = false;
				}
			}

			if (!isset($options['lock'])) {
				$options['lock'] = false;
			}

			// Active sort
			$activeSort = JRequest::getCmd('kmt-sort', $config->get('default_sort'));

			// Conversation bar
			$authors = false;

			if ($profile->allow('read_comment') && $config->get('enable_conversation_bar') && $config->get('layout_avatar_enable') && count($comments) > 0) {
				$authors = $commentsModel->getConversationBarAuthors($component, $cid);
			}

			// Double check this with Joomla's registration component
			$joomlaUserParams = JComponentHelper::getParams('com_users');
			$allowRegistration = $joomlaUserParams->get('allowUserRegistration') == '0' ? false : true;

			// Add support for different view form and listing
			$section = isset($options['section']) ? $options['section'] : false;

			// get the background presets
			$bgModel = KT::model('Backgrounds');
			$presets = $bgModel->getPresets(array('published' => true));

			$theme = KT::themes();
			$theme->set('authors', $authors);
			$theme->set('activeSort', $activeSort);
			$theme->set('moreStartCount', $moreStartCount);
			$theme->set('showMoreButton', $showMoreButton);
			$theme->set('showSubscribe', $showSubscribe);
			$theme->set('subscription', $subscription);
			$theme->set('showTerms', $showTerms);
			$theme->set('showCaptcha', $showCaptcha);
			$theme->set('showNameField', $showNameField);
			$theme->set('requireNameField', $requireNameField);
			$theme->set('showEmailField', $showEmailField);
			$theme->set('requireEmailField', $requireEmailField);
			$theme->set('showWebsiteField', $showWebsiteField);
			$theme->set('requireWebsiteField', $requireWebsiteField);
			$theme->set('showHeaders', $showHeaders);
			$theme->set('totalRating', $totalRating);
			$theme->set('totalRatingCount', $totalRatingCount);
			$theme->set('component', $component );
			$theme->set('cid', $cid);
			$theme->set('comments', $comments);
			$theme->set('options', $options);
			$theme->set('componentHelper', $application);
			$theme->set('application', $application);
			$theme->set('commentCount', $commentCount);
			$theme->set('contentLink', $contentLink);
			$theme->set('allowRegistration', $allowRegistration);
			$theme->set('pinnedComments', $pinnedComments);
			$theme->set('presets', $presets);

			if ($section) {
				$output = $theme->output('site/structure/' . $section);
			} else {
				$output = $theme->output('site/structure/default');
			}

			$return	= $application->onExecute($article, $output, 'entry', $options);
		}

		KT::document()->end();

		return $return;
	}

	/**
	 * Determines if captcha is needed
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function requireCaptcha()
	{
		static $required = null;

		if (is_null($required)) {
			$config = KT::config();
			$groups = $config->get('show_captcha');

			if (!is_array($groups)) {
				$groups = trim($groups);
				$groups = explode(',', $groups);
			}

			$profile = KT::getProfile();
			$userGroups = $profile->getUserGroups();
			$required = false;

			foreach ($userGroups as $userGroupId) {
				if (in_array($userGroupId, $groups)) {
					$required = true;
					break;
				}
			}
		}

		return $required;
	}

	/**
	 * Determines if the terms and conditions field is required
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function requireTerms()
	{
		static $terms = null;

		if (is_null($terms)) {
			$config = KT::config();
			$groups = $config->get('show_tnc');

			if (!is_array($groups)) {
				$groups = explode(',', $groups);
			}

			$profile = KT::getProfile();
			$userGroups = $profile->getUserGroups();

			// Default to not require terms
			$terms = false;

			foreach ($userGroups as $userGroupId) {
				if (in_array($userGroupId, $groups)) {
					$terms = true;
					break;
				}
			}
		}

		return $terms;
	}

	public static function requireFormField($component, $name)
	{
		$config	= self::getConfig($component);
		$my = JFactory::getUser();
		$require = false;

		if ($config->get($name) == 2 || ($config->get($name) == 1 && $my->guest)) {
			$require = true;
		}

		return $require;
	}

	public static function showFormField($component, $name)
	{
		$config	= self::getConfig($component);
		$my = JFactory::getUser();
		$show = false;

		if ($config->get($name) == 2 || ($config->get($name) == 1 && $my->guest)) {
			$show = true;
		}

		return $show;
	}

	public static function processParameter(&$article, &$options)
	{
		// Retrieve user parameters e.g.
		// {KomentoDisable}, {KomentoLock}

		if (is_string($article)) {
			$text = &$article;
		} elseif (is_object($article)) {
			// adjust to standard format
			if (!isset($article->introtext)) {
				$article->introtext = '';
			}

			if (!isset($article->text)) {
				$article->text = '';
			}

			// We assign it to a temp variable to avoid using pass by reference
			$introtext = $article->introtext;
			$text = $article->text;

			if (!$text) {
				// this could be coming from other extension
				// Special case for Ohanah
				if (isset($article->description)) {
					$text = $article->description;
				}
			}
		} else {
			return;
		}

		$options['disable'] = (JString::strpos($introtext, '{KomentoDisable}') !== false || JString::strpos($text, '{KomentoDisable}') !== false);
		$options['enable'] = (JString::strpos($introtext, '{KomentoEnable}') !== false || JString::strpos($text, '{KomentoEnable}') !== false);
		$options['lock'] = (JString::strpos($introtext, '{KomentoLock}') !== false || JString::strpos($text, '{KomentoLock}') !== false);

		// Remove in-content parameters
		if (!empty($introtext)) {
			$introtext = JString::str_ireplace('{KomentoDisable}', '', $introtext);
			$introtext = JString::str_ireplace('{KomentoEnable}', '', $introtext);
			$introtext = JString::str_ireplace('{KomentoLock}', '', $introtext);
		}

		if (!empty($text)) {
			$text = JString::str_ireplace('{KomentoDisable}', '', $text);
			$text = JString::str_ireplace('{KomentoEnable}', '', $text);
			$text = JString::str_ireplace('{KomentoLock}', '', $text);
		}

		$article->introtext = $introtext;
		$article->text = $text;

		// Special case for Ohanah
		if (isset($article->description)) {
			$article->description = $text;
		}
	}

	public static function mergeOptions( $defaults, $options )
	{
		$options	= array_merge($defaults, $options);
		foreach ($options as $key => $value)
		{
			if( !array_key_exists($key, $defaults) )
				unset($options[$key]);
		}

		return $options;
	}

	public static function setMessageQueue($message, $type = 'info')
	{
		$session 	= JFactory::getSession();

		$msgObj = new stdClass();
		$msgObj->message    = $message;
		$msgObj->type       = strtolower($type);

		//save messsage into session
		$session->set('komento.message.queue', $msgObj, 'KOMENTO.MESSAGE');
	}

	public static function getMessageQueue()
	{
		$session 	= JFactory::getSession();
		$msgObj 	= $session->get('komento.message.queue', null, 'KOMENTO.MESSAGE');

		//clear messsage into session
		$session->set('komento.message.queue', null, 'KOMENTO.MESSAGE');

		return $msgObj;
	}

	/**
	 * Method to get Joomla's version
	 *
	 * @return	string
	 */
	public static function joomlaVersion()
	{
		static $version = null;

		if (!$version) {
			$version = KT::version()->getJoomlaVersion();
		}

		return $version;
	}

	/**
	 * Determines if the user is a super admin on the site.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function isSiteAdmin($id = null)
	{
		static $items = array();

		$user = JFactory::getUser($id);

		if (!isset($items[$user->id])) {
			$items[$user->id] = $user->authorise('core.admin');
		}

		return $items[$user->id] ? true : false;
	}

	public static function isJoomla30()
	{
		return KT::joomlaVersion() >= '3.0';
	}

	public static function isJoomla31()
	{
		return KT::joomlaVersion() >= '3.1';
	}

	public static function isJoomla25()
	{
		return KT::joomlaVersion() >= '1.6' && KT::joomlaVersion() <= '2.5';
	}

	public static function isJoomla15()
	{
		return KT::joomlaVersion() == '1.5';
	}

	/**
	 * Method to get installed Komento version
	 *
	 * @return	string
	 */
	public static function komentoVersion()
	{
		static $version = null;

		if (!$version) {
			$version = KT::version()->getLocalVersion();
		}

		return $version;
	}

	public static function getCurrentComponent()
	{
		return self::$component;
	}

	public static function setCurrentComponent( $component = 'com_component' )
	{
		$component	= preg_replace('/[^A-Z0-9_\.-]/i', '', $component);

		self::$component = $component;

		return self::$component;
	}

	/**
	 * Used in J1.6!. To retrieve list of superadmin users's id.
	 * array
	 */
	public static function getSAUsersIds()
	{
		$saGroup = KT::getSAIds();

		//now we got all the SA groups. Time to get the users
		$saUsers = array();
		if (count($saGroup) > 0) {
			foreach ($saGroup as $saId) {
				$userArr = JAccess::getUsersByGroup($saId);

				if (count($userArr) > 0) {
					foreach($userArr as $user) {
						$saUsers[] = $user;
					}
				}
			}
		}

		return $saUsers;
	}

	public static function getSAIds()
	{
		$db = KT::db();

		$query = 'SELECT a.`id`';
		$query .= ' FROM `#__usergroups` AS a';
		$query .= ' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt';
		$query .= ' GROUP BY a.id';
		$query .= ' ORDER BY a.lft ASC';

		$db->setQuery($query);
		$result = $db->loadObjectList();

		$saGroup = array();
		foreach ($result as $group) {
			if (JAccess::checkGroup($group->id, 'core.admin')) {
				$saGroup[]  = $group->id;
			}
		}

		return $saGroup;
	}

	/**
	 * Alias method to load info library
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function info()
	{
		static $lib = null;

		if (is_null($lib)) {
			KT::load('info');

			$lib = new KomentoInfo();
		}

		return $lib;
	}

	// Method to route standard links (bugged)
	public static function route( $link )
	{
		if (JPATH_BASE == JPATH_ADMINISTRATOR) {
			JFactory::$application = JApplication::getInstance('site');
		}

		$link = JRoute::_($link);

		if (JPATH_BASE == JPATH_ADMINISTRATOR) {
			$link = str_ireplace('/administrator/', '/', $link);
			JFactory::$application = JApplication::getInstance('administrator');
		}

		return $link;
	}

	public static function getUniqueFileName($originalFilename, $path)
	{
		$ext			= JFile::getExt($originalFilename);
		$ext			= $ext ? '.'.$ext : '';
		$uniqueFilename	= JFile::stripExt($originalFilename);

		$i = 1;

		while( JFile::exists($path.DIRECTORY_SEPARATOR.$uniqueFilename.$ext) )
		{
			// $uniqueFilename	= JFile::stripExt($originalFilename) . '-' . $i;
			$uniqueFilename	= JFile::stripExt($originalFilename) . '_' . $i . '_' . KT::date()->toFormat( "%Y%m%d-%H%M%S" );
			$i++;
		}

		//remove the space into '-'
		$uniqueFilename = str_ireplace(' ', '-', $uniqueFilename);

		return $uniqueFilename.$ext;
	}

	public static function getUsergroupById( $id )
	{
		$sql = KT::sql();

		if( KT::isJoomla15() )
		{
			$sql->select( '#__core_acl_aro_groups' )
				->column( 'id' )
				->column( 'name', 'title' )
				->column( '0', 'depth', '', true )
				->where( 'id', $id );
		}
		else
		{
			$sql->select( '#__usergroups' )
				->column( 'title' )
				->where( 'id', $id );
		}

		return $sql->loadResult();
	}

	public static function getUsersByGroup( $gid )
	{
		$userArr = array();

		if( KT::joomlaVersion() >= '1.6' )
		{
			$userArr	= JAccess::getUsersByGroup($gid);
		}
		else
		{
			$sql = KT::sql();

			$sql->select( '#__users' )
				->column( 'id' )
				->where( 'gid', $gid );

			$userArr = $sql->loadResultArray();
		}

		return $userArr;
	}

	/**
	 * Retrieves an instance of the ajax library
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function ajax()
	{
		static $lib = null;

		if (is_null($lib)) {
			KT::load('ajax');

			$lib = new KomentoAjax();
		}

		return $lib;
	}

	public static function addJomSocialPoint($action, $userId = 0)
	{
		$jsCoreFile	= JPATH_ROOT . '/components/com_community/libraries/core.php';

		if (!JFile::exists($jsCoreFile)) {
			return false;
		}

		require_once($jsCoreFile);

		$my	= JFactory::getUser();

		if (!empty($userId)) {
			$my	= JFactory::getUser($userId);
		}

		if ($my->id != 0) {
			CUserPoints::assignPoint($action, $my->id);
		}

		return true;
	}

	public static function addAUP($plugin_function = '', $referrerid = '', $keyreference = '', $datareference = '')
	{
		$my	= JFactory::getUser();

		if (!empty($referrerid)) {
			$my	= JFactory::getUser($referrerid);
		}

		if ($my->id != 0) {
			$aup = JPATH_ROOT . '/components/com_alphauserpoints/helper.php';
			if (JFile::exists($aup)) {
				require_once($aup);
				AlphaUserPointsHelper::newpoints($plugin_function, AlphaUserPointsHelper::getAnyUserReferreID($referrerid), $keyreference, $datareference);
			}
		}
	}


	public static function addALP($plugin_function = '', $referrerid = '', $keyreference = '', $datareference = '')
	{
		$my	= JFactory::getUser();

		if (!empty($referrerid)) {
			$my	= JFactory::getUser($referrerid);
		}

		if ($my->id != 0) {
			$alp = JPATH_ROOT . '/components/com_altauserpoints/helper.php';
			if (JFile::exists($alp)) {
				require_once($alp);
				AltaUserPointsHelper::newpoints($plugin_function, AltaUserPointsHelper::getAnyUserReferreID($referrerid), $keyreference, $datareference);
			}
		}
	}


	public static function addDiscussPoint($action, $userId = 0, $title = '')
	{
		$my	= JFactory::getUser();

		if (!empty($userId)) {
			$my	= JFactory::getUser( $userId );
		}

		if ($my->id) {
			
			jimport('joomla.filesystem.file');
			$file = JPATH_ADMINISTRATOR . '/components/com_easydiscuss/includes/easydiscuss.php';

			if (!JFile::exists($file)) {
				return false;
			}

			include_once($file);

			ED::points()->assign($action, $my->id);

			if ($title != '' && KT::getConfig()->get('enable_discuss_log')) {
				ED::history()->log($action, $userId, $title, 0);
			}

			return true;
		}
	}

	public static function clearCaptcha( $days = '7' )
	{
		$db = KT::getDBO();

		$query = 'DELETE FROM ' . $db->nameQuote( '#__komento_captcha' ) . ' WHERE ' . $db->nameQuote( 'created' ) . ' <= DATE_SUB(NOW(), INTERVAL ' . $days . ' DAY)';

		$db->setQuery( $query );
		$db->query();

		return $query;
	}

	/*
	General trigger function to trigger custom Komento events
	List of triggers:
		void onBeforeKomentoBar( int &$commentCount )
		void onBeforeKomentoBox( object &$system, object &$comments )
		bool onBeforeSaveComment object( &$comment )
		void onAfterSaveComment( object &$comment )
		void onBeforeProcessComment( object &$comment )
		void onAfterProcessComment( object &$comment )
		bool onBeforeSendNotification( object &$recipient )
		bool onBeforeDeleteComment( object &$comment )
		void onAfterDeleteComment( object &$comment )
		bool onBeforePublishComment( object &$comment )
		void onAfterPublishComment( object &$comment )
		bool onBeforeUnpublishComment( object &$comment )
		void onAfterUnpublishComment( object &$comment )
	*/
	public static function trigger($event, $params = array())
	{
		$config = KT::getConfig();
		$component = null;
		$cid = null;

		if (isset($params['component'])) {
			$component = $params['component'];
			unset($params['component']);
		}

		if (isset($params['cid'])) {
			$cid = $params['cid'];
			unset($params['cid']);
		}

		if ($config->get('trigger_method') == 'joomla') {

			static $plugin = false;

			if ($plugin === false) {
				$plugin = true;
				JPluginHelper::importPlugin('komento');
			}

			$application = JFactory::getApplication();

			$arguments = array();

			if (!empty($component)) {
				$arguments[] = $component;
			}

			if (!empty($cid)) {
				$arguments[] = $cid;
			}

			$arguments[] = &$params;
			$results = $application->triggerEvent($event, $arguments);

			if (is_array($results) && in_array(false, $results)) {
				return false;
			}

			return true;
		}

		if ($config->get('trigger_method') === 'component') {

			if (!$component) {
				return false;
			}

			$application = KT::loadApplication($component);

			if ($cid) {
				$application->load($cid);
			}

			return call_user_func_array(array($application, $event), $params);
		}

		return true;
	}

	public static function debugSql( $query )
	{
		return nl2br(str_replace('#__', 'jos_', $query));
	}

	public static function getIP()
	{
		return KT::getHelper( 'ip' )->getIP();
	}

	public static function setMessage( $msg, $type = 'notice' )
	{
		KT::$messages[] = array( 'message' => $msg, 'type' => $type );
	}

	public static function getMessages( $type = 'all' )
	{
		if( $type === 'all' )
		{
			return KT::$messages;
		}
		else
		{
			$filtered = array();

			foreach( KT::$messages as $message )
			{
				if( $message['type'] === $type )
				{
					$filtered[] = $message['message'];
				}
			}

			return $filtered;
		}
	}

	public static function setError( $msg )
	{
		KT::setMessage( $msg, 'error' );
	}

	public static function getErrors()
	{
		return KT::getMessages( 'error' );
	}

	public static function getPaidComponents()
	{
		return array( 'com_aceshop', 'com_flexicontent', 'com_hwdmediashare', 'com_jevents', 'com_k2', 'com_mtree', 'com_ohanah', 'com_redshop', 'com_sobipro', 'com_virtuemart', 'com_zoo' );
	}

	/**
	 * Get's the database object.
	 *
	 * @since	3.0
	 */
	public static function db()
	{
		static $db = null;

		if (!$db) {
			$db = KT::database();
		}

		return $db;
	}

	/**
	 * Content formatter for the comments
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function formatter($type, $items, $options = array(), $cache = true)
	{
		KT::import('admin:/includes/formatter/formatter');

		$formatter 	= new KomentoFormatter($type, $items, $options, $cache);

		return $formatter->execute();
	}

	/**
	 * Renders the pagination library
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function pagination($total, $limitstart, $limit)
	{
		static $lib = null;

		if (is_null($lib)) {
			KT::load('pagination');

			$lib = new KomentoPagination($total, $limitstart, $limit);
		}

		return $lib;
	}

	/**
	 * profiles class
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function profiles($profile, $type = 'default')
	{
		KT::import('admin:/includes/profiles/profiles');

		$profile = new KomentoProfiles($profile, $type);

		return $profile;
	}


	/**
	 * cache for comment related items.
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public static function cache()
	{
		static $cache = null;

		if (!$cache) {
			KT::import('admin:/includes/cache/cache');
			$cache = new KomentoCache();
		}

		return $cache;
	}

	public static function getThemeObject($name)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$obj = new stdClass();
		$obj->element = $name;
		$obj->name = ucwords($name);
		$obj->path = KOMENTO_ROOT . '/themes/' . $obj->element;
		$obj->writable = is_writable($obj->path);
		$obj->version = '&mdash;';
		$obj->author = 'Stack Ideas';

		return $obj;
	}

	/**
	 * Synchronizes database versions
	 *
	 * @since   5.0
	 * @access  public
	 * @param   string
	 * @return
	 */
	public static function sync($from = '')
	{
		$db = KT::db();

		// List down files within the updates folder
		$path = KOMENTO_ADMIN_ROOT . '/updates';

		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');

		$scripts= array();

		if ($from) {
			$folders = JFolder::folders($path);

			if ($folders) {

				foreach ($folders as $folder) {

					// Because versions always increments, we don't need to worry about smaller than (<) versions.
					// As long as the folder is greater than the installed version, we run updates on the folder.
					// We cannot do $folder > $from because '1.2.8' > '1.2.15' is TRUE
					// We want > $from, NOT >= $from
					if (version_compare($folder, $from) === 1) {
						$fullPath = $path . '/' . $folder;

						// Get a list of sql files to execute
						$files = JFolder::files( $fullPath , '.json$' , false , true );

						foreach ($files as $file) {
							$data = json_decode(JFile::read($file));
							$scripts = array_merge($scripts, $data);
						}
					}
				}
			}
		} else {

			$files = JFolder::files($path, '.json$', true, true);

			// If there is nothing to process, skip this
			if (!$files) {
				return false;
			}

			foreach ($files as $file) {
				$data = json_decode(JFile::read($file));
				$scripts = array_merge($scripts, $data);
			}
		}

		if (!$scripts) {
			return false;
		}

		$tables = array();
		$indexes = array();
		$affected = 0;


		foreach ($scripts as $script) {

			$columnExist = true;
			$indexExist = true;

			if (isset($script->column)) {

				// Store the list of tables that needs to be queried
				if (!isset($tables[$script->table])) {
					$tables[$script->table] = $db->getColumns($script->table);
				}

				// Check if the column is in the fields or not
				$columnExist = in_array($script->column, $tables[$script->table]);
			}

			if (isset($script->index)) {

				// Get the list of indexes on a table
				if (!isset($indexes[$script->table])) {
					$indexes[$script->table] = $db->getIndexes($script->table);
				}

				$indexExist = in_array($script->index, $indexes[$script->table]);
			}

			if (!$columnExist || !$indexExist) {
				$db->setQuery($script->query);
				$db->Query();

				$affected += 1;
			}
		}

		return $affected;
	}

	/**
	 * Retrieves the view object.
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string	The view's name.
	 * @param	bool	True for back end , false for front end.
	 */
	public static function view( $name , $backend = true )
	{
		$className 	= 'KomentoView' . ucfirst( $name );

		if( !isset( self::$views[ $className ] ) || ( !self::$views[ $className ] instanceof KomentoView ) ) {

			if (!class_exists($className)) {
				$path  = $backend ? KOMENTO_ADMIN_ROOT : KOMENTO_ROOT;
				$doc   = JFactory::getDocument();
				$path .= '/views/' . strtolower( $name ) . '/view.' . $doc->getType() . '.php';

				if (!JFile::exists($path)) {
					return false;
				}

				// Include the view
				require_once($path);
			}

			if (!class_exists($className)) {
				JError::raiseError( 500 , JText::sprintf( 'View class not found: %1s' , $className ) );
				return false;
			}

			self::$views[ $className ]	= new $className( array() );
		}

		return self::$views[ $className ];
	}

	/**
	 * Retrieves the cdn url for the site
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function getCdnUrl()
	{
		$config = KT::config();
		$url = $config->get('komento_cdn_url');

		if (!$url) {
			return false;
		}

		// Ensure that the url contains http:// or https://
		if (stristr($url, 'http://') === false && stristr($url, 'https://') === false) {
			$url = '//' . $url;
		}

		return $url;
	}

	/**
	 * Detects if the folder exist based on the path given. If it doesn't exist, create it.
	 *
	 * @since   3.1
	 * @access  public
	 */
	public static function makeFolder($path)
	{
		jimport('joomla.filesystem.folder');

		// If folder exists, we don't need to do anything
		if (JFolder::exists($path)) {
			return true;
		}

		// Folder doesn't exist, let's try to create it.
		if (JFolder::create($path)) {
			return true;
		}

		return false;
	}
}

class KMT extends KT {}
class Komento extends KT {}

