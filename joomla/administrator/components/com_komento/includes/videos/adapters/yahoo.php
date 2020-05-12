<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

class KomentoVideoYahoo
{
	private function getCode($url)
	{
		preg_match('/\?vid=(.*)(?=&)/i', $url, $matches);

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

		$useMaxWidth = $config->get('enable_media_max_width');

		if ($useMaxWidth) {
			$width = '100%';
		}

		if ($code) {
			$html	= '<object width="' . $width . '" height="' . $height . '">'
					. '<param name="movie" value="http://d.yimg.com/nl/cbe/butterfinger/player.swf"></param>'
					. '<param name="flashVars" value="browseCarouselUI=hide&shareUrl=https%3A//comedy.video.yahoo.com/%3Fv%3D' . $code . '&repeat=0&vid=' . $code . '"></param>'
					. '<param name="allowfullscreen" value="true"></param>'
					. '<param name="wmode" value="transparent"></param>'
					. '<embed width="' . $width . '" height="' . $height . '" allowFullScreen="true" src="http://d.yimg.com/nl/cbe/butterfinger/player.swf" type="application/x-shockwave-flash" flashvars="browseCarouselUI=hide&shareUrl=https%3A//comedy.video.yahoo.com/%3Fv%3D' . $code . '&repeat=0&vid=' . $code . '&"></embed>'
					. '</object>';
			return $html;
		}
		return false;
	}
}
