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

// jimport('joomla.application.component.controller');

class KomentoParentController extends JControllerLegacy
{
	public function __construct($config = array())
	{
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
		$this->doc = JFactory::getDocument();
		$this->my = JFactory::getUser();
		$this->config = KT::config();
		$this->info = KT::info();
		
		return parent::__construct($config);
	}

	/**
	 * Allows caller to get the current view.
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCurrentView()
	{
		$className 	= get_class( $this );

		// Remove the EasySocialController portion from it.
		$className 	= str_ireplace('KomentoController', '', $className);

		$backend = true;

		$view = KT::view($className, $backend);

		return $view;
	}
}