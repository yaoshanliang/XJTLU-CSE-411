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

class KomentoVideoGoogle
{
	private function getCode($url)
	{
		preg_match('/videoplay\?docid=(.*)(?=\#)/i', $url, $matches);

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
			return '<embed id=VideoPlayback src=https://video.google.com/googleplayer.swf?docid=' . $code . '&hl=en&fs=true style=width:' . $width . ';height:' . $height . ' allowFullScreen=true allowScriptAccess=always type=application/x-shockwave-flash> </embed>';
		}

		return false;
	}
}
