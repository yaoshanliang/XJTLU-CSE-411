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

class KomentoDate
{
	private $date = null;

	private static $lang;

	public function __construct($date = 'now', $withoffset = true, $debug = false)
	{
		require_once(__DIR__ . '/helper.php');

		$this->date = new KomentoDateHelper($date, $withoffset);
	}

	public function toFormat($format = 'DATE_FORMAT_LC2', $local = true )
	{
		$format 	= JText::_( $format );
	    return $this->date->toFormat( $format, $local );
	}

	/**
	 * Returns the lapsed time since NOW
	 *
	 * @since	3.0
	 * @access	public
	 */
	public function toLapsed()
	{
		$now = KT::date();
		$time = $now->date->toUnix(true) - $this->date->toUnix(true);

		$tokens = array (
							31536000 	=> 'COM_KOMENTO_X_YEAR',
							2592000 	=> 'COM_KOMENTO_X_MONTH',
							604800 		=> 'COM_KOMENTO_X_WEEK',
							86400 		=> 'COM_KOMENTO_X_DAY',
							3600 		=> 'COM_KOMENTO_X_HOUR',
							60 			=> 'COM_KOMENTO_X_MINUTE',
							1 			=> 'COM_KOMENTO_X_SECOND'
						);
		
		if ($time == 0) {
			return JText::_('COM_KOMENTO_LAPSED_NOW');
		}

		foreach ($tokens as $unit => $key) {
			
			if ($time < $unit) {
				continue;
			}

			$units	= floor( $time / $unit );

			$string = $units > 1 ?  $key . 'S' : $key;
			$string = $string . '_AGO';

			$text   = JText::sprintf(strtoupper($string), $units);
			return $text;
		}

		return JText::_('COM_KOMENTO_ONE_SECOND_AGO');

	}

	/**
	 * Returns the JDate/Datetime object
	 *
	 * @since  1.2
	 * @access public
	 */
	public function getObject()
	{
		return $this->date;
	}

	/**
	 * Middle man method to apply the "modify" method on the helper, but return the current instance instead for chaining.
	 *
	 * @since  1.3.10
	 * @access public
	 */
	public function modify($string)
	{
		$this->date->modify($string);

		return $this;
	}

	public function __call( $method , $args )
	{
		return call_user_func_array( array( $this->date , $method ) , $args );
	}
}
