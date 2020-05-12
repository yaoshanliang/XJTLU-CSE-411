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

require_once(dirname(__FILE__) . '/library.php');

class KomentoAkismet
{
	private $akismet = null;

	/**
	 * Determines if akismet is enabled
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function isEnabled()
	{	
		$config = KT::config();

		return $config->get('antispam_akismet') && !is_null($config->get('antispam_akismet_key'));
	}

	private function init($url = '')
	{
		$config = KT::config();

		if (!$config->get('antispam_akismet_key')) {
			return false;
		}

		if (is_null($this->akismet)) {

			$url = !empty($url) ? $url : JURI::root();
			$this->akismet = new Akismet($url, $config->get('antispam_akismet_key'));
		}

		return $this;
	}

	public function isSpam($comment)
	{
		if (!$this->akismet) {
			if (!$this->init()) {
				return false;
			}
		}

		$data = array();
		$data['author'] = $comment->table->name;
		$data['email'] = $comment->table->email;
		$data['website'] = $comment->table->url;
		$data['body'] = $comment->table->comment;

		$this->akismet->setComment($data);

		// If there are errors, we just assume that everything is fine so the entire
		// operation will still work correctly.
		if ($this->akismet->errorsExist()) {
			return false;
		}

		return $this->akismet->isSpam();
	}

	public function submitSpam($data)
	{
		if (!$this->akismet) {
			if (!$this->init()) {
				return false;
			}
		}

		$this->akismet->setComment($data);

		$this->akismet->submitSpam();

		if ($this->akismet->errorsExist()) {
			return false;
		}

		return true;
	}

	public function submitHam($data)
	{
		if (!$this->akismet) {
			if (!$this->init()) {
				return false;
			}
		}

		$this->akismet->setComment($data);

		$this->akismet->submitHam();

		if ($this->akismet->errorsExist()) {
			return false;
		}

		return true;
	}
}
