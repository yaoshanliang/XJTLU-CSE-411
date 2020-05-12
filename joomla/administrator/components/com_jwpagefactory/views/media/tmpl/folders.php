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

$input = JFactory::getApplication()->input;
$mediaParams = JComponentHelper::getParams('com_media');
$media_path = $mediaParams->get('file_path', 'images');
$m_source = $input->get('source', '', 'STRING');

if ($m_source == 'page') {

	$input = JFactory::getApplication()->input;
	$path = $input->post->get('path', '/images', 'PATH');
	$media = $this->media;

	$report['folders'] = $media['folders'];
	$report['folders_list'] = $media['folders_list'];

	$images = array();

	foreach ($media['images'] as $key => $image) {

		$image = str_replace('\\', '/', $image);
		$root_path = str_replace('\\', '/', JPATH_ROOT);
		$path = str_replace($root_path . '/', '', $image);

		$images[$key]['path'] = $path;

		$thumb = dirname($path) . '/_jw-pagefactory_thumbs/' . basename($path);
		if (file_exists(JPATH_ROOT . '/' . $thumb)) {
			$images[$key]['src'] = JURI::root(true) . '/' . $thumb;
		} else {
			$images[$key]['src'] = JURI::root(true) . '/' . $path;
		}

		$filename = basename($image);
		$title = JFile::stripExt($filename);
		$ext = JFile::getExt($filename);

		$images[$key]['title'] = $title;
		$images[$key]['ext'] = $ext;
	}

	$report['images'] = $images;

	echo json_encode($report);
	die;

} else {

	$input = JFactory::getApplication()->input;
	$path = $input->post->get('path', '/images', 'PATH');

	$report = array();
	$media = $this->media;

	$report['output'] = '';
	$report['count'] = 0;

	$tree = '<option value="/' . $media_path . '">/' . $media_path . '</option>';
	foreach ($media['folders'] as $folder) {
		$value = str_replace('\\', '/', $folder['relname']);
		if ($path == $value) {
			$tree .= '<option value="' . $value . '" selected>' . str_replace('\\', '/', $folder['relname']) . '</option>';
		} else {
			$tree .= '<option value="' . $value . '">' . str_replace('\\', '/', $folder['relname']) . '</option>';
		}
	}
	$report['folders_tree'] = $tree; // End folders tree

	$report['output'] .= '<ul class="jw-pagefactory-media clearfix">';

	// Folders List
	if (dirname($path) != '/') {
		$report['output'] .= '<li class="jw-pagefactory-media-folder jw-pagefactory-media-to-folder-back" data-path="' . dirname($path) . '">';
		$report['output'] .= '<div>';
		$report['output'] .= '<div>';
		$report['output'] .= '<div>';
		$report['output'] .= '<div>';
		$report['output'] .= '<div>';
		$report['output'] .= '<div>';
		$report['output'] .= '<i class="fa fa-arrow-left fa-4x"></i>';
		$report['output'] .= '</div>';
		$report['output'] .= '</div>';
		$report['output'] .= '</div>';
		$report['output'] .= '</div>';
		$report['output'] .= '</div>';
		$report['output'] .= '<span class="jw-pagefactory-media-title">' . JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_FOLDER_BACK') . '</span>';
		$report['output'] .= '</div>';

		$report['count'] = 1;
	}

	if (isset($media['folders_list']) && count((array)$media['folders_list'])) {
		foreach ($media['folders_list'] as $single_folder) {
			$report['output'] .= '<li class="jw-pagefactory-media-folder jw-pagefactory-media-to-folder" data-path="' . $path . '/' . $single_folder . '">';
			$report['output'] .= '<div>';
			$report['output'] .= '<div>';
			$report['output'] .= '<div>';
			$report['output'] .= '<div>';
			$report['output'] .= '<div>';
			$report['output'] .= '<div>';
			$report['output'] .= '<i class="fa fa-folder"></i>';
			$report['output'] .= '</div>';
			$report['output'] .= '</div>';
			$report['output'] .= '</div>';
			$report['output'] .= '</div>';
			$report['output'] .= '</div>';
			$report['output'] .= '<span class="jw-pagefactory-media-title">' . $single_folder . '</span>';
			$report['output'] .= '</div>';
			$report['output'] .= '</li>';
		}

		// Get Folders count
		$report['count'] += (isset($media['folders_list']) && count((array)$media['folders_list'])) ? count((array)$media['folders_list']) : 0;
	}

	if (isset($media['images']) && count((array)$media['images'])) {
		foreach ($media['images'] as $image) {

			$image = str_replace('\\', '/', $image);
			$root_path = str_replace('\\', '/', JPATH_ROOT);
			$path = str_replace($root_path . '/', '', $image);

			$filename = basename($image);
			$title = JFile::stripExt($filename);
			$ext = JFile::getExt($filename);
			$report['output'] .= '<li class="jw-pagefactory-media-item" data-type="image" data-src="' . JURI::root(true) . '/' . $path . '" data-path="' . $path . '">';

			$report['output'] .= '<div>';
			$report['output'] .= '<div>';
			$report['output'] .= '<div>';
			$report['output'] .= '<div>';

			$thumb = dirname($path) . '/_jw-pagefactory_thumbs/' . basename($path);
			if (file_exists(JPATH_ROOT . '/' . $thumb)) {
				$report['output'] .= '<img title="' . $filename . '" src="' . JURI::root(true) . '/' . $thumb . '">';
			} else {
				$report['output'] .= '<img title="' . $filename . '" src="' . JURI::root(true) . '/' . $path . '">';
			}

			$report['output'] .= '</div>';
			$report['output'] .= '</div>';
			$report['output'] .= '</div>';
			$report['output'] .= '<span title="' . $filename . '" class="jw-pagefactory-media-title"><i class="fa fa-picture-o"></i> ' . $title . '.' . $ext . '</span>';
			$report['output'] .= '</div>';

			$report['output'] .= '</li>';

		}
	}

	$report['output'] .= '</ul>';

	// Get Media count
	$report['count'] += (isset($media['images']) && count((array)$media['images'])) ? count((array)$media['images']) : 0;

	echo json_encode($report);

	die;

}
