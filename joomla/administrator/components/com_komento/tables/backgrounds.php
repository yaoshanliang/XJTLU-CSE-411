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

class KomentoTableBackgrounds extends KomentoParentTable
{
	public $id = null;
	public $title = null;
	public $params = null;
	public $type = null;
	public $published = null;
	public $created = null;

	public function __construct(&$db)
	{
		parent::__construct('#__komento_backgrounds', 'id', $db);
	}

	public function getParams()
	{
		return new JRegistry($this->params);
	}

	/**
	 * Retrieve the color in RGB format
	 *
	 * @since   3.1
	 * @access  public
	 */
	public function getRGBColor($variable)
	{
		$params = $this->getParams();
		$hexCode = $params->get($variable);

		return KT::string()->hexToRGB($hexCode);
	}

	/**
	 * Retrieve the direction of the gradient
	 *
	 * @since   3.1
	 * @access  public
	 */
	public function getGradientDirection()
	{
		return $this->type == 'vertical' ? 'right' : 'bottom';
	}
}
