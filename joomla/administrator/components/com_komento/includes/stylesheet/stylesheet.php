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

require_once(__DIR__ . '/libraries/compiler.php');
require_once(__DIR__ . '/libraries/minifier.php');
require_once(__DIR__ . '/libraries/builder.php');
require_once(__DIR__ . '/libraries/analyzer.php');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.path');

// Used by stylesheet library
define('KOMENTO_JOOMLA', JPATH_ROOT);
define('KOMENTO_JOOMLA_URI', rtrim(JURI::root(), '/'));
define('KOMENTO_JOOMLA_ADMIN', JPATH_ADMINISTRATOR);
define('KOMENTO_JOOMLA_ADMIN_URI', KOMENTO_JOOMLA_URI . '/administrator');
define('KOMENTO_JOOMLA_SITE_TEMPLATES', KOMENTO_JOOMLA . '/templates' );
define('KOMENTO_JOOMLA_SITE_TEMPLATES_URI', KOMENTO_JOOMLA_URI . '/templates' );
define('KOMENTO_JOOMLA_ADMIN_TEMPLATES', KOMENTO_JOOMLA_ADMIN . '/templates' );
define('KOMENTO_JOOMLA_ADMIN_TEMPLATES_URI', KOMENTO_JOOMLA_ADMIN_URI . '/templates' );
define('KOMENTO_JOOMLA_MODULES', KOMENTO_JOOMLA . '/modules' );
define('KOMENTO_JOOMLA_MODULES_URI', KOMENTO_JOOMLA_URI . '/modules' );

class KomentoStylesheet extends KomentoBase
{
	public $ns = 'KOMENTO';
	public $baseurl = null;

	private $class = __CLASS__;

	public $workspace = array(
		'site'       => null,
		'site_base'  => null,
		'admin'      => null,
		'admin_base' => null,
		'module'     => null,
		'override'   => null
	);

	public $location;
	public $name;
	public $isOverride = false;
	public $overrideStylesheet;
	static $attached = array();
	static $filetypes = array(
		'ats'     => array('css', 'minified'),
		'less'    => array('less', 'css', 'minified'),
		'css'     => array('css', 'minified'),
		'section' => array('less', 'css', 'minified')
	);

	const FILE_STATUS_NEW       = -1;
	const FILE_STATUS_UNCHANGED = 0;
	const FILE_STATUS_MODIFIED  = 1;
	const FILE_STATUS_MISSING   = 2;
	const FILE_STATUS_REMOVED   = 3;
	const FILE_STATUS_UNKNOWN   = 4;

	public $themename = '';

	public function __construct($location, $theme, $useOverride)
	{
		parent::__construct();

		if (!defined('KOMENTO_CLI')) {
			$this->environment = $this->config->get('komento_environment');
		}

		// For legacy purposes
		if ($this->environment == 'static') {
			$this->environment = 'production';
		}
				
		// Determines if this is an override request based on the location path
		$isOverride = preg_match('/(.*)(-override)$/', $location, $parts);

		// Internally, override is a location.
		if ($useOverride) {
			$location = 'override';
		}
		
		$this->baseurl = JURI::root(true);

		// If cdn is enabled, we need to update the base url
		$cdn = KT::getCdnUrl();

		if ($this->environment == 'production' && $cdn) {
			$this->baseurl = $cdn;
		}
		
		$this->themeName = $theme;
		$this->workspace = $this->getDefaultWorkspace();
		$this->workspace = array_merge($this->workspace, array($location => $theme));
		$this->location = $isOverride ? $parts[1] : $location;
		$this->name = $this->workspace[$isOverride ? 'override' : $this->location];
		$this->isOverride = $isOverride;
	}

	public function getDefaultWorkspace()
	{
		static $defaultWorkspace;

		if (!isset($defaultWorkspace)) {
			$defaultWorkspace = array(
				'site' => strtolower($this->config->get('theme.site')),
				'site_base' => strtolower($this->config->get('theme.site_base')),
				'admin' => strtolower($this->config->get('theme.admin')),
				'admin_base' => strtolower($this->config->get('theme.admin_base'))
			);
		}

		return $defaultWorkspace;
	}

	/**
	 * Retrieves the path of a given folder type
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function folder($name = 'current')
	{
		static $cache = array();

		if (!isset($cache[$name]) || defined('KOMENTO_CLI')) {

			$base = $this->location == 'site' ? JPATH_ROOT : JPATH_ADMINISTRATOR;
			$theme = $this->themeName;

			if ($name == 'site_base') {
				$theme = 'wireframe';
			}

			if ($name == 'admin_base') {
				$theme = 'default';
			}

			if ($name == 'site' || $name == 'site_base' || $name == 'admin' || $name == 'admin_base') {
				$folder = $base . '/components/com_komento/themes/' . $theme . '/styles';
			}

			if ($name == 'cache') {
				$folder = $base . '/components/com_komento/themes/' . $theme . '/styles/_cache';
			}

			if ($name == 'log') {
				$folder = $base . '/components/com_komento/themes/' . $theme . '/styles/_log';
			}

			if ($name == 'media') {
				$folder = KOMENTO_MEDIA;
			}

			if ($name == 'component') {
				$folder = KOMENTO_MEDIA . '/styles';
			}

			if ($name == 'foundry' || $name == 'global') {
				$folder = KOMENTO_MEDIA . '/styles/foundry';
			}

			if ($name == 'root') {
				$folder = JPATH_ROOT;
			}

			if ($name == 'current') {
				$folder = $this->folder($this->location);
			}

			$folder = JPath::clean($folder, '/');

			$cache[$name] = $folder;
		}

		return $cache[$name];
	}

	/**
	 * What the f ?
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function file($filename, $type = null)
	{

		// Default file options
		$defaultOptions = array(
								'location' => $this->location,
								'filename' => 'style',
								'type' => $type,
								'seek' => false
							);

		// Current options
		$options = array();

		// When passing in an object.
		// $this->file(array('location'=>'override', 'type'=>'css'));
		if (is_array($filename)) {
			$options = $filename;

		// When passing in type or filename + type pair.
		// $this->file('css') returns 'path_to_location/style.css'
		// $this->file('photos', 'css') returns 'path_to_location/photos.css'
		} else {
			$numargs = func_num_args();
			if ($numargs===1) $options['type'] = $filename;
			if ($numargs===2) $options['filename'] = $filename;
		}

		// Extract options as variables
		$options = array_merge($defaultOptions, $options);
		extract($options);

		// If we should seek for the file according
		// to the list of import ordering locations.
		if ($seek) {

			// Get list of import ordering locations
			$locations = KomentoStylesheetCompiler::importOrdering($this->location . ($this->isOverride ? '-override' : ''));

			// Go through each of the location
			foreach ($locations as $location) {

				$file = $this->file(array(
					'location' => $location,
					'filename' => $filename,
					'type' => $type
				));

				// and return if the file exists
				if (JFile::exists($file)) return $file;
			}

			// If file could not be found, return file from current location.
			$file = $this->file(array(
				'location' => $this->location,
				'filename' => $filename,
				'type' => $type
			));

			return $file;
		}

		// Construct filename without extension
		$folder = $this->folder($location);

		switch ($type) {

			case 'worksheet':
			case 'less':
				$file = "$folder/$filename.less";

				break;

			case 'stylesheet':
			case 'css':
				$file = "$folder/$filename.css";
				break;

			case 'minified':
				$file = "$folder/$filename.min.css";
				break;

			case 'manifest':
			case 'json':
				$file = "$folder/$filename.json";
				break;

			case 'fallback':
				$file = "$folder/$filename.default.css";
				break;

			case 'config':
			case 'xml':
				$file = "$folder/$filename.default.xml";
				break;

			case 'log';
				$folder = $this->folder('log');
				$file = "$folder/$filename.json";
				break;

			case 'cache':
				$folder = $this->folder('cache');
				$file = "$folder/$filename.json";
				break;

			case 'variables':
				$file = "$folder/variables.less";
				break;
		}

		return $file;
	}

	public function uri($filename, $type=null)
	{
		$path = is_array($filename) ? $this->path($filename) : $this->path($filename, $type);

		$uri = $this->baseurl . '/' . $path;
		
		return $uri;
	}

	public function cdn($filename, $type=null)
	{
		$path = is_array($filename) ? $this->path($filename) : $this->path($filename, $type);

		$NS = $this->ns . '_';

		if (defined($NS . 'JOOMLA_CDN')) {

			$passiveCdn = false;
			if (defined($NS . 'PASSIVE_CDN')) {
				$passiveCdn = constant($NS . 'PASSIVE_CDN');
			}

			// Don't rewrite url if we're on passive CDN.
			if (!$passiveCdn) {
				$root_uri = constant($NS . 'JOOMLA_CDN');
			}
		}

		$uri = $this->baseurl . '/' . $path;

		return $uri;
	}

	public function path($filename, $type=null)
	{
		$path = is_array($filename) ? $this->file($filename) : $this->file($filename, $type);

		$path = $this->strip_root($path);

		return $path;
	}

	public function strip_root($path='')
	{
		$NS = $this->ns . '_';
		$root = constant($NS . 'JOOMLA');
		$root_win = str_replace('\\', '/', $root);

		if (strpos($path, $root)===0) {
			$path = substr_replace($path, '', 0, strlen($root));
		} else if (strpos($path, $root_win)===0) {
			$path = substr_replace($path, '', 0, strlen($root_win));
		}

		// Strip trailing slash
		return substr($path, 1);
	}

	public function relative($dest, $root='', $dir_sep='/')
	{
		$root = explode($dir_sep, $root);
		$dest = explode($dir_sep, $dest);
		$path = '.';
		$fix = '';
		$diff = 0;

		for ($i = -1; ++$i < max(($rC = count($root)), ($dC = count($dest)));) {

			if (isset($root[$i]) and isset($dest[$i])) {

				if ($diff) {
					$path .= $dir_sep. '..';
					$fix .= $dir_sep. $dest[$i];
					continue;
				}

				if ($root[$i] != $dest[$i]) {
					$diff = 1;
					$path .= $dir_sep. '..';
					$fix .= $dir_sep. $dest[$i];
					continue;
				}

			} elseif (!isset($root[$i]) and isset($dest[$i])) {

				for($j = $i-1; ++$j < $dC;) {
					$fix .= $dir_sep. $dest[$j];
				}
				break;

			} elseif (isset($root[$i]) and !isset($dest[$i])) {

				for($j = $i-1; ++$j < $rC;) {
					$fix = $dir_sep. '..'. $fix;
				}
				break;
			}
		}

		$rel = $path . $fix;
		$rel = (substr($rel, 0, 2)=='./') ? substr($rel, 2) : '';

		return $rel;
	}

	/**
	 * Creates a new compiler instance
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function compiler()
	{
		$compiler = new KomentoStylesheetCompiler($this);

		return $compiler;
	}

	public function minifier()
	{
		if (!isset($minifier)) {
			$minifier = new KomentoStylesheetMinifier($this);
		}

		return $minifier;
	}

	public function builder()
	{
		if (!isset($builder)) {
			$builder = new KomentoStylesheetBuilder($this);
		}

		return $builder;
	}

	/**
	 * Allows caller to compile the less files and combine them into a single style.css file
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function compile($section, $options=array())
	{
		$compiler = $this->compiler();
		$task = $compiler->run($section, $options);
		return $task;
	}

	/**
	 * Minifies the css file
	 *
	 * @since	2.0
	 * @access	public
	 */
	public function minify($section, $options=array())
	{
		$minifier = $this->minifier();
		$task = $minifier->run($section, $options);
		return $task;
	}

	public function build($preset = 'cache', $options=array())
	{
		$builder = $this->builder();
		$task = $builder->run($preset, $options);
		return $task;
	}

	public function type()
	{

		// static $type;

		if (isset($type)) return $type;

		// ATS
		$manifestFile = $this->file('manifest');
		if (JFile::exists($manifestFile)) {
			$type = 'ats';
			return $type;
		}

		// LESS
		$lessFile = $this->file('less');
		if (JFile::exists($lessFile)) {
			$type = 'less';
			return $type;
		}

		// CSS
		$cssFile = $this->file('css');
		if (JFile::exists($cssFile)) {
			$type = 'css';
			return $type;
		}

		// Fallback is always css
		return 'css';
	}

	public function manifest() {

		// static $manifestContent;

		// Manifest content loaded before, just return it.
		if (isset($manifestContent)) {
			return $manifestContent;
		}

		$manifestFile = $this->file('manifest');

		// If manifest file exists,
		if (JFile::exists($manifestFile)) {

			// read manifest file,
			$manifestData = JFile::read($manifestFile);

			// and parse manifest data.
			$manifestContent = json_decode($manifestData, true);
		}

		// If no manifest file found or manifest could not be parsed, assume simple stylesheet.
		// Simple stylesheet does not contain sections, the bare minimum is a single "style.css" file.
		// If it has a "style.less" file, then this less file is considered the source stylesheet where "style.css" is compiled from, else "style.css" is considered the source stylesheet.
		if (empty($manifestContent) || !is_array($manifestContent)) {
			$manifestContent = array('style' => array('style'));
		}

		return $manifestContent;
	}

	public function sections() {

		// static $sections;

		if (isset($sections)) {
			return $sections;
		}

		// Get manifest
		$manifest = $this->manifest();

		// Merge all sections in a single array
		$sections = array();
		foreach ($manifest as $group => $_sections) {
			$sections = array_merge($sections, $_sections);
		}

		// Remove duplicates
		$sections = array_unique($sections);

		return $sections;
	}

	public function log($section='style') {

		// If log file does not exist, stop.
		$logFile = $this->file($section, 'log');
		if (!JFile::exists($logFile)) return false;

		// If log file could not be read, stop.
		$logContent = JFile::read($logFile);
		if (!$logContent) return false;

		$log = json_decode($logContent, true);

		return $log;
	}

	public function sectionId($section) {
		return $this->location . '-' . $this->workspace[$this->location] . '-' . $section;
	}

	public function strip_folder($path, $folders=array()) {

		// static $folders;

		// Generate a list of folders to strip
		if (empty($folders)) {

			$locations = KomentoStylesheetCompiler::importOrdering($this->location . ($this->isOverride ? '-override' : ''));
			$folders = array();

			foreach ($locations as $location) {
				$folder     = $this->folder($location);
				$folder_win = str_replace('\\', '/', $folder);
				$folders[] = $folder;
				$folders[] = $folder_win;
			}
		}

		$found = false;
		foreach($folders as $folder) {
			if (strpos($path, $folder)===0) {
				$path = substr_replace($path, '', 0, strlen($folder) + 1);
				$found = true;
				break;
			}
		}

		// Fallback if folder was not stripped
		if (!$found) $path = basename($path);

		// Strip extension
		$path = preg_replace("/\\.[^.\\s]{3,4}$/", "", $path);

		return $path;
	}

	public function imports($section) {

		// static $imports;
		if (!isset($imports)) $imports = array();
		if (isset($imports[$section])) return $imports[$section];

		// If cache file does not exist, stop.
		$cacheFile = $this->file($section, 'cache');
		if (!JFile::exists($cacheFile)) return false;

		// If unable to read file, stop.
		$cacheContent = JFile::read($cacheFile);
		if (!$cacheFile) return false;

		// If cache structure is invalid, stop.
		$cache = json_decode($cacheContent, true);
		if (!is_array($cache)) return false;

		$NS = $this->ns . '_';

		$status = array();
		$files  = $cache['files'];

		foreach ($files as $file => $modified) {

			// Properties
			$name   = $this->strip_folder($file);
			$path   = $this->strip_root($file);
			$uri    = constant($NS . 'JOOMLA_URI') . '/' . $path;
			$state  = 'ready';
			$exists = JFile::exists($file);

			// Missing file
			if (!$exists) $state = 'missing';

			// Modified file
			$current = @filemtime($file);
			if ($current > $modified) {
				$state = 'modified';
			}

			$status[] = (object) array(
				'name'     => $name,
				'path'     => $path,
				'uri'      => $uri,
				'current ' => $current,
				'modified' => $modified,
				'state'    => $state
			);
		}

		$imports[$section] = $status;

		return $status;
	}

	public function override() {

		if (empty($this->overrideStylesheet)) {
			$this->overrideStylesheet = new $this->class($this->ns, $this->workspace, $this->location . '-override');
		}

		return $this->overrideStylesheet;
	}

	public function overrides() {

		// static $overrides;

		if (isset($overrides)) return $overrides;

		// Prepare keywords for path building.
		$NS = $this->ns . '_';
		$administrator = ($this->location=='admin') ? '/administrator/' : '';
		$component = ($this->location=='module') ? $this->workspace['module'] : constant($NS . 'COMPONENT_NAME');

		// Determine path for Joomla template folder because frontend and backend is different.
		$templateFolder = constant($NS . 'JOOMLA') . "$administrator/templates";

		// Get a list of template folders.
		$templates = JFolder::folders($templateFolder);

		// Go through each template folder to see if there is a stylesheet override.
		$overrides = array();
		foreach ($templates as $template) {

			$overrideFolder = "$templateFolder/$template/html/$component/styles";

			// If override folder exists, add to override list.
			if (JFolder::exists($overrideFolder)) {
				$overrides[] = $template;
			}
		}

		return $overrides;
	}

	public function hasOverride() {

		if ($this->isOverride) return false;

		$overrideFile = $this->file(array('location' => 'override', 'type' => 'css'));
		$hasOverride = JFile::exists($overrideFile);

		return $hasOverride;
	}

	public function modules()
	{
		// static $modules;

		if (isset($modules)) return $modules;

		// Prepare keywords for path building.
		$NS = $this->ns . '_';
		$modulePath = constant($NS . 'JOOMLA_MODULES');
		$modulePrefix = 'mod_' . constant($NS . 'IDENTIFIER');

		// Get a list of modules that starts with the component identifier,
		// e.g. mod_easysocial, mod_easyblog, mod_easydiscuss.
		$_modules = JFolder::folders($modulePath, $modulePrefix);

		// Go through each module folder and see if there is a styles folder
		$modules = array();
		foreach ($_modules as $module) {

			// If styles folder exists, add to module list.
			if (JFolder::exists("$modulePath/$module/styles")) {
				$modules[] = $module;
			}
		}

		return $modules;
	}

	/**
	 * Attaches stylesheet on the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function attach($minified = true, $allowOverride = true)
	{
		// Default settings
		$build = false;

		// If we're in a development environment, always cache compile stylesheet and
		// attached uncompressed stylesheets.
		if ($this->environment == 'development') {
			$build = true;
			$minified = false;
		}

		// Rebuild stylesheet on page load if necessary
		if ($build) {
			$task = $this->build($this->environment);

			// This generates build log in the browser console.
			if ($this->environment == 'development') {
				$script = KT::script();
				$script->set('task', $task);
				$script->attach('admin/stylesheet/log');
			}
		}

		// Determines if the viewer is viewing the admin section.
		$app = JFactory::getApplication();
		$isAdmin = $app->isAdmin();

		if ($isAdmin) {
			$allowOverride = false;

			if ($this->environment == 'production') {
				$hash = md5(KT::getLocalVersion());
				$uri = rtrim(JURI::root(), '/') . '/administrator/components/com_komento/themes/default/styles/style.min.css?' . $hash . '=1';

				return $this->doc->addStylesheet($uri);
			}

			$this->process($minified, $allowOverride);
			return;
		}		

		$direction = $this->doc->getDirection();

		// For rtl, we need to handle this manually
		if ($direction == 'rtl' && $this->themeName == 'wireframe') {
			$rtlStylesheet = $this->baseurl . '/components/com_komento/themes/wireframe/styles/style-rtl';

			if ($this->environment != 'development') {
				$rtlStylesheet .= '.min';
			}

			$rtlStylesheet .= '.css';

			$this->doc->addStylesheet($rtlStylesheet);
		} else {

			// Production mode
			if ($this->environment == 'production') {
				$hash = md5(KT::getLocalVersion());
				$uri = rtrim(JURI::root(), '/') . '/components/com_komento/themes/' . $this->themeName . '/styles/style.min.css?' . $hash . '=1';

				$this->doc->addStylesheet($uri);
			} 

			if ($this->environment == 'development') {
				$this->process($minified, $allowOverride);
			}
		}
		

		// Site location
		if ($this->location == 'site') {
			
			// Check if custom.css exists on the site as template overrides
			$file = JPATH_ROOT . '/templates/' . $this->app->getTemplate() . '/html/com_komento/css/custom.css';
			$exists = JFile::exists($file);

			if ($exists) {
				$customCssFile = $this->baseurl . '/templates/' . $this->app->getTemplate() . '/html/com_komento/css/custom.css';

				$this->doc->addStylesheet($customCssFile);
			}

			if ($this->config->get('enable_backgrounds')) {
				$theme = KT::themes();
				$output = $theme->output('site/structure/custom.css');

				$this->doc->addCustomTag($output);
			}
		}
	}

	public function process($minified = true, $allowOverride = true)
	{
		$document = JFactory::getDocument();
		$app = JFactory::getApplication();
		$isAdmin = $app->isAdmin();

		// Load manifest file.
		$manifest = $this->manifest();

		$uris = array();

		foreach ($manifest as $group => $sections) {

			// Determine the type of stylesheet to attach
			$type = $minified ? 'minified' : 'css';

			// Build path options
			$target = array(
				'location' => $this->isOverride ? 'override' : $this->location,
				'filename' => $group,
				'type' => $type
			);

			// Fallback to css if minified not exists,
			// only for template overrides because
			// we don't want too much disk i/o.
			if ($this->isOverride && $minified) {

				$minifiedFile = $this->file($target);

				if (!JFile::exists($minifiedFile)) {
					$target['type'] = 'css';
				}
			}

			// Get stylesheet uri.
			// Do not attach CDN uri for backend
			if ($isAdmin) {
				$uri = $this->uri($target);

			// Prefer CDN over site uri if possible
			} else {
				$uri = $this->cdn($target);
			}

			$uris[] = $uri;

			// Stop because this stylesheet
			// has been attached.
			if (isset(self::$attached[$uri])) {
				return;
			}

			// Attach to document head.
			$document->addStyleSheet($uri);

			// Remember this stylesheet so
			// we won't reattach it again.
			self::$attached[$uri] = true;
		}

		return $uris;
	}

	public function status($section='style')
	{
		// static $status;
		if (!isset($status)) $status = array();
		if (isset($status[$section])) return $status[$section];

		// Stylesheet
		if ($section=='style') {
			$manifest  = $this->manifest();
			$filenames = array_keys($manifest);
			$type      = $this->type();

		// Section
		} else {
			$filenames = array($section);
			$type      = 'section';
		}

		$files = array();
		$filetypes = self::$filetypes[$type];

		foreach ($filenames as $filename) {

			foreach ($filetypes as $filetype) {

				// Options to get file path
				$options = array(
					'filename' => $filename,
					'type'     => $filetype,
					'seek'     => $type=='section' && $filetype=='less'
				);

				// Get properties of this file
				$file     = $this->file($options);
				$uri      = $this->uri($options);
				$path     = $this->path($options);
				$exists   = JFile::exists($file);
				$size     = null;
				$modified = null;
				$rules    = 0;
				$state    = 'unknown';

				if ($exists) {
					$size     = @filesize($file);
					$modified = @filemtime($file);
					$state    = 'ready';

					if ($filetype=='css' || $filetype=='minified') {
						$content  = JFile::read($file);
						$rules = KomentoStylesheetAnalyzer::rules($content);
						$rules = count($rules);
					}
				} else {
					$state   = 'missing';
				}

				// Determine file status
				// TODO: Merge "changes()" method within this method so we can track new/missing/modified files.

				$files[] = (object) array(
					'name'     => basename($file),
					'path'     => $path,
					'uri'      => $uri,
					'exists'   => $exists,
					'size'     => $size,
					'modified' => $modified,
					'state'    => $state,
					'rules'    => $rules
				);
			}
		}

		$status[$section] = $files;

		return $files;
	}

	public function changes($fast=false)
	{
		// static $result;

		$key = (string) $fast;

		if (!isset($result)) $result = array();

		// Return cached result if possible
		if (isset($result[$key])) return $result[$key];

		$task = new KomentoStylesheetTask('Detect changes in stylesheets');

		$cacheFile = $this->stylesheet->file('cache');
		$cache = null;

		if (!JFile::exists($cacheFile)) {

			$cacheData = JFile::read($cacheFile);

			if (!$cacheData) {
				$task->report("No cache file found at '$cacheFile'.", KomentoStylesheetTask::MESSAGE_INFO);
				return $task->reject();
			}

			$cache = json_decode($cacheData);
		}

		if (!is_array($cache)) {
			$task->report("Incompatible style cache structure or invalid cache file was provided at '$cacheFile'.");
			return $task->reject();
		}

		// Result dataset
		$changes  = array();
		$status   = array();
		$modified = false;

		// Get cache to detect missing or modified file.
		$files = $cache->files;

		// Get sections to detect new or deleted files.
		$sections = $this->sections();

		foreach ($sections as $section) {

			$filename = $section . '.css';

			if (!isset($files[$filename])) {
				$files[$filename] = null;
			}
		}

		// Go through each file to look for changes
		foreach ($files as $filename => $timestamp) {

			// For fast change detection. Used by hasChanges().
			if ($fast && $modified) break;

			// Get file path
			$file = $this->file($this->filename, 'css');
			$state = self::FILE_STATUS_UNCHANGED;

			// If the file does not exist anymore
			if (!JFile::exists($file)) {

				// If the file still exist in the manifest,
				// then this file is missing.
				if (isset($sections[$filename])) {
					$task->report("Missing file '$file'.");
					$state = self::FILE_STATUS_MISSING;

				// Else this file has been removed.
				} else {
					$task->report("Removed file '$file'.");
					$$state = self::FILE_STATUS_REMOVED;
				}

			} else {

				// Retrieve file's modified time
				$modifiedTime = @filemtime($file);

				// Skip and generate a warning if unable to retrieve timestamp
				if ($modifiedTime===false) {
					$task->report("Unknown modified time for '$file'.");
					$$state = self::FILE_STATUS_UNKNOWN;
				}

				// File is new
				if (is_null($timestamp)) {
					$task->report("New file found '$file'.");
					$state = self::FILE_STATUS_NEW;

				// File is modified
				} elseif ($timestamp < $modifiedTime) {
					$task->report("Modified file found '$file'.");
					$state = self::FILE_STATUS_MODIFIED;
				}
			}

			// Add to change list
			$changes[$file] = $state;

			// Increase state count
			if (isset($status[$state])) {
				$status[$state] = 0;
			}
			$status[$state]++;

			// Flag to indicate this stylesheet is modified
			if (!$modified && $state!==0) {
				$modified = true;
			}
		}

		// If there are no changes in this stylesheet, report it.
		if (!$modified) {
			$task->report('There are no changes in this stylesheet.');
		}

		$task->result = (object) array(
			'status'   => $status,
			'changes'  => $changes,
			'modified' => $modified
		);

		$result[$key] = $result;

		return $task->resolve();
	}

	public function hasChanges() {

		$task = $this->changes(true);

		// Unable to detect changes
		if ($task->failed) return null;

		return $task->result->modified;
	}

	public function purge() {

		// Create compile task object.
		$task = new KomentoStylesheetTask("Purging stylesheet cache & log files.");

		$cacheFolder = $this->folder('cache');

		if (JFolder::exists($cacheFolder)) {

			if (JFolder::delete($cacheFolder)) {
				$task->report("Deleted cache folder '$cacheFolder'.");
			} else {
				$task->report("Unable to delete cache folder '$cacheFolder'.");
			}
		}

		$logFolder = $this->folder('log');

		if (JFolder::exists($logFolder)) {

			if (JFolder::delete($logFolder)) {
				$task->report("Deleted log folder '$logFolder'.");
			} else {
				$task->report("Unable to delete log folder '$logFolder'.");
			}
		}

		return $task->resolve();
	}
}
