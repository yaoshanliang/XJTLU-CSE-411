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

$addon = $displayData['addon'];

// Visibility
$custom_class = (isset($addon->settings->hidden_md) && $addon->settings->hidden_md) ? 'jwpf-hidden-md jwpf-hidden-lg ' : '';
$custom_class .= (isset($addon->settings->hidden_sm) && $addon->settings->hidden_sm) ? 'jwpf-hidden-sm ' : '';
$custom_class .= (isset($addon->settings->hidden_xs) && $addon->settings->hidden_xs) ? 'jwpf-hidden-xs ' : '';
$global_section_z_index = (isset($addon->settings->global_section_z_index) && $addon->settings->global_section_z_index) ? $addon->settings->global_section_z_index : '';
$global_addon_z_index = (isset($addon->settings->global_addon_z_index) && $addon->settings->global_addon_z_index) ? $addon->settings->global_addon_z_index : '';
$global_custom_position = (isset($addon->settings->global_custom_position) && $addon->settings->global_custom_position) ? $addon->settings->global_custom_position : '';
$global_seclect_position = (isset($addon->settings->global_seclect_position) && $addon->settings->global_seclect_position) ? $addon->settings->global_seclect_position : '';
$rowId = (isset($addon->settings->row_id) && $addon->settings->row_id) ? $addon->settings->row_id : '';
$colId = (isset($addon->settings->column_id) && $addon->settings->column_id) ? $addon->settings->column_id : '';

//Image lazy load
$config = JComponentHelper::getParams('com_jwpagefactory');
$lazyload = $config->get('lazyloadimg', '0');

if ($lazyload && isset($addon->settings->global_background_image) && $addon->settings->global_background_image) {
	if ($addon->settings->global_background_type == 'image') {
		$custom_class .= 'jwpf-element-lazy ';
	}
}

// Animation
$addon_attr = '';
if (isset($addon->settings->global_use_animation) && $addon->settings->global_use_animation) {
	if (isset($addon->settings->global_animation) && $addon->settings->global_animation) {
		$custom_class .= ' jwpf-wow ' . $addon->settings->global_animation . ' ';
		if (isset($addon->settings->global_animationduration) && $addon->settings->global_animationduration) {
			$addon_attr .= ' data-jwpf-wow-duration="' . $addon->settings->global_animationduration . 'ms" ';
		}
		if (isset($addon->settings->global_animationdelay) && $addon->settings->global_animationdelay) {
			$addon_attr .= 'data-jwpf-wow-delay="' . $addon->settings->global_animationdelay . 'ms" ';
		}
	}
}

$html = '<div id="jwpf-addon-wrapper-' . $addon->id . '" class="jwpf-addon-wrapper">';
$html .= '<div id="jwpf-addon-' . $addon->id . '" class="' . $custom_class . 'clearfix ' . ($global_custom_position && $global_seclect_position != '' ? 'jwpf-positioned-addon' : '') . '" ' . $addon_attr . ' ' . ($global_section_z_index ? 'data-zindex="' . $global_section_z_index . '"' : '') . ' ' . ($global_addon_z_index ? 'data-col-zindex="' . $global_addon_z_index . '"' : '') . ' ' . ($global_custom_position && $rowId ? 'data-rowid="' . $rowId . '"' : '') . ' ' . ($global_custom_position && $colId ? 'data-colid="' . $colId . '"' : '') . '>';

if (isset($addon->settings->global_use_overlay) && $addon->settings->global_use_overlay) {
	$html .= '<div class="jwpf-addon-overlayer"></div>';
}
echo $html;
