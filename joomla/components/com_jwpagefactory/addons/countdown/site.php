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

class JwpagefactoryAddonCountdown extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		// Options
		$class = (isset($settings->class) && $settings->class) ? ' ' . $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		$output = '';
		$output .= '<div class="jwpf-addon jwpf-addon-countdown ' . $class . '">';
		$output .= '<div class="countdown-text-wrap">';
		$output .= ($title) ? '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';
		$output .= '</div>';
		$output .= "<div class='jwpf-countdown-timer jwpf-row'></div>";
		$output .= '</div>';

		return $output;
	}

	public function scripts()
	{
		return array(JURI::base(true) . '/components/com_jwpagefactory/assets/js/jquery.countdown.min.js');
	}

	public function js()
	{
		$settings = $this->addon->settings;
		$date = JHtml::_('date', $settings->date, 'Y/m/d');
		$time = $settings->time;
		$finish_text = addslashes($settings->finish_text);

		$js = "jQuery(function($){
			var addon_id = '#jwpf-addon-'+'" . $this->addon->id . "';
			//console.log(addon_id +' .jwpf-addon-countdown .jwpf-countdown-timer');
			$( addon_id +' .jwpf-addon-countdown .jwpf-countdown-timer').each(function () {
					var cdDateFormate = '" . $date . "' + ' ' + '" . $time . "';
					//console.log(cdDateFormate);
					$(this).countdown(cdDateFormate, function (event) {
							$(this).html(event.strftime('<div class=\"jwpf-countdown-days jwpf-col-xs-6 jwpf-col-sm-3 jwpf-text-center\"><span class=\"jwpf-countdown-number\">%-D</span><span class=\"jwpf-countdown-text\">%!D: ' + '" . JTEXT::_('COM_JWPAGEFACTORY_DAY') . "' + ',' + '" . JTEXT::_('COM_JWPAGEFACTORY_DAYS') . "' + ';</span></div><div class=\"jwpf-countdown-hours jwpf-col-xs-6 jwpf-col-sm-3 jwpf-text-center\"><span class=\"jwpf-countdown-number\">%H</span><span class=\"jwpf-countdown-text\">%!H: ' + '" . JTEXT::_('COM_JWPAGEFACTORY_HOUR') . "' + ',' + '" . JTEXT::_('COM_JWPAGEFACTORY_HOURS') . "' + ';</span></div><div class=\"jwpf-countdown-minutes jwpf-col-xs-6 jwpf-col-sm-3 jwpf-text-center\"><span class=\"jwpf-countdown-number\">%M</span><span class=\"jwpf-countdown-text\">%!M:' + '" . JTEXT::_('COM_JWPAGEFACTORY_MINUTE') . "' + ',' + '" . JTEXT::_('COM_JWPAGEFACTORY_MINUTES') . "' + ';</span></div><div class=\"jwpf-countdown-seconds jwpf-col-xs-6 jwpf-col-sm-3 jwpf-text-center\"><span class=\"jwpf-countdown-number\">%S</span><span class=\"jwpf-countdown-text\">%!S:' + '" . JTEXT::_('COM_JWPAGEFACTORY_SECOND') . "' + ',' + '" . JTEXT::_('COM_JWPAGEFACTORY_SECONDS') . "' + ';</span></div>'))
							.on('finish.countdown', function () {
									$(this).html('<div class=\"jwpf-countdown-finishedtext-wrap jwpf-col-xs-12 jwpf-col-sm-12 jwpf-text-center\"><h3 class=\"jwpf-countdown-finishedtext\">' + '" . $finish_text . "' + '</h3></div>');
							});
					});
			});
		})";
		return $js;
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;

		// Counter
		$counter_style = '';
		$counter_style_sm = '';
		$counter_style_xs = '';

		$counter_style .= (isset($settings->counter_height) && $settings->counter_height) ? "height: " . (int)$settings->counter_height . "px; line-height: " . (int)$settings->counter_height . "px;" : '';
		$counter_style_sm .= (isset($settings->counter_height_sm) && $settings->counter_height_sm) ? "height: " . (int)$settings->counter_height_sm . "px; line-height: " . (int)$settings->counter_height_sm . "px;" : '';
		$counter_style_xs .= (isset($settings->counter_height_xs) && $settings->counter_height_xs) ? "height: " . (int)$settings->counter_height_xs . "px; line-height: " . (int)$settings->counter_height_xs . "px;" : '';

		$counter_style .= (isset($settings->counter_width) && $settings->counter_width) ? "width: " . (int)$settings->counter_width . "px;" : '';
		$counter_style_sm .= (isset($settings->counter_width_sm) && $settings->counter_width_sm) ? "width: " . (int)$settings->counter_width_sm . "px;" : '';
		$counter_style_xs .= (isset($settings->counter_width_xs) && $settings->counter_width_xs) ? "width: " . (int)$settings->counter_width_xs . "px;" : '';

		$counter_style .= (isset($settings->counter_font_size) && $settings->counter_font_size) ? "font-size: " . (int)$settings->counter_font_size . "px;" : '';
		$counter_style .= (isset($settings->counter_text_font_family) && $settings->counter_text_font_family) ? "font-family: " . $settings->counter_text_font_family . ";" : '';
		$counter_style .= (isset($settings->counter_text_font_weight) && $settings->counter_text_font_weight) ? "font-weight: " . $settings->counter_text_font_weight . ";" : '';
		$counter_style_sm .= (isset($settings->counter_font_size_sm) && $settings->counter_font_size_sm) ? "font-size: " . (int)$settings->counter_font_size_sm . "px;" : '';
		$counter_style_xs .= (isset($settings->counter_font_size_xs) && $settings->counter_font_size_xs) ? "font-size: " . (int)$settings->counter_font_size_xs . "px;" : '';

		$counter_style .= (isset($settings->counter_text_color) && $settings->counter_text_color) ? "color: " . $settings->counter_text_color . ";" : '';
		$counter_style .= (isset($settings->counter_background_color) && $settings->counter_background_color) ? "background-color: " . $settings->counter_background_color . ";" : '';

		$counter_style .= (isset($settings->counter_border_radius) && $settings->counter_border_radius) ? "border-radius: " . $settings->counter_border_radius . "px;" : '';
		$counter_style_sm .= (isset($settings->counter_border_radius_sm) && $settings->counter_border_radius_sm) ? "border-radius: " . $settings->counter_border_radius_sm . "px;" : '';
		$counter_style_xs .= (isset($settings->counter_border_radius_xs) && $settings->counter_border_radius_xs) ? "border-radius: " . $settings->counter_border_radius_xs . "px;" : '';

		$use_border = (isset($settings->counter_user_border) && $settings->counter_user_border) ? 1 : 0;
		if ($use_border) {
			$counter_style .= (isset($settings->counter_border_width) && $settings->counter_border_width) ? "border-width: " . $settings->counter_border_width . "px;" : '';
			$counter_style .= (isset($settings->counter_border_style) && $settings->counter_border_style) ? "border-style: " . $settings->counter_border_style . ";" : '';
			$counter_style .= (isset($settings->counter_border_color) && $settings->counter_border_color) ? "border-color: " . $settings->counter_border_color . ";" : '';
			$counter_style_sm .= (isset($settings->counter_border_width_sm) && $settings->counter_border_width_sm) ? "border-width: " . $settings->counter_border_width_sm . "px;" : '';
			$counter_style_xs .= (isset($settings->counter_border_width_xs) && $settings->counter_border_width_xs) ? "border-width: " . $settings->counter_border_width_xs . "px;" : '';
		}

		// Label
		$label_style = '';
		$label_style_sm = '';
		$label_style_xs = '';
		$label_style .= (isset($settings->label_font_size) && $settings->label_font_size) ? "font-size: " . (int)$settings->label_font_size . "px;" : '';
		$label_style .= (isset($settings->label_color) && $settings->label_color) ? "color: " . $settings->label_color . ";" : '';
		$label_style .= (isset($settings->label_margin) && trim($settings->label_margin)) ? "margin: " . $settings->label_margin . ";" : '';

		$label_font_style = (isset($settings->label_font_style) && $settings->label_font_style) ? $settings->label_font_style : '';
		if (isset($label_font_style->underline) && $label_font_style->underline) {
			$label_style .= 'text-decoration:underline;';
		}
		if (isset($label_font_style->italic) && $label_font_style->italic) {
			$label_style .= 'font-style:italic;';
		}
		if (isset($label_font_style->uppercase) && $label_font_style->uppercase) {
			$label_style .= 'text-transform:uppercase;';
		}
		if (isset($label_font_style->weight) && $label_font_style->weight) {
			$label_style .= 'font-weight:' . $label_font_style->weight . ';';
		}

		$label_style_sm .= (isset($settings->label_font_size_sm) && $settings->label_font_size_sm) ? "font-size: " . (int)$settings->label_font_size_sm . "px;" : '';
		$label_style_sm .= (isset($settings->label_margin_sm) && trim($settings->label_margin_sm)) ? "margin: " . $settings->label_margin_sm . ";" : '';

		$label_style_xs .= (isset($settings->label_font_size_xs) && $settings->label_font_size_xs) ? "font-size: " . (int)$settings->label_font_size_xs . "px;" : '';
		$label_style_xs .= (isset($settings->label_margin_xs) && trim($settings->label_margin_xs)) ? "margin: " . $settings->label_margin_xs . ";" : '';

		//CSS out start
		$css = '';
		if ($counter_style) {
			$css .= $addon_id . ' .jwpf-countdown-number, ' . $addon_id . ' .jwpf-countdown-finishedtext {';
			$css .= $counter_style;
			$css .= '}';
		}

		if ($label_style) {
			$css .= $addon_id . ' .jwpf-countdown-text {';
			$css .= $label_style;
			$css .= '}';
		}

		if (!empty($counter_style_sm) || !empty($label_style_sm)) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			if ($counter_style_sm) {
				$css .= $addon_id . ' .jwpf-countdown-number, ' . $addon_id . ' .jwpf-countdown-finishedtext {';
				$css .= $counter_style_sm;
				$css .= '}';
			}

			if ($label_style_sm) {
				$css .= $addon_id . ' .jwpf-countdown-text {';
				$css .= $label_style_sm;
				$css .= '}';
			}
			$css .= '}';
		}

		if (!empty($counter_style_xs) || !empty($label_style_xs)) {
			$css .= '@media (max-width: 767px) {';
			if ($counter_style_xs) {
				$css .= $addon_id . ' .jwpf-countdown-number, ' . $addon_id . ' .jwpf-countdown-finishedtext {';
				$css .= $counter_style_xs;
				$css .= '}';
			}

			if ($label_style_xs) {
				$css .= $addon_id . ' .jwpf-countdown-text {';
				$css .= $label_style_xs;
				$css .= '}';
			}
			$css .= '}';
		}

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
		<style type="text/css">
			#jwpf-addon-{{ data.id }} .jwpf-countdown-number, #jwpf-addon-{{ data.id }} .jwpf-countdown-finishedtext {
				<# if(_.isObject(data.counter_height)){ #>
					height: {{ data.counter_height.md }}px;
					line-height: {{ data.counter_height.md }}px;
				<# } else { #>
					height: {{ data.counter_height }}px;
					line-height: {{ data.counter_height }}px;
				<# } #>

				<# if(_.isObject(data.counter_width)){ #>
					width: {{ data.counter_width.md }}px;
				<# } else { #>
					width: {{ data.counter_width }}px;
				<# } #>
				
				<# if(_.isObject(data.counter_font_size)){ #>
					font-size: {{ data.counter_font_size.md }}px;
				<# } else { #>
					font-size: {{ data.counter_font_size }}px;
				<# } #>
				color: {{ data.counter_text_color }};
				font-weight: {{ data.counter_text_font_weight }};
				background-color: {{ data.counter_background_color }};
				
				<# if(_.isObject(data.counter_border_radius)){ #>
					border-radius: {{ data.counter_border_radius.md }}px;
				<# } else { #>
					border-radius: {{ data.counter_border_radius }}px;
				<# } #>

				<# if(data.counter_user_border){ #>
					<# if(_.isObject(data.counter_border_width)){ #>
						border-width: {{ data.counter_border_width.md }}px;
					<# } else { #>
						border-width: {{ data.counter_border_width }}px;
					<# } #>
					
					border-style: {{ data.counter_border_style }};
					border-color: {{ data.counter_border_color }};
				<# } #>
			}
			#jwpf-addon-{{ data.id }} .jwpf-countdown-text {
				<# if(_.isObject(data.label_font_size)){ #>
					font-size: {{ data.label_font_size.md }}px;
				<# } else { #>
					font-size: {{ data.label_font_size }}px;
				<# }
				if(_.isObject(data.label_margin)){ #>
					margin: {{ data.label_margin.md }};
				<# } else { #>
					margin: {{ data.label_margin }};
				<# } #>
				color: {{ data.label_color }};
				<# if(_.isObject(data.label_font_style)){
					if(data.label_font_style.underline){
				#>
						text-decoration:underline;
					<# }
					if(data.label_font_style.italic){
					#>
						font-style:italic;
					<# }
					if(data.label_font_style.uppercase){
					#>
						text-transform:uppercase;
					<# }
					if(data.label_font_style.weight){
					#>
						font-weight:{{data.label_font_style.weight}};
					<# }
				} #>
			}

			@media (min-width: 768px) and (max-width: 991px) {
				#jwpf-addon-{{ data.id }} .jwpf-countdown-number, #jwpf-addon-{{ data.id }} .jwpf-countdown-finishedtext {
					<# if(_.isObject(data.counter_height)){ #>
						height: {{ data.counter_height.sm }}px;
						line-height: {{ data.counter_height.sm }}px;
					<# } #>
	
					<# if(_.isObject(data.counter_width)){ #>
						width: {{ data.counter_width.sm }}px;
					<# } #>
					
					<# if(_.isObject(data.counter_font_size)){ #>
						font-size: {{ data.counter_font_size.sm }}px;
					<# } #>
					
	
					<# if(_.isObject(data.counter_border_radius)){ #>
						border-radius: {{ data.counter_border_radius.sm }}px;
					<# } #>
	
					<# if(data.counter_user_border){ #>
						<# if(_.isObject(data.counter_border_width)){ #>
							border-width: {{ data.counter_border_width.sm }}px;
						<# } #>
					<# } #>
				}
				#jwpf-addon-{{ data.id }} .jwpf-countdown-text {
					<# if(_.isObject(data.label_font_size)){ #>
						font-size: {{ data.label_font_size.sm }}px;
					<# }
					if(_.isObject(data.label_margin)){ #>
						margin: {{ data.label_margin.sm }};
					<# } #>
				}
			}
			@media (max-width: 767px) {
				#jwpf-addon-{{ data.id }} .jwpf-countdown-number, #jwpf-addon-{{ data.id }} .jwpf-countdown-finishedtext {
					<# if(_.isObject(data.counter_height)){ #>
						height: {{ data.counter_height.xs }}px;
						line-height: {{ data.counter_height.xs }}px;
					<# } #>
	
					<# if(_.isObject(data.counter_width)){ #>
						width: {{ data.counter_width.xs }}px;
					<# } #>
					
					<# if(_.isObject(data.counter_font_size)){ #>
						font-size: {{ data.counter_font_size.xs }}px;
					<# } #>
					
	
					<# if(_.isObject(data.counter_border_radius)){ #>
						border-radius: {{ data.counter_border_radius.xs }}px;
					<# } #>
	
					<# if(data.counter_user_border){ #>
						<# if(_.isObject(data.counter_border_width)){ #>
							border-width: {{ data.counter_border_width.xs }}px;
						<# } #>
					<# } #>
				}
				#jwpf-addon-{{ data.id }} .jwpf-countdown-text {
					<# if(_.isObject(data.label_font_size)){ #>
						font-size: {{ data.label_font_size.xs }}px;
					<# }
					if(_.isObject(data.label_margin)){ #>
						margin: {{ data.label_margin.xs }};
					<# } #>
				}
			}
		</style>
		<div class="jwpf-addon jwpf-addon-countdown {{ data.class }}">
			<div class="countdown-text-wrap">
				<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
			</div>
			<div class="jwpf-countdown-timer jwpf-row" data-date="{{ data.date }}" data-time="{{ data.time }}" data-finish-text="{{ data.finish_text }}"></div>
		</div>
		';

		return $output;
	}

}
