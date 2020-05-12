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
defined('_JEXEC') or die('Restricted access');

class JwpagefactoryAddonTimeline extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;

		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';

		$output = '';
		$output .= '<div class="jwpf-addon jwpf-addon-timeline ' . $class . '">';
		$output .= '<div class="jwpf-addon-timeline-text-wrap">';
		$output .= ($title) ? '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';
		$output .= '</div>'; //.jwpf-addon-instagram-text-wrap

		$output .= '<div class="jwpf-addon-timeline-wrapper">';

		foreach ($settings->jw_timeline_items as $key => $timeline) {
			$oddeven = (round($key % 2) == 0) ? 'even' : 'odd';
			$output .= '<div class="jwpf-row timeline-movement ' . $oddeven . '">';
			$output .= '<div class="timeline-badge"></div>';
			if ($oddeven == 'odd') {
				$output .= '<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item">';
				if (isset($timeline->date) && $timeline->date) {
					$output .= '<p class="timeline-date text-right">' . $timeline->date . '</p>';
				}
				$output .= '</div>';
				$output .= '<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item">';
				$output .= '<div class="timeline-panel">';

				$clsphoto = '';
				if (isset($timeline->photo) && $timeline->photo) {
					$output .= '<div class="photo"><img src="' . $timeline->photo . '" /></div>';
					$clsphoto = 'hasphoto';
				}
				if (isset($timeline->title) && $timeline->title) {
					$output .= '<p class="title ' . $clsphoto . '">' . $timeline->title . '</p>';
				}
				if (isset($timeline->content) && $timeline->content) {
					$output .= '<div class="details ' . $clsphoto . '">' . $timeline->content . '</div>';
				}
				$output .= '</div>';
				$output .= '</div>';
			} elseif ($oddeven == 'even') {
				$output .= '<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item mobile-block">';
				if (isset($timeline->date) && $timeline->date) {
					$output .= '<p class="timeline-date text-left">' . $timeline->date . '</p>';
				}
				$output .= '</div>';
				$output .= '<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item">';
				$output .= '<div class="timeline-panel left-part">';

				$clsphoto = '';
				if (isset($timeline->photo) && $timeline->photo) {
					$output .= '<div class="photo"><img src="' . $timeline->photo . '" /></div>';
					$clsphoto = 'hasphoto';
				}
				if (isset($timeline->title) && $timeline->title) {
					$output .= '<p class="title ' . $clsphoto . '">' . $timeline->title . '</p>';
				}
				if (isset($timeline->content) && $timeline->content) {
					$output .= '<div class="details ' . $clsphoto . '">' . $timeline->content . '</div>';
				}
				$output .= '</div>';
				$output .= '</div>';
				$output .= '<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item mobile-hidden">';
				if (isset($timeline->date) && $timeline->date) {
					$output .= '<p class="timeline-date text-left">' . $timeline->date . '</p>';
				}
				$output .= '</div>';
			}
			$output .= '</div>'; //.timeline-movement
		} // foreach timelines

		$output .= '</div>'; //.Timeline

		$output .= '</div>'; //.jwpf-addon-instagram-gallery

		return $output;
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;

		$bar_color = (isset($settings->bar_color) && $settings->bar_color) ? $settings->bar_color : '#0095eb';

		$css = '';
		if ($bar_color) {
			$css .= $addon_id . ' .jwpf-addon-timeline .jwpf-addon-timeline-wrapper:before, ' . $addon_id . ' .jwpf-addon-timeline .jwpf-addon-timeline-wrapper .timeline-badge:after, ' . $addon_id . ' .jwpf-addon-timeline .timeline-movement.even:before{';
			$css .= 'background-color: ' . $bar_color . ';';
			$css .= '}';

			$css .= $addon_id . ' .jwpf-addon-timeline .jwpf-addon-timeline-wrapper .timeline-badge:before, ' . $addon_id . ' .jwpf-addon-timeline .timeline-movement.even:after{';
			$css .= 'border-color: ' . $bar_color . ';';
			$css .= '}';
		}

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
			<#
				let bar_color = data.bar_color || "#0095eb";
				if(bar_color){
			#>
			<style type="text/css">
				#jwpf-addon-{{data.id}} .jwpf-addon-timeline .jwpf-addon-timeline-wrapper:before,
				#jwpf-addon-{{data.id}} .jwpf-addon-timeline .jwpf-addon-timeline-wrapper .timeline-badge:after,
				#jwpf-addon-{{data.id}} .jwpf-addon-timeline .timeline-movement.even:before {
					background-color: {{bar_color}};
				}
	
				#jwpf-addon-{{data.id}} .jwpf-addon-timeline .jwpf-addon-timeline-wrapper .timeline-badge:before,
				#jwpf-addon-{{data.id}} .jwpf-addon-timeline .timeline-movement.even:after {
					border-color: {{bar_color}};
				}
			</style>
	
			<# } #>
			<div class="jwpf-addon jwpf-addon-timeline {{ data.class }}">
				<div class="jwpf-addon-timeline-text-wrap">
				<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
				</div>
	
				<div class="jwpf-addon-timeline-wrapper">
				<#
					_.each(data.jw_timeline_items, function(timeline_item, key){
					let oddeven = ((key%2) == 0 ) ? "even":"odd";
				#>
					<div class="jwpf-row timeline-movement {{oddeven}}">
						<div class="timeline-badge"></div>
						<#
							if(oddeven == "odd") {
						#>
						<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item">
							<p class="timeline-date text-right">{{ timeline_item.date }}</p>
						</div>
						<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item">
							<div class="timeline-panel">
								<p class="title jw-editable-content" id="addon-title-{{data.id}}-{{key}}" data-id={{data.id}} data-fieldName="jw_timeline_items-{{key}}-title">{{ timeline_item.title }}</p>
								<div class="details jw-editable-content" id="addon-content-{{data.id}}-{{key}}" data-id={{data.id}} data-fieldName="jw_timeline_items-{{key}}-content">{{{ timeline_item.content }}}</div>
								<div class="details jw-editable-content" id="addon-photo-{{data.id}}-{{key}}" data-id={{data.id}} data-fieldName="jw_timeline_items-{{key}}-photo">{{{ timeline_item.photo }}}</div>
							</div>
						</div>
	
						<# } else { #>
	
						<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item mobile-block">
							<p class="timeline-date text-left">{{ timeline_item.date }}</p>
						</div>
						<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item">
							<div class="timeline-panel left-part">
								<p class="title jw-editable-content" id="addon-title-{{data.id}}-{{key}}" data-id={{data.id}} data-fieldName="jw_timeline_items-{{key}}-title">{{ timeline_item.title }}</p>
								<div class="details jw-editable-content" id="addon-content-{{data.id}}-{{key}}" data-id={{data.id}} data-fieldName="jw_timeline_items-{{key}}-content">{{{ timeline_item.content }}}</div>
								<div class="details jw-editable-content" id="addon-photo-{{data.id}}-{{key}}" data-id={{data.id}} data-fieldName="jw_timeline_items-{{key}}-photo">{{{ timeline_item.photo }}}</div>
							</div>
						</div>
						<div class="jwpf-col-xs-12 jwpf-col-sm-6 timeline-item mobile-hidden">
							<p class="timeline-date text-left">{{ timeline_item.date }}</p>
						</div>
						<# } #>
					</div>
				<# }) #>
				</div>
			</div>
		';

		return $output;
	}

}
