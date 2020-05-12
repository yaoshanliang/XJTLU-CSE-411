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

class KomentoThemesForm
{
	/**
	 * Floating label with input form
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public static function floatinglabel($label, $name, $type = 'textbox', $value = '')
	{
		// This currently only supports textbox and password
		$supported = array('textbox', 'password');

		if (!in_array($type, $supported)) {
			return "";
		}

		$label = JText::_($label);
		$id = 'kt-' . str_ireplace(array('.'), '', $name);

		$theme = KT::themes();
		$theme->set('type', $type);
		$theme->set('value', $value);
		$theme->set('label', $label);
		$theme->set('name', $name);
		$theme->set('id', $id);

		$output = $theme->output('site/helpers/form/' . __FUNCTION__);

		return $output;
	}

	/**
	 * Renders a simple password input
	 *
	 * @since   3.1.0
	 * @access  public
	 */
	public static function password($name, $id = null, $value = '', $options = array())
	{
		$class = 'o-form-control';
		$placeholder = '';
		$attributes = '';

		if (isset($options['attr']) && $options['attr']) {
			$attributes = $options['attr'];
		}

		if (isset($options['class']) && $options['class']) {
			$class = $options['class'];
		}

		if (isset($options['placeholder']) && $options['placeholder']) {
			$placeholder = JText::_($options['placeholder']);
		}

		$theme = KT::themes();
		$theme->set('attributes', $attributes);
		$theme->set('name', $name);
		$theme->set('id', $id);
		$theme->set('value', $value);
		$theme->set('class', $class);
		$theme->set('placeholder', $placeholder);

		return $theme->output('site/helpers/form/password');
	}

	/**
	 * Generates a on / off switch
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function toggler($name, $enabled = false, $id = '', $attributes = '')
	{
		if (is_array($attributes)) {
			$attributes = implode(' ', $attributes);
		}

		if (!$id) {
			$id = $name;
		}

		$theme = KT::themes();
		$theme->set('id', $id);
		$theme->set('name', $name);
		$theme->set('enabled', $enabled);
		$theme->set('attributes', $attributes);

		$output = $theme->output('admin/helpers/form/toggler');

		return $output;
	}

	/**
	 * Renders a text input
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public static function textbox($name, $value = '', $placeholder = '', $class = '', $options = array())
	{
		if ($placeholder) {
			$placeholder = JText::_($placeholder);
		}
		
		if (isset($options['class'])) {
			$class = $options['class'];
		}

		$attributes = '';

		if (isset($options['attr'])) {
			$attributes = $options['attr'];
		}

		$theme = KT::themes();
		$theme->set('attributes', $attributes);
		$theme->set('name', $name);
		$theme->set('value', $value);
		$theme->set('class', $class);
		$theme->set('placeholder', $placeholder);

		$output = $theme->output('site/helpers/form/textbox');

		return $output;
	}

	/**
	 * Generates token for a form
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function token()
	{
		$token = KT::token();

		$theme = KT::themes();
		$theme->set('token', $token);

		$content = $theme->output('admin/helpers/form/token');

		return $content;
	}

	/**
	 * Generates a dropdown list of available extensions integration
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function extensions($inputName, $selected = '', $attributes = array())
	{
		// Get a list of components
		$extensions = KT::components()->getAvailableComponents();

		if (is_array($attributes)) {
			$attributes = implode(' ', $attributes);
		}

		$theme = KT::themes();
		$theme->set('inputName', $inputName);
		$theme->set('extensions', $extensions);
		$theme->set('attributes', $attributes);
		$theme->set('selected', $selected);
			
		$content = $theme->output('admin/helpers/form/extensions');

		return $content;
	}

	/**
	 * Generates a hidden input for encoded return urls
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function returnUrl($url = '')
	{
		if (!$url) {
			$app = JFactory::getApplication();
			$url = $app->input->get('currentUrl', '', 'default');
		}

		$theme = KT::themes();
		$theme->set('url', $url);

		$content = $theme->output('admin/helpers/form/returnurl');

		return $content;
	}

	/**
	 * Renders hidden input with token and task
	 *
	 * @since	3.0
	 * @access	public
	 */
	public static function action($task = '')
	{
		$theme = KT::themes();

		$tasks = explode(".", $task);

		$controller = $tasks[0];
		$task = $tasks[1];

		$theme->set('task', $task);
		$theme->set('controller', $controller);
		$output	= $theme->output('admin/helpers/form/action');

		return $output;
	}

	/**
	 * Renders a colour picker input
	 *
	 * @since	3.1
	 * @access	public
	 */
	public static function colorpicker($name, $value = '', $revert = '')
	{
		static $script = null;

		$loadScript = false;
		
		if (is_null($script)) {
			$loadScript = true;
			$script = true;
		}

		JHTML::_('behavior.colorpicker');

		$theme = KT::themes();
		$theme->set('loadScript', $loadScript);
		$theme->set('name', $name);
		$theme->set('value', $value);
		$theme->set('revert', $revert);

		$output = $theme->output('admin/helpers/form/colorpicker');

		return $output;
	}
}
