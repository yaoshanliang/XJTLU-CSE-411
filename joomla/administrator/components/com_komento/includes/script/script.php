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

require_once(KOMENTO_LIB . '/template/template.php');

class KomentoScript extends KomentoTemplate
{
	public $extension = 'js';

	public $scriptTag = false;
	public $openingTag = '<script>';
	public $closingTag = '</script>';

	public $CDATA = false;
	public $safeExecution = false;

	public $header = '';
	public $footer = '';


	/**
	 * Parses a script file.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function parse($vars = null)
	{
		// Pass to the parent to process the theme file
		$vars = parent::parse($vars);
		$script	= $this->header . $vars . $this->footer;

		// Do not reveal root folder path.
		$file = str_ireplace(JPATH_ROOT, '', $this->file);

		// Replace \ with / to avoid javascript syntax errors.
		$file = str_ireplace('\\', '/', $file);

		$cdata = $this->CDATA;
		$scriptTag = $this->scriptTag;
		$safeExecution = $this->safeExecution;

ob_start();
include(KOMENTO_MEDIA . '/scripts/template.php');
$contents = ob_get_contents();
ob_end_clean();

		return $contents;
	}

	/**
	 * Attaches files to the header.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function attach($path = null)
	{
		// Keep original file value
		if (!is_null($path)) {
			$_file = $this->file;
			$this->file = $this->resolve($path, $this->extension);
		}

		// Keep current value
		$_scriptTag = $this->scriptTag;
		$_CDATA = $this->CDATA;

		// Reset to false
		$this->scriptTag = false;
		$this->CDATA = false;

		$output = $this->parse();

		KT::document()->addInlineScript($output);

		// Restore current value
		$this->scriptTag = $_scriptTag;
		$this->CDATA = $_CDATA;

		// Restore original file value
		if (!is_null($path)) {
			$this->file = $_file;
		}
	}

	/**
	 * Allows inclusion of scripts within another script
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function output($file = null, $vars = null)
	{
		$template = $this->getTemplate($file);

		// Ensure that the script file exists
		if (!JFile::exists($template->script)) {
			return;
		}

		$this->file = $template->script;

		$output = $this->parse();

		return $output;
	}
}
