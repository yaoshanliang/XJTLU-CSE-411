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

class JwpagefactoryAddonBlocknumber extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : '';
		$text = (isset($settings->text) && $settings->text) ? $settings->text : '';
		$number = (isset($settings->number) && $settings->number) ? $settings->number : '';
		$alignment = (isset($settings->alignment) && $settings->alignment) ? $settings->alignment : '';
		$heading = (isset($settings->heading) && $settings->heading) ? $settings->heading : '';

		if ($number) {
			$block_number = '<span class="jwpf-blocknumber-number">' . $number . '</span>';
		}
		//Output start
		$output = '';
		$output .= '<div class="jwpf-addon jwpf-addon-blocknumber ' . $class . '">';

		if ($title) {
			$output .= '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>';
		}

		$output .= '<div class="jwpf-addon-content">';
		$output .= '<div class="jwpf-blocknumber jwpf-media">';
		if ($alignment == 'center') {
			if ($number) {
				$output .= '<div class="jwpf-text-center">' . $block_number . '</div>';
			}
			$output .= '<div class="jwpf-media-body jwpf-text-center">';
			if ($heading) $output .= '<h3 class="jwpf-media-heading">' . $heading . '</h3>';
			if ($text) {
				$output .= $text;
			}
		} else {
			if ($number) {
				$output .= '<div class="pull-' . $alignment . '">' . $block_number . '</div>';
			}
			$output .= '<div class="jwpf-media-body jwpf-text-' . $alignment . '">';
			if ($heading) $output .= '<h3 class="jwpf-media-heading">' . $heading . '</h3>';
			$output .= $text;
		}

		$output .= '</div>'; //.jwpf-media-body
		$output .= '</div>'; //.jwpf-media
		$output .= '</div>'; //.jwpf-addon-content
		$output .= '</div>'; //.jwpf-addon-blocknumber

		return $output;
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$number_style = '';
		$number_style_sm = '';
		$number_style_xs = '';

		//number_style
		$number_style .= (isset($settings->size) && $settings->size) ? 'width: ' . (int)$settings->size . 'px; height: ' . (int)$settings->size . 'px; line-height: ' . (int)$settings->size . 'px;' : '';
		$number_style_sm .= (isset($settings->size_sm) && $settings->size_sm) ? 'width: ' . (int)$settings->size_sm . 'px; height: ' . (int)$settings->size_sm . 'px; line-height: ' . (int)$settings->size_sm . 'px;' : '';
		$number_style_xs .= (isset($settings->size_xs) && $settings->size_xs) ? 'width: ' . (int)$settings->size_xs . 'px; height: ' . (int)$settings->size_xs . 'px; line-height: ' . (int)$settings->size_xs . 'px;' : '';

		if ($settings->background) $number_style .= 'background-color: ' . $settings->background . ';';
		if ($settings->color) $number_style .= 'color: ' . $settings->color . ';';
		if ($settings->border_radius) $number_style .= 'border-radius: ' . (int)$settings->border_radius . 'px;';

		$css = '';

		if ($number_style) {
			$css .= $addon_id . ' .jwpf-blocknumber-number {';
			$css .= $number_style;
			$css .= "\n" . '}' . "\n";
		}

		if ($number_style_sm) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			$css .= $addon_id . ' .jwpf-blocknumber-number {';
			$css .= $number_style_sm;
			$css .= "\n" . '}' . "\n";
			$css .= '}';
		}

		if ($number_style_xs) {
			$css .= '@media (max-width: 767px) {';
			$css .= $addon_id . ' .jwpf-blocknumber-number {';
			$css .= $number_style_xs;
			$css .= "\n" . '}' . "\n";
			$css .= '}';
		}

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
		<style type="text/css">
			#jwpf-addon-{{ data.id }} .jwpf-blocknumber-number {
				<# if(_.isObject(data.size)){ #>
					width: {{ data.size.md }}px;
					height: {{ data.size.md }}px;
					line-height: {{ data.size.md }}px;
				<# } else { #>
					width: {{ data.size }}px;
					height: {{ data.size }}px;
					line-height: {{ data.size }}px;
				<# } #>
				background-color: {{ data.background }};
				color: {{ data.color }};
				border-radius: {{ data.border_radius }}px;
			}

			@media (min-width: 768px) and (max-width: 991px) {
				#jwpf-addon-{{ data.id }} .jwpf-blocknumber-number {
					<# if(_.isObject(data.size)){ #>
						width: {{ data.size.sm }}px;
						height: {{ data.size.sm }}px;
						line-height: {{ data.size.sm }}px;
					<# } #>
				}
			}
			@media (max-width: 767px) {
				#jwpf-addon-{{ data.id }} .jwpf-blocknumber-number {
					<# if(_.isObject(data.size)){ #>
						width: {{ data.size.xs }}px;
						height: {{ data.size.xs }}px;
						line-height: {{ data.size.xs }}px;
					<# } #>
				}
			}
		</style>
		<div class="jwpf-addon jwpf-addon-blocknumber {{ data.class }}">
			<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{{ data.title }}}</{{ data.heading_selector }}><# } #>
			<div class="jwpf-addon-content">
				<div class="jwpf-blocknumber jwpf-media">
					<# if( data.alignment == "center" ) { #>
						<# if(data.number){ #>
							<div class="jwpf-text-center"><span class="jwpf-blocknumber-number jw-inline-editable-element" data-id={{data.id}} data-fieldName="number" contenteditable="true">{{ data.number }}</span></div>
						<# } #>
						<div class="jwpf-media-body jwpf-text-center">
							<# if(data.heading){ #>
								<h3 class="jwpf-media-heading jw-inline-editable-element" data-id={{data.id}} data-fieldName="heading" contenteditable="true">{{{ data.heading }}}</h3>
							<# } #>
							<div class="jw-inline-editable-element" data-id={{data.id}} data-fieldName="text" contenteditable="true">{{ data.text }}</div>
						</div>
					<# } else { #>
						<# if(data.number){ #>
							<div class="pull-{{ data.alignment }}"><span class="jwpf-blocknumber-number jw-inline-editable-element" data-id={{data.id}} data-fieldName="number" contenteditable="true">{{ data.number }}</span></div>
						<# } #>
						<div class="jwpf-media-body jwpf-text-{{ data.alignment }}">
							<# if(data.heading){ #>
								<h3 class="jwpf-media-heading jw-inline-editable-element" data-id={{data.id}} data-fieldName="heading" contenteditable="true">{{{ data.heading }}}</h3>
							<# } #>
							<div class="jw-inline-editable-element" data-id={{data.id}} data-fieldName="text" contenteditable="true">{{ data.text }}</div>
						</div>
					<# } #>
				</div>
			</div>
		</div>';

		return $output;
	}
}
