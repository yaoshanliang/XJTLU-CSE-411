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

class KomentoProfilesDefault extends KomentoBase
{
	protected $profile = null;

	public function __construct($profile)
	{
		parent::__construct();
		$this->profile = $profile;
	}

	public function exists()
	{
		return true;
	}

	public function getAvatar()
	{
		$avatar = rtrim(JURI::root(), '/') . '/media/com_komento/images/avatar/default.png';
		return $avatar;
	}

	public function getLink($email = null, $website = '')
	{
		$permalink = 'javascript: void(0);';

		if (!$this->profile->id && $config->get('enable_guest_link') && $website) {
			$permalink = $website;
		}

		return $permalink;
	}

}
