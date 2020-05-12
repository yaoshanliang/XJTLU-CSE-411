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

jimport('joomla.filesystem.file');

class KomentoTemplate
{
	/**
	 * Stores the template variables
	 * @var array
	 */
	public $vars = array();

	public $file = '';

	/**
	 * Stores the current extension
	 * @var array
	 */
	public $extension = 'php';
	public $currentTheme = null;

	/**
	 * Stores the access for the current user
	 * @var SocialAccess
	 */
	static $acl = null;
	static $user = null;
	static $tmplMode = null;

	/**
	 * Stores the access for the current user
	 * @var SocialAccess
	 */
	static $templateConfig	= null;

	public function __construct()
	{
		// Define Joomla's app
		$this->config = KT::config();

		if (!defined('KOMENTO_CLI')) {
			$this->app = JFactory::getApplication();
			$this->input = $this->app->input;
			$this->session = JFactory::getSession();
		}

		if (is_null(self::$tmplMode)) {
			self::$tmplMode = $this->input->get('tmpl', '', 'default');
		}

		// Define the current logged in user or guest
		if (is_null(self::$user)) {
			self::$user = KT::getProfile();
		}

		// // Define the current logged in user's access.
		if (is_null(self::$acl)) {
			self::$acl = KT::acl();
		}

		if (is_null(self::$tmplMode)) {
			self::$tmplMode = $this->input->get('tmpl', '', 'default');
		}

		// Get the current access
		$this->my = self::$user;
		$this->access = self::$acl;
		$this->tmpl = self::$tmplMode;
		$this->doc = JFactory::getDocument();

		$this->responsive = KT::responsive()->getClass();
	}

	/**
	 * Returns the metadata of a template file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	$tpl	The name of the file. Example: themes:/dashboard/default
	 * @return	stdClass
	 */
	public function getTemplate($namespace = null)
	{
		// Explode the namespace
		$parts = explode(':', $namespace);

		// Legacy fixes.
		$hasProtocol = count( $parts ) > 1 ? true : false;

		if (!$hasProtocol) {
			$namespace = 'themes:/' . $namespace;
		}

		$template = new stdClass();
		$template->file = $this->resolve($namespace . '.' . $this->extension);
		$template->script = $this->resolve($namespace . '.js');

		return $template;
	}

	/**
	 * Returns the metadata of a template file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	$tpl	The name of the file. Example: themes:/dashboard/default
	 * @return	stdClass
	 */
	public function getFileStructure($namespace = null)
	{
		$template = new stdClass();
		$template->file = $this->resolve($namespace);
		$template->script = $this->resolve($namespace, 'js');

		return $template;
	}

	/**
	 * Resolves a given namespace
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function resolve($namespace, $extension = 'php')
	{
		if (defined('KOMENTO_CLI')) {
			$defaultPath = KOMENTO_THEMES . '/' . KOMENTO_THEME_BASE . '/' . $file;

			return $defaultPath;
		}

		$parts = explode('/', $namespace);
		$location  = $parts[0];

		// Remove the location
		unset($parts[0]);

		// Glue back the parts
		$file = implode('/', $parts) . '.' . $extension;

		// Get the base path
		$base = JPATH_ROOT . '/components/com_komento/themes';
		$currentThemePath = $base . '/' . $this->currentTheme;

		// Get your current template
		$themeModel = KT::model('themes');

		// For admin theme files, we can discard the overrides for now
		if ($location == 'admin') {
			$path = JPATH_ADMINISTRATOR . '/components/com_komento/themes/default/' . $file;

			return $path;
		}

		// Default path is the current theme's path
		$path = $currentThemePath . '/' . $file;

		// Check for template overrides
		$override = JPATH_ROOT . '/templates/' . $themeModel->getCurrentTemplate() . '/html/com_komento/' . $file;

		if (JFile::exists($override)) {
			$path = $override;
		}

		// If file doesn't exists, we should fall back to the wireframe theme
		if (!JFile::exists($path)) {
			$path = $base . '/wireframe/' . $file;
		}
		
		return $path;
	}

	/**
	 * Assigns a value into the vars data.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function set($key, $value)
	{
		$this->vars[$key] = $value;

		return $this;
	}

	/**
	 * Similar to loadTemplate but the only difference is that the variables are shared with $this->vars
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function includeTemplate( $path , $vars = array() )
	{
		if (count($vars) > 0) {
			foreach ($vars as $key => $value) {
				$this->set($key, $value);
			}
		}

		return $this->output($path);
	}

	/**
	 * Given a set of arguments, load a new template file and only extracted vars would be available in this scope.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The template's file name.
	 * @param	Array
	 * @return
	 */
	public function loadTemplate($path, $vars = array())
	{
		$data 	= array();

		if (count($vars) > 0) {
			foreach ($vars as $key => $value) {
				$data[$key]	= $value;
			}
		}

		return $this->output($path, $data);
	}

	/**
	 * Outputs the data from a template file.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function output($path = null , $vars = null )
	{
		// Keep original file value
		if (!is_null($path)) {
			$_file = $this->file;
			$this->file = FD::resolve($path . '.' . $this->extension);
		}

		// Let's try to extract the data.
		$output = $this->parse($vars);

		// Restore original file value
		if (!is_null($path)) {
			$this->file = $_file;
		}

		// Free up some memory
		unset($_file);

		return $output;
	}

	/**
	 * Cleaner extract method. All variables that are set in $this->vars would be extracted within this scope only.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	Array	An array of arguments to supply to the theme file.
	 *
	 * @return	string	The template contents.
	 */
	public function parse($vars = null)
	{
		ob_start();

		// If argument is passed in, we only want to load that into the scope.
		if (is_array($vars)) {
			extract($vars);
		} else {
			// Extract variables that are available in the namespace
			if(!empty($this->vars)) {
				extract($this->vars);
			}
		}

		// Magic happens here when we include the template file.
		include($this->file);

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Determines if this is a mobile layout
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function isMobile()
	{
		$responsive = null;

		if (is_null($responsive)) {
			$responsive = KT::responsive()->isMobile();
		}

		return $responsive;
	}

	/**
	 * Determines if this is a tablet layout
	 *
	 * @since	3.2
	 * @access	public
	 */
	public function isTablet()
	{
		static $responsive = null;

		if (is_null($responsive)) {
			$responsive = KT::responsive()->isTablet();
		}
		
		return $responsive;
	}


	/**
	 * Template helper
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The name of the method.
	 * @return	mixed
	 */
	public function html($namespace)
	{
		static $objects = array();

		// Get the correct namespaces
		list($helperName, $methodName) = explode('.', $namespace);

		$className = "ThemesHelper" . ucfirst($helperName);
		$uid = md5($className);

		// If it doesn't exists yet, create it
		if (!isset($objects[$uid])) {

			$file = __DIR__ . '/helpers/' . strtolower($helperName) . '.php';

			include_once($file);

			$objects[$uid] = new $className;
		}

		// Remove the first 2 arguments from the args.
		$args = func_get_args();
		$args = array_splice($args, 1);


		$obj = $objects[$uid];

		$response = call_user_func_array(array($obj, $methodName), $args);

		return $response;
	}

	/**
	 * Check if the template file exist
	 *
	 * @since	1.1
	 * @access	public
	 * @param	string		The namespace of the file
	 * @return	boolean
	 *
	 */
	public function exists($namespace, $type = 'file')
	{
		$template = $this->getTemplate( $namespace );

		return !empty( $template->$type ) && JFile::exists( $template->$type );
	}

	/**
	 * We cannot rely on $app->getTemplate() because we need to explicitly get the current default front end template.
	 *
	 * @since	3.0.7
	 * @access	public
	 */
	public static function getCurrentTemplate()
	{
		static $template = null;

		if (is_null($template)) {
			$model = KT::model('Themes');
			$template = $model->getCurrentTemplate();
		}

		return $template;
	}
}
