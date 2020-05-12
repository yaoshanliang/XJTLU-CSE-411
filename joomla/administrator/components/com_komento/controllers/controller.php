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

require_once(__DIR__ . '/parent.php');

class KomentoController extends KomentoParentController
{
	/**
	 * This is the center of the brain to process all views.
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function display($cacheable = false, $urlparams = false)
	{
		$type = $this->doc->getType();
		$viewName = $this->input->get('view', $this->getName(), 'cmd');
		$viewLayout	= $this->input->get('layout', 'default', 'cmd');

		$view = $this->getView($viewName, $type, '');
		$view->setLayout($viewLayout);

		if ($viewLayout != 'default') {	
			if (!method_exists($view, $viewLayout)) {
				$view->display();
			} else {
				call_user_func_array(array($view, $viewLayout), array());
			}
		} else {
			$view->display();
		}
	}

	/**
	 * Checks if the current viewer can really access this section
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function checkAccess($rule)
	{
		if (!$this->my->authorise($rule , 'com_komento')) {
			$this->app->enqueueMessage('JERROR_ALERTNOAUTHOR', 'error');
			return $this->app->redirect('index.php?option=com_komento');
		}
	}
}
