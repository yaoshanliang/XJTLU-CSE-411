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

KT::import('admin:/includes/date/date');
KT::import('admin:/includes/string/string');
KT::import('admin:/includes/string/string');

KT::import('admin:/includes/template/template');

class KomentoThemes extends KomentoTemplate
{
	// Holds all the template variables
	public $vars = null;
	private $_system = null;
	protected $_json = null;

	private $scripts = array();

	// User selected theme
	protected $_direction = null;
	protected $_themeInfo = array();

	/**
	 * Determines if this view is for the adminv iew
	 *
	 * @param	object
	 */
	public $admin = false;

	/**
	 * Holds the current view object
	 *
	 * @param	object
	 */
	public $view = null;

	public function __construct($overrideTheme = 'kuro', $options = array())
	{
		parent::__construct();

		$this->currentTheme = $this->config->get('layout_theme');

		// Determine if this is an admin location
		if (isset($options['admin']) && $options['admin']) {
			$this->admin = true;
		}

		// If a view is provided into the theme, the theme files could call methods from a view
		if (isset($options['view']) && is_object($options['view'])) {
			$this->view = $options['view'];
		}
	}

	public function getDirection()
	{
		if ($this->_direction === null) {
			$document	= JFactory::getDocument();
			$this->_direction	= $document->getDirection();
		}

		return $this->_direction;
	}

	public function getNouns($text, $count, $includeCount = false)
	{
		return KT::string()->getNoun($text, $count, $includeCount);
	}

	public function chopString($string, $length)
	{
		return JString::substr($string, 0, $length);
	}

	public function formatDate($format, $dateString)
	{
		$date = KT::date($dateString);
		return $date->toFormat($format);
	}

	public function set($name, $value)
	{
		$this->vars[$name] = $value;
	}

	/**
	 * Outputs the data from a template file.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function output($namespace = null, $args = null)
	{
		// Try to get the template data.
		$template = $this->getFileStructure($namespace);

		// Template
		$this->file	= $template->file;

		// Get the output
		$output = $this->parse($args);

		// Script
		if (JFile::exists($template->script)) {

			$script = KT::script();
			$script->file = $template->script;
			$script->vars = $this->vars;
			$script->scriptTag	= true;

			$doc = JFactory::getDocument();

			if ($doc->getType() == 'html') {
				KT::addScript($script->parse($args));
			}

			if ($doc->getType() != 'html') {
				$output .= $script->parse($args);
			}
		}

		return $output;
	}

	/**
	 * Cleaner extract method. All variables that are set in $this->vars would be extracted within this scope only.
	 *
	 * @since	3.0
	 * @access	public
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
	 * Renders a nice checkbox switch.
	 *
	 * @param	string	$option		Name attribute for the checkbox.
	 * @param	string	$sate		State of the checkbox, checked or not.
	 * @return	string	HTML output.
	 */
	public function renderCheckbox($option, $state)
	{
		ob_start();
	?>
		<div class="si-optiontap">
			<label class="option-enable<?php echo $state == 1 ? ' selected' : '';?>"><span><?php echo JText::_('COM_KOMENTO_NO_SWITCH');?></span></label>
			<label class="option-disable<?php echo $state == 0 ? ' selected' : '';?>"><span><?php echo JText::_('COM_KOMENTO_YES_SWITCH'); ?></span></label>
			<input name="<?php echo $option; ?>" value="<?php echo $state;?>" type="radio" class="radiobox" checked="checked" style="display: none;" />
		</div>
	<?php
		$html	= ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function escape($val)
	{
		return KT::string()->escape($val);
	}
	
	public function getComponentSelection()
	{
		$availableComponents = KT::components()->getAvailableComponents();

		$options = array($this->renderOption('all', '*'));

		foreach($availableComponents as $row)
		{
			$options[] = $this->renderOption($row, KT::loadApplication($row)->getComponentName());
		}

		$html = JHtml::_('select.genericlist', $options, 'componentSelection', '.fa- class="inputbox componentSelection"', 'value', 'text');

		return $html;
	}

	public function getArticleSelection()
	{
		$options = array($this->renderOption('all', '*'));

		$html = JHtml::_('select.genericlist', $options, 'articleSelection', '.fa- class="inputbox articleSelection"', 'value', 'text');

		return $html;
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
		static $language = false;

		if (!$language) {
			// Load language strings from back end.
			JFactory::getLanguage()->load('com_komento', JPATH_ROOT . '/administrator');
			$language = true;
		}

		$helper	= explode('.', $namespace);
		$helperName	= $helper[0];
		$methodName	= $helper[1];

		$file = dirname(__FILE__) . '/helpers/' . strtolower($helperName) . '.php';

		// Remove the first 2 arguments from the args.
		$args = func_get_args();
		$args = array_splice($args, 1);

		include_once($file);

		$class = 'KomentoThemes' . ucfirst($helperName);

		if (!method_exists($class, $methodName)) {
			return false;
		}

		return call_user_func_array(array($class, $methodName), $args);
	}

	public function getUsergroupsMultilist()
	{
		$usergroups = KT::getUsergroups();

		foreach ($usergroups as $usergroup) {
			$usergroup->treename = str_repeat('.&#160;&#160;&#160;', $usergroup->level) . ($usergroup->level > 0 ? '|_&#160;' : '') . $usergroup->title;
		}

		return $usergroups;
	}

	public function renderOption($value, $text)
	{
		return JHtml::_('select.option', $value, JText::_($text));
	}
}
