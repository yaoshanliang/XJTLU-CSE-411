<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
//no direct accees
defined('_JEXEC') or die ('Restricted access');
$input 	= JFactory::getApplication()->input;
$path 	= $input->post->get('path', '/images', 'PATH');
$media 	= $this->media;

$report['folders'] = $media['folders'];
$report['folders_list'] = $media['folders_list'];

$images = array();

foreach ($media['images'] as $key => $image) {

	$image 			= str_replace('\\', '/',$image);
	$root_path 	= str_replace('\\', '/', JPATH_ROOT);
	$path 			= str_replace($root_path . '/', '', $image);

	$images[$key]['path'] 	= $path;

	$thumb = dirname($path) . '/_jw-pagefactory_thumbs/' . basename($path);
	if(file_exists(JPATH_ROOT . '/' . $thumb)) {
		$images[$key]['src'] = JURI::root(true) . '/' . $thumb;
	} else {
		$images[$key]['src'] = JURI::root(true) . '/' . $path;
	}

	$filename = basename($image);
	$title = JFile::stripExt($filename);
	$ext = JFile::getExt($filename);

	$images[$key]['title'] 	= $title;
	$images[$key]['ext'] 		= $ext;
}

$report['images'] = $images;

echo json_encode($report); die;
