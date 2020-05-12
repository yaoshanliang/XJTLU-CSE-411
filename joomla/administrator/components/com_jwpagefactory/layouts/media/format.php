<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
defined('_JEXEC') or die();

$media = '';
$item = $displayData['media'];
$ext = JFile::getExt($item->path);
$app = JFactory::getApplication();
$support = $app->input->post->get('support', 'image', 'STRING');
$filename = $item->title . '.' . $ext;

$innerHTML = false;
if (isset($displayData['innerHTML']) && $displayData['innerHTML']) {
	$innerHTML = true;
}

if (isset($displayData['support']) && $displayData['support']) {
	$support = $displayData['support'];
}

if (!$innerHTML) {
	$class = ' jw-pagefactory-media-unsupported';
	if ($support == $item->type) {
		$class = ' jw-pagefactory-media-supported';
	}

	if ($support == 'all') {
		$class = ' jw-pagefactory-media-supported';
	}
	$media .= '<li class="jw-pagefactory-media-item' . $class . ' jw-pagefactory-media-type-' . $item->type . '" data-id="' . $item->id . '" data-type="' . $item->type . '" data-src="' . JURI::root(true) . '/' . $item->path . '" data-path="' . $item->path . '">';
}

if ($item->type == 'image') {
	$media .= '<div>';
	$media .= '<div>';
	$media .= '<div>';
	$media .= '<div>';
	if (isset($item->thumb) && $item->thumb) {
		$media .= '<img title="' . $filename . '" src="' . JURI::root(true) . '/' . $item->thumb . '">';
	} else {
		$media .= '<img title="' . $filename . '" src="' . JURI::root(true) . '/' . $item->path . '">';
	}
	$media .= '</div>';
	$media .= '</div>';
	$media .= '</div>';
	$media .= '<span title="' . $filename . '" class="jw-pagefactory-media-title"><span><i class="fa fa-picture-o"></i> ' . $filename . '</span></span>';
	$media .= '</div>';
} else {

	if ($item->type == 'video') {
		$box_class = 'video';
		$icon_class = 'film';
	} else if ($item->type == 'audio') {
		$box_class = 'audio';
		$icon_class = 'music';
	} else if ($item->type == 'attachment') {
		if (($ext == 'doc') || ($ext == 'docx') || ($ext == 'odt')) {
			$box_class = 'attachment-document';
			$icon_class = 'file-word-o';
		} elseif (($ext == 'key') || ($ext == 'ppt') || ($ext == 'pptx') || ($ext == 'pps') || ($ext == 'ppsx')) {
			$box_class = 'attachment-presentation';
			$icon_class = 'file-powerpoint-o';
		} elseif (($ext == 'xls') || ($ext == 'xlsx')) {
			$box_class = 'attachment-excel';
			$icon_class = 'file-excel-o';
		} elseif (($ext == 'pdf')) {
			$box_class = 'attachment-pdf';
			$icon_class = 'file-pdf-o';
		} elseif (($ext == 'zip')) {
			$box_class = 'attachment-zip';
			$icon_class = 'file-archive-o';
		}
	}
	$media .= '<div>';
	$media .= '<div>';
	$media .= '<div>';
	$media .= '<div>';
	$media .= '<div>';
	$media .= '<div class="jw-pagefactory-media-' . $box_class . '">';
	$media .= '<i title="' . $filename . '" class="fa fa-' . $icon_class . '"></i>';
	$media .= '</div>';
	$media .= '</div>';
	$media .= '</div>';
	$media .= '</div>';
	$media .= '</div>';
	$media .= '<span title="' . $filename . '" class="jw-pagefactory-media-title"><span><i class="fa fa-' . $icon_class . '"></i> ' . $filename . '</span></span>';
	$media .= '</div>';
}

if (!$innerHTML) {
	$media .= '</li>';
}

echo $media;
