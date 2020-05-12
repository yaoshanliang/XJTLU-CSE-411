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
defined ('_JEXEC') or die ('Restricted access');

$options = $displayData['options'];

$fluid_row = (isset($options->fullscreen) && $options->fullscreen) ? $options->fullscreen : 0;

$html = '</div>';

if(!$fluid_row){
	$html .= '</div>';
	$html .= '</section>';
} else {
	$html .= '</div>';
	$html .= '</div>';
}

echo $html;
