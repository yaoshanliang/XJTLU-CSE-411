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

$addon_id = $displayData['addon_id'];
$selector = (isset($displayData['selector']) && $displayData['selector']) ? $displayData['selector'] : '';
$options = $displayData['options'];

$output = '';
if (count((array)$options)) {
	foreach ($options as $option) {
		if (is_array($option)) {
			// Font
			if (isset($option['type']) && $option['type']) {
				$type = $option['type'];
				if ($type == 'font') {
					$font = $option['font'];

					$system = array(
						'Arial',
						'Tahoma',
						'Verdana',
						'Helvetica',
						'Times New Roman',
						'Trebuchet MS',
						'Georgia'
					);

					if (!in_array($font, $system)) {
						$google_font = '//fonts.googleapis.com/css?family=' . str_replace(' ', '+', $option['font']) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&display=swap';
						JFactory::getDocument()->addStylesheet($google_font);
					}

					if ($selector) {
						$output .= $addon_id . ' ' . $selector . $option['css'] . "\n";
					} else {
						$output .= $addon_id . ' ' . $option['css'] . "\n";
					}

				}
			} else {

			}
		}
	}
}

echo $output;
