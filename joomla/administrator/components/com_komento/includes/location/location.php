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

class KomentoLocation extends KomentoBase
{
	public $key = null;

	/**
	 * Determines if the location services are enabled
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function isEnabled()
	{
		static $enabled = null;

		if (is_null($enabled)) {
			$enabled = false;

			if ($this->config->get('enable_location') && $this->config->get('location_key')) {
				$enabled = true;
			}
		}

		return $enabled;
	}
}
