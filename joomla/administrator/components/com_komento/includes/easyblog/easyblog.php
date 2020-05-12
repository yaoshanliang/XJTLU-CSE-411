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

class KomentoEasyBlog extends KomentoBase
{
	private $file = null;

	public function __construct()
	{
		// KT::loadLanguage(JPATH_ROOT);

		$this->file = JPATH_ADMINISTRATOR . '/components/com_easyblog/includes/easyblog.php';
		parent::__construct();
	}

	/**
	 * Determines if EasyBlog is installed on the site.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function exists()
	{
		static $loaded = false;

		if ($loaded) {
			return true;
		}

		if (!JFile::exists($this->file)) {
			return false;
		}

		require_once($this->file);

		$loaded = true;

		return true;
	}
}
