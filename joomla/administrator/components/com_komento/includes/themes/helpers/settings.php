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

class KomentoThemesSettings
{
	/**
	 * Generates a settings row in the panel body
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public static function label($text, $helpText = '', $help = true, $columns = 5)
	{
		if ($help && !$helpText) {
			$helpText = JText::_($text . '_DESC');
		}

		$text = JText::_($text);

		$theme = KT::themes();
		$theme->set('columns', $columns);
		$theme->set('text', $text);
		$theme->set('help', $help);
		$theme->set('helpText', $helpText);

		$output = $theme->output('admin/helpers/settings/label');

		return $output;
	}

	/**
	 * Renders a textbox for settings
	 *
	 * @since	4.2.0
	 * @access	public
	 */
	public static function textbox($name, $title, $desc = '', $options = array(), $instructions = '', $class = '')
	{
		$theme = KT::themes();
		
		if (empty($desc)) {
			$desc = $title . '_DESC';
		}

		$size = '';
		$postfix = '';
		$prefix = '';
		$attributes = '';
		$type = 'text';
		$wrapperAttributes = '';
		$visible = true;

		$config = KT::config();
		$value = $config->get($name);

		if (isset($options['type'])) {
			$type = $options['type'];
		}

		if (isset($options['attributes'])) {
			$attributes = $options['attributes'];
		}

		if (isset($options['wrapperAttributes'])) {
			$wrapperAttributes = $options['wrapperAttributes'];
		}
		
		if (isset($options['postfix'])) {
			$postfix = $options['postfix'];
		}

		if (isset($options['visible']) && !$options['visible']) {
			$visible = false;
		}

		if (isset($options['prefix'])) {
			$prefix = $options['prefix'];
		}

		if (isset($options['size'])) {
			$size = $options['size'];
		}

		if (isset($options['defaultValue'])) {
			$value = $config->get($name, $options['defaultValue']);
		}

		$theme->set('wrapperAttributes', $wrapperAttributes);
		$theme->set('value', $value);
		$theme->set('attributes', $attributes);
		$theme->set('type', $type);
		$theme->set('size', $size);
		$theme->set('class', $class);
		$theme->set('instructions', $instructions);
		$theme->set('name', $name);
		$theme->set('title', $title);
		$theme->set('desc', $desc);
		$theme->set('prefix', $prefix);
		$theme->set('postfix', $postfix);
		$theme->set('visible', $visible);

		$contents = $theme->output('admin/helpers/settings/textbox');

		return $contents;
	}

	/**
	 * Renders a toggle button settings
	 *
	 * @since	3.1.0
	 * @access	public
	 */
	public static function toggle($name, $title, $desc = '', $attributes = '', $note = '', $wrapperAttributes = '')
	{
		$theme = KT::themes();

		if (empty($desc)) {
			$desc = $title . '_DESC';
		}
		
		if ($note) {
			$note = JText::_($note);
		}

		if (is_array($wrapperAttributes)) {
			$wrapperAttributes = implode(' ', $wrapperAttributes);
		}

		$theme->set('note', $note);
		$theme->set('name', $name);
		$theme->set('title', $title);
		$theme->set('desc', $desc);
		$theme->set('attributes', $attributes);
		$theme->set('wrapperAttributes', $wrapperAttributes);

		$contents = $theme->output('admin/helpers/settings/toggle');

		return $contents;
	}
}
