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

$options = $displayData['options'];
$custom_class = (isset($options->class)) ? ' ' . $options->class : '';
$data_attr = '';
$doc = JFactory::getDocument();

//Image lazy load
$config = JComponentHelper::getParams('com_jwpagefactory');
$lazyload = $config->get('lazyloadimg', '0');

if ($lazyload && isset($options->background_image) && $options->background_image) {
	if ($options->background_type == 'image') {
		$custom_class .= ' jwpf-element-lazy';
	}
}

// Responsive
if (isset($options->sm_col) && $options->sm_col) {
	$options->cssClassName .= ' jwpf-' . $options->sm_col;
}

if (isset($options->xs_col) && $options->xs_col) {
	$options->cssClassName .= ' jwpf-' . $options->xs_col;
}

if (isset($options->items_align_center) && $options->items_align_center) {
	$options->cssClassName .= ' jwpf-column-vertical-align';
}

//Column order
$column_order = '';
if (isset($options->tablet_order) && $options->tablet_order) {
	$column_order .= ' jwpf-order-sm-' . $options->tablet_order;
}

if (isset($options->mobile_order) && $options->mobile_order) {
	$column_order .= ' jwpf-order-xs-' . $options->mobile_order;
}

// Visibility
if (isset($options->hidden_md) && $options->hidden_md) {
	$custom_class .= ' jwpf-hidden-md jwpf-hidden-lg';
}

if (isset($options->hidden_sm) && $options->hidden_sm) {
	$custom_class .= ' jwpf-hidden-sm';
}

if (isset($options->hidden_xs) && $options->hidden_xs) {
	$custom_class .= ' jwpf-hidden-xs';
}

if (isset($options->items_content_alignment) && $options->items_content_alignment == 'top') {
	$custom_class .= (isset($options->items_align_center) && $options->items_align_center) ? ' jwpf-align-items-top' : '';
} else if (isset($options->items_content_alignment) && $options->items_content_alignment == 'bottom') {
	$custom_class .= (isset($options->items_align_center) && $options->items_align_center) ? ' jwpf-align-items-bottom' : '';
} else {
	$custom_class .= (isset($options->items_align_center) && $options->items_align_center) ? ' jwpf-align-items-center' : '';
}

// Animation
if (isset($options->animation) && $options->animation) {

	$custom_class .= ' jwpf-wow ' . $options->animation;

	if (isset($options->animationduration) && $options->animationduration) {
		$data_attr .= ' data-jwpf-wow-duration="' . $options->animationduration . 'ms"';
	}

	if (isset($options->animationdelay) && $options->animationdelay) {
		$data_attr .= ' data-jwpf-wow-delay="' . $options->animationdelay . 'ms"';
	}
}

$html = '';
$html .= '<div class="jwpf-' . $options->cssClassName . '' . $column_order . '" id="column-wrap-id-' . $options->dynamicId . '">';
$html .= '<div id="column-id-' . $options->dynamicId . '" class="jwpf-column' . $custom_class . '" ' . $data_attr . '>';

if (isset($options->background_image) && $options->background_image) {
	if (isset($options->overlay_type) && $options->overlay_type !== 'overlay_none') {
		$html .= '<div class="jwpf-column-overlay"></div>';
	}
}

$html .= '<div class="jwpf-column-addons">';

echo $html;
