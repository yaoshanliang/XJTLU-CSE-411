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
$categories = $displayData['categories'];
$media_categories = '';

$app = JFactory::getApplication();
$support = $app->input->post->get('support', 'image', 'STRING');
$type = $app->input->post->get('type', '*', 'STRING');

$media_categories .= '<li' . (($type == '*') ? ' class="active"' : '') . '><a href="#" class="jw-pagefactory-browse-media jw-pagefactory-browse-all" data-type="*"><i class="fa fa-files-o fa-fw"></i> ' . JText::_('COM_JWPAGEFACTORY_MEDIA_CATEGORIES_ALL_ITEMS') . ' <span>' . ((isset($categories['all']) && $categories['all']) ? $categories['all'] : 0) . '</span></a></li>';
$media_categories .= '<li' . (($type == 'image') ? ' class="active"' : '') . '><a href="#" class="jw-pagefactory-browse-media jw-pagefactory-browse-image" data-type="image"><i class="fa fa-picture-o fa-fw"></i> ' . JText::_('COM_JWPAGEFACTORY_MEDIA_CATEGORIES_IMAGES') . ' <span>' . ((isset($categories['image']) && $categories['image']) ? $categories['image'] : 0) . '</span></a></li>';
$media_categories .= '<li' . (($type == 'video') ? ' class="active"' : '') . '><a href="#" class="jw-pagefactory-browse-media jw-pagefactory-browse-video" data-type="video"><i class="fa fa-film fa-fw"></i> ' . JText::_('COM_JWPAGEFACTORY_MEDIA_CATEGORIES_VIDEOS') . ' <span>' . ((isset($categories['video']) && $categories['video']) ? $categories['video'] : 0) . '</span></a></li>';
$media_categories .= '<li' . (($type == 'audio') ? ' class="active"' : '') . '><a href="#" class="jw-pagefactory-browse-media jw-pagefactory-browse-audio" data-type="audio"><i class="fa fa-music fa-fw"></i> ' . JText::_('COM_JWPAGEFACTORY_MEDIA_CATEGORIES_AUDIOS') . ' <span>' . ((isset($categories['audio']) && $categories['audio']) ? $categories['audio'] : 0) . '</span></a></li>';
$media_categories .= '<li' . (($type == 'attachment') ? ' class="active"' : '') . '><a href="#" class="jw-pagefactory-browse-media jw-pagefactory-browse-attachment" data-type="attachment"><i class="fa fa-paperclip fa-fw"></i> ' . JText::_('COM_JWPAGEFACTORY_MEDIA_CATEGORIES_ATTACHMENTS') . ' <span>' . ((isset($categories['attachment']) && $categories['attachment']) ? $categories['attachment'] : 0) . '</span></a></li>';
if ($support == 'image') {
	$media_categories .= '<li><a href="#" class="jw-pagefactory-browse-media jw-pagefactory-browse-folders" data-type="folders"><i class="fa fa-folder-open-o fa-fw"></i> ' . JText::_('COM_JWPAGEFACTORY_MEDIA_CATEGORIES_BROWSE_FOLDERS') . ' <span>...</span></a></li>';
}

echo $media_categories;
