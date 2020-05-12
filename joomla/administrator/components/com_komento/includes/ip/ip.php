<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class KomentoIp
{
	static public $rules = array();

	static function getIP()
	{
		return JRequest::getVar('REMOTE_ADDR', '', 'SERVER');
	}

	public function getRule()
	{
		$component	= KT::getCurrentComponent();
		$ip			= KomentoIpHelper::getIP();

		$rules		= KT::getModel( 'ipfilter' )->getRule( $component, $ip );
	}
}
