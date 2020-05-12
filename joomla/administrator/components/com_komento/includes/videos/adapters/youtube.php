<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

class KomentoVideoYoutube
{
	private function getCode($url)
	{
		/* match http://www.youtube.com/watch?v=TB4loah_sXw&feature=fvst */
		preg_match('/youtube.com\/watch\?v=(.*)(?=&)/is', $url, $matches);

		if (!empty($matches)) {
			return $matches[1];
		}

		/* match http://www.youtube.com/watch?v=sr1eb3ngYko */
		preg_match('/youtube.com\/watch\?v=(.*)/is', $url, $matches);

		if (!empty($matches)) {
			return $matches[1];
		}

		preg_match('/youtu.be\/(.*)/is', $url, $matches);

		if (!empty($matches)) {
			return $matches[1];
		}

		return false;
	}

	public function getEmbedHTML($url)
	{
		$code	= $this->getCode($url);

		$config	= KT::getConfig();
		$width	= $config->get('bbcode_video_width');
		$height	= $config->get('bbcode_video_height');

		$width = $width ? $width . 'px' : '';
		$height = $height ? $height . 'px' : '';
		
		$useMaxWidth = $config->get('enable_media_max_width');

		if ($useMaxWidth) {
			$width = '100%';
		}

		if ($code) {
			return '<div class="embed-responsive embed-responsive-16by9" style="max-width:' . $width . ';"><iframe class="embed-responsive-item" title="YouTube video player" width="' . $width . '"  src="https://www.youtube.com/embed/' . $code . '?wmode=transparent" frameborder="0" allowfullscreen></iframe></div>';
		} else {
		    // this video do not have a code. so include the url directly.
			return '<div class="embed-responsive embed-responsive-16by9" style="max-width:' . $width . ';"><iframe class="embed-responsive-item" title="YouTube video player" width="' . $width . '"  src="' . $url . '&wmode=transparent" frameborder="0" allowfullscreen></iframe></div>';
		}
		return false;
	}
}
