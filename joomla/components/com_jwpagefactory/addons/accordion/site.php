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

class JwpagefactoryAddonAccordion extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$style = (isset($settings->style) && $settings->style) ? $settings->style : 'panel-default';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';
		$icon_position = (isset($settings->icon_position) && $settings->icon_position) ? $settings->icon_position : '';

		$output = '';
		$output = '<div class="jwpf-addon jwpf-addon-accordion ' . $class . '">';

		if ($title) {
			$output .= '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>';
		}

		$output .= '<div class="jwpf-addon-content">';
		$output .= '<div class="jwpf-panel-group">';

		if (isset($settings->jw_accordion_item) && is_array($settings->jw_accordion_item) && count($settings->jw_accordion_item)) {
			foreach ($settings->jw_accordion_item as $key => $item) {
				$item_title = (isset($item->title) && $item->title) ? $item->title : '';

				$output .= '<div class="jwpf-panel jwpf-' . $style . '">';
				$output .= '<div class="jwpf-panel-heading' . (($key == 0) ? ' active' : '') . ' ' . ($icon_position == 'right' ? 'jwpf-accordion-icon-position-right' : '') . '" id="jwpf-ac-heading-' . $this->addon->id . '-key-' . $key . '" aria-expanded="' . (($key == 0) ? 'true' : 'false') . '" aria-controls="jwpf-ac-content-' . $this->addon->id . '-key-' . $key . '">';
				if (isset($item->icon) && $item->icon != '' && $style == 'panel-custom') {
					$output .= '<span class="jwpf-accordion-icon-wrap" aria-label="' . trim(strip_tags($item_title)) . '">';

					$icon_arr = array_filter(explode(' ', $item->icon));
					if (count($icon_arr) === 1) {
						$item->icon = 'fa ' . $item->icon;
					}

					$output .= '<i class="' . $item->icon . '" aria-hidden="true"></i> ';
					$output .= '</span>';//.jwpf-accordion-icon-wrap
				}
				$output .= '<span class="jwpf-panel-title" aria-label="' . trim(strip_tags($item_title)) . '">';
				if (isset($item->icon) && $item->icon != '' && $style !== 'panel-custom') {

					$icon_arr = array_filter(explode(' ', $item->icon));
					if (count($icon_arr) === 1) {
						$item->icon = 'fa ' . $item->icon;
					}

					$output .= '<i class="' . $item->icon . '" aria-hidden="true"></i> ';
				}
				$output .= $item_title;
				$output .= '</span>';//.jwpf-panel-title
				if ($style !== 'panel-custom') {
					$output .= '<span class="jwpf-toggle-direction" aria-label="Toggle Direction Icon ' . ($key + 1) . '"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>';
				}
				$output .= '</div>';//.jwpf-panel-heading
				$output .= '<div id="jwpf-ac-content-' . $this->addon->id . '-key-' . $key . '" class="jwpf-panel-collapse"' . (($key != 0) ? ' style="display: none;"' : '') . ' aria-labelledby="jwpf-ac-heading-' . $this->addon->id . '-key-' . $key . '">';
				$output .= '<div class="jwpf-panel-body">';
				$output .= isset($item->content) ? $item->content : '';
				$output .= '</div>';//.jwpf-panel-body
				$output .= '</div>';//.jwpf-panel-collapse
				$output .= '</div>';//.jwpf-panel
			}
		}


		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$css = '';

		//item style
		$item_style = '';
		$item_style .= (isset($settings->item_bg) && $settings->item_bg) ? 'background: ' . $settings->item_bg . ';' : '';
		$item_style .= (isset($settings->item_border_color) && $settings->item_border_color) ? 'border-color: ' . $settings->item_border_color . ';' : '';
		$item_style .= (isset($settings->item_border_width) && trim($settings->item_border_width)) ? 'border-width: ' . $settings->item_border_width . '; border-style: solid;' : '';
		$item_style .= (isset($settings->item_border_radius) && $settings->item_border_radius) ? 'border-radius: ' . $settings->item_border_radius . 'px;' : '';
		$item_style .= (isset($settings->item_margin) && trim($settings->item_margin)) ? 'margin: ' . $settings->item_margin . ';' : '';
		$item_style .= (isset($settings->item_padding) && trim($settings->item_padding)) ? 'padding: ' . $settings->item_padding . ';' : '';

		if ($item_style) {
			$css .= $addon_id . ' .jwpf-panel.jwpf-panel-custom {';
			$css .= $item_style;
			if (!isset($settings->item_margin) || $settings->item_margin == '0px 0px 0px 0px' || !trim($settings->item_margin)) {
				$css .= 'border-top-width: 0;';
			}
			$css .= '}';
			if (!isset($settings->item_margin) || $settings->item_margin == '0px 0px 0px 0px' || !trim($settings->item_margin)) {
				if (isset($settings->item_border_width) && $settings->item_border_width) {
					$border_top = explode(' ', $settings->item_border_width)[0];
				}
				$css .= $addon_id . ' .jwpf-panel-group > .jwpf-panel.jwpf-panel-custom:first-child {';
				$css .= 'border-top-width: ' . $border_top . ';';
				$css .= '}';
			}
		}

		//title style
		$title_style = '';
		$title_style .= (isset($settings->item_title_bg_color) && $settings->item_title_bg_color) ? 'background: ' . $settings->item_title_bg_color . ';' : '';
		$title_style .= (isset($settings->item_title_text_color) && $settings->item_title_text_color) ? 'color: ' . $settings->item_title_text_color . ';' : '';
		$title_style .= (isset($settings->item_title_fontsize) && $settings->item_title_fontsize) ? 'font-size: ' . $settings->item_title_fontsize . 'px;' : '';
		$title_style .= (isset($settings->item_title_lineheight) && $settings->item_title_lineheight) ? 'line-height: ' . $settings->item_title_lineheight . 'px;' : '';
		$title_style .= (isset($settings->item_title_letterspace) && $settings->item_title_letterspace) ? 'letter-spacing: ' . $settings->item_title_letterspace . ';' : '';
		$title_style .= (isset($settings->item_title_padding) && trim($settings->item_title_padding)) ? 'padding: ' . $settings->item_title_padding . ';' : '';

		$item_title_font_style = (isset($settings->item_title_font_style) && $settings->item_title_font_style) ? $settings->item_title_font_style : '';
		if (isset($item_title_font_style->underline) && $item_title_font_style->underline) {
			$title_style .= 'text-decoration:underline;';
		}
		if (isset($item_title_font_style->italic) && $item_title_font_style->italic) {
			$title_style .= 'font-style:italic;';
		}
		if (isset($item_title_font_style->uppercase) && $item_title_font_style->uppercase) {
			$title_style .= 'text-transform:uppercase;';
		}
		if (isset($item_title_font_style->weight) && $item_title_font_style->weight) {
			$title_style .= 'font-weight:' . $item_title_font_style->weight . ';';
		}
		if ($title_style) {
			$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-heading {';
			$css .= $title_style;
			$css .= '}';
			$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-heading .jwpf-panel-title{';
			if (isset($settings->item_title_fontsize) && $settings->item_title_fontsize) {
				$css .= 'font-size: ' . $settings->item_title_fontsize . 'px;';
			}
			if (isset($item_title_font_style->weight) && $item_title_font_style->weight) {
				$css .= 'font-weight:' . $item_title_font_style->weight . ';';
			}
			$css .= '}';
		}

		//icon style
		$icon_style = '';
		$icon_style .= (isset($settings->icon_text_color) && $settings->icon_text_color) ? 'color: ' . $settings->icon_text_color . ';' : '';
		$icon_style .= (isset($settings->icon_fontsize) && $settings->icon_fontsize) ? 'font-size: ' . $settings->icon_fontsize . 'px;' : '';
		$icon_style .= (isset($settings->icon_margin) && trim($settings->icon_margin)) ? 'margin: ' . $settings->icon_margin . ';' : '';

		if ($icon_style) {
			$css .= $addon_id . ' .jwpf-panel-custom .jwpf-accordion-icon-wrap {';
			$css .= $icon_style;
			$css .= '}';
		}

		//content style
		$content_style = '';
		$content_style .= (isset($settings->item_content_padding) && trim($settings->item_content_padding)) ? 'padding: ' . $settings->item_content_padding . ';' : '';
		$content_style .= (isset($settings->item_content_border_color) && $settings->item_content_border_color) ? 'border-color: ' . $settings->item_content_border_color . ';' : '';
		$content_style .= (isset($settings->item_content_border_width) && trim($settings->item_content_border_width)) ? 'border-width: ' . $settings->item_content_border_width . ';border-style:solid;' : '';

		if ($content_style) {
			$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-body {';
			$css .= $content_style;
			$css .= '}';
		}

		//active title style
		$active_title_style = '';
		$active_title_style .= (isset($settings->active_title_bg_color) && $settings->active_title_bg_color) ? 'background:' . $settings->active_title_bg_color . ';' : '';
		$active_title_style .= (isset($settings->active_title_text_color) && $settings->active_title_text_color) ? 'color:' . $settings->active_title_text_color . ';' : '';

		if ($active_title_style) {
			$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-heading.active {';
			$css .= $active_title_style;
			$css .= '}';
		}

		//Active icon style
		$active_icon_style = '';
		$active_icon_style .= (isset($settings->active_icon_color) && $settings->active_icon_color) ? 'color:' . $settings->active_icon_color . ';' : '';
		$active_icon_style .= (isset($settings->active_icon_rotate) && $settings->active_icon_rotate) ? 'transform: rotate(' . $settings->active_icon_rotate . 'deg);' : '';

		if ($active_icon_style) {
			$css .= $addon_id . ' .jwpf-panel-custom .active .jwpf-accordion-icon-wrap {';
			$css .= $active_icon_style;
			$css .= '}';
		}

		//Responsive

		//Item tab style
		$item_style_sm = '';
		$item_style_sm .= (isset($settings->item_margin_sm) && trim($settings->item_margin_sm)) ? 'margin: ' . $settings->item_margin_sm . ';' : '';
		$item_style_sm .= (isset($settings->item_padding_sm) && trim($settings->item_padding_sm)) ? 'padding: ' . $settings->item_padding_sm . ';' : '';

		//Title tab style
		$title_style_sm = '';
		$title_style_sm .= (isset($settings->item_title_padding_sm) && trim($settings->item_title_padding_sm)) ? 'padding: ' . $settings->item_title_padding_sm . ';' : '';
		$title_style_sm .= (isset($settings->item_title_fontsize_sm) && $settings->item_title_fontsize_sm) ? 'font-size: ' . $settings->item_title_fontsize_sm . 'px;' : '';
		$font_size_sm = (isset($settings->item_title_fontsize_sm) && $settings->item_title_fontsize_sm) ? 'font-size: ' . $settings->item_title_fontsize_sm . 'px;' : '';
		$title_style_sm .= (isset($settings->item_title_lineheight_sm) && $settings->item_title_lineheight_sm) ? 'line-height: ' . $settings->item_title_lineheight_sm . 'px;' : '';

		//Icon tab style
		$icon_style_sm = '';
		$icon_style_sm .= (isset($settings->icon_fontsize_sm) && $settings->icon_fontsize_sm) ? 'font-size: ' . $settings->icon_fontsize_sm . 'px;' : '';
		$icon_style_sm .= (isset($settings->icon_margin_sm) && trim($settings->icon_margin_sm)) ? 'margin: ' . $settings->icon_margin_sm . ';' : '';

		//Content tab style
		$content_style_sm = (isset($settings->item_content_padding_sm) && trim($settings->item_content_padding_sm)) ? 'padding: ' . $settings->item_content_padding_sm . ';' : '';

		if ($item_style_sm || $title_style_sm || $icon_style_sm || $content_style_sm) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			if ($item_style_sm) {
				$css .= $addon_id . ' .jwpf-panel.jwpf-panel-custom {';
				$css .= $item_style_sm;
				$css .= '}';
			}
			if ($title_style_sm) {
				$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-heading {';
				$css .= $title_style_sm;
				$css .= '}';
				$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-heading .jwpf-panel-title{';
				$css .= $font_size_sm;
				$css .= '}';
			}
			if ($icon_style_sm) {
				$css .= $addon_id . ' .jwpf-panel-custom .jwpf-accordion-icon-wrap {';
				$css .= $icon_style_sm;
				$css .= '}';
			}
			if ($content_style_sm) {
				$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-body {';
				$css .= $content_style_sm;
				$css .= '}';
			}
			$css .= '}';
		}
		//Item mobile style
		$item_style_xs = '';
		$item_style_xs .= (isset($settings->item_margin_xs) && trim($settings->item_margin_xs)) ? 'margin: ' . $settings->item_margin_xs . ';' : '';
		$item_style_xs .= (isset($settings->item_padding_xs) && trim($settings->item_padding_xs)) ? 'padding: ' . $settings->item_padding_xs . ';' : '';

		//Title mobile style
		$title_style_xs = '';
		$title_style_xs .= (isset($settings->item_title_padding_xs) && trim($settings->item_title_padding_xs)) ? 'padding: ' . $settings->item_title_padding_xs . ';' : '';
		$title_style_xs .= (isset($settings->item_title_fontsize_xs) && $settings->item_title_fontsize_xs) ? 'font-size: ' . $settings->item_title_fontsize_xs . 'px;' : '';
		$font_size_xs = (isset($settings->item_title_fontsize_xs) && $settings->item_title_fontsize_xs) ? 'font-size: ' . $settings->item_title_fontsize_xs . 'px;' : '';
		$title_style_xs .= (isset($settings->item_title_lineheight_xs) && $settings->item_title_lineheight_xs) ? 'line-height: ' . $settings->item_title_lineheight_xs . 'px;' : '';

		//Icon mobile style
		$icon_style_xs = '';
		$icon_style_xs .= (isset($settings->icon_fontsize_xs) && $settings->icon_fontsize_xs) ? 'font-size: ' . $settings->icon_fontsize_xs . 'px;' : '';
		$icon_style_xs .= (isset($settings->icon_margin_xs) && trim($settings->icon_margin_xs)) ? 'margin: ' . $settings->icon_margin_xs . ';' : '';

		//Content mobile style
		$content_style_xs = (isset($settings->item_content_padding_xs) && trim($settings->item_content_padding_xs)) ? 'padding: ' . $settings->item_content_padding_xs . ';' : '';

		if ($item_style_xs || $title_style_xs || $icon_style_xs || $content_style_xs) {
			$css .= '@media (max-width: 767px) {';
			if ($item_style_xs) {
				$css .= $addon_id . ' .jwpf-panel.jwpf-panel-custom {';
				$css .= $item_style_xs;
				$css .= '}';
			}
			if ($title_style_xs) {
				$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-heading {';
				$css .= $title_style_xs;
				$css .= '}';
				$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-heading .jwpf-panel-title{';
				$css .= $font_size_xs;
				$css .= '}';
			}
			if ($icon_style_xs) {
				$css .= $addon_id . ' .jwpf-panel-custom .jwpf-accordion-icon-wrap {';
				$css .= $icon_style_xs;
				$css .= '}';
			}
			if ($content_style_xs) {
				$css .= $addon_id . ' .jwpf-panel-custom .jwpf-panel-body {';
				$css .= $content_style_xs;
				$css .= '}';
			}
			$css .= '}';
		}

		return $css;
	}

	public function js()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$openitem = (isset($settings->openitem) && $settings->openitem) ? $settings->openitem : '';
		if ($openitem) {
			$js = "jQuery(document).ready(function($){'use strict';
				if('" . $openitem . "' === 'hide') {
					$( '" . $addon_id . "' + ' .jwpf-addon-accordion .jwpf-panel-heading').removeClass('active');
				} else {
					$( '" . $addon_id . "' + ' .jwpf-addon-accordion .jwpf-panel-heading').addClass('active');
				}
				$( '" . $addon_id . "' + ' .jwpf-addon-accordion .jwpf-panel-collapse')." . $openitem . "();
			});";
			return $js;
		}
		return;
	}

	public static function getTemplate()
	{
		$output = '
		<style  type="text/css">
			<# if(data.style == "panel-custom") { #>
				#jwpf-addon-{{ data.id }} .jwpf-panel.jwpf-panel-custom {
					background:{{data.item_bg}};
					border-color: {{data.item_border_color}};
					border-color: {{data.item_border_color}};
					border-width: {{data.item_border_width}};
					border-radius: {{data.item_border_radius}}px;
					<# if(_.isObject(data.item_margin)) { #>
						margin: {{data.item_margin.md}};
					<# } else { #>
						margin: {{data.item_margin}};
					<# }
					if(_.isObject(data.item_padding)) { #>
						padding: {{data.item_padding.md}};
					<# } else { #>
						padding: {{data.item_padding}};
					<# }
					if(_.trim(data.item_border_width)){ #>
						border-style: solid;
					<# }
					if(_.isObject(data.item_margin)){
						if(data.item_margin.md =="0px 0px 0px 0px" || !_.trim(data.item_margin.md)){ #>
							border-top-width: 0;
						<# }
					} #>
				}
				<# if(_.isObject(data.item_margin)){
					if(data.item_margin.md =="0px 0px 0px 0px" || !_.trim(data.item_margin.md)){
						let borderTop = _.split(data.item_border_width, " ")[0];
					#>
						#jwpf-addon-{{ data.id }} .jwpf-panel-group > .jwpf-panel.jwpf-panel-custom:first-child {
							border-top-width: {{borderTop}};
						}
					<# }
				} #>

				#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-heading {
					background: {{data.item_title_bg_color}};
					color: {{data.item_title_text_color}};
					<# if (_.isObject(data.item_title_fontsize)) { #>
						font-size: {{data.item_title_fontsize.md}}px;
					<# } else { #>
						font-size: {{data.item_title_fontsize}}px;
					<# }
					if (_.isObject(data.item_title_lineheight)) { #>
						line-height: {{data.item_title_lineheight.md}}px;
					<# } else { #>
						line-height: {{data.item_title_lineheight}}px;
					<# }
					if (_.isObject(data.item_title_padding)) { #>
						padding: {{data.item_title_padding.md}};
					<# } else { #>
						padding: {{data.item_title_padding}};
					<# }
					if(_.isObject(data.item_title_font_style)){
						if(data.item_title_font_style.underline){ #>
							text-decoration:underline;
						<# }
						if(data.item_title_font_style.italic){ #>
							font-style:italic;
						<# }
						if(data.item_title_font_style.uppercase){ #>
							text-transform:uppercase;
						<# }
						if(data.item_title_font_style.weight){ #>
							font-weight:{{data.item_title_font_style.weight}};
						<# }
					} #>
					letter-spacing: {{data.item_title_letterspace}};
				}

				#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-heading .jwpf-panel-title{
					<# if (!_.isEmpty(data.item_title_fontsize) && data.item_title_fontsize) { #>
						<# if(_.isObject(data.item_title_fontsize)){ #>
							font-size: {{data.item_title_fontsize.md}}px;
						<# } else { #>
							font-size: {{data.item_title_fontsize}}px;
						<# } #>
					<# }
					if(_.isObject(data.item_title_font_style)){
						if(data.item_title_font_style.weight){ #>
							font-weight:{{data.item_title_font_style.weight}};
						<# }
					} #>
				}
					
				#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-accordion-icon-wrap {
					color: {{data.icon_text_color}};
					<# if(_.isObject(data.icon_fontsize)){ #>
						font-size: {{data.icon_fontsize.md}}px;
					<# } else { #>
						font-size: {{data.icon_fontsize}}px;
					<# }
					if(_.isObject(data.icon_margin)) { #>
						margin: {{data.icon_margin.md}};
					<# } else { #>
						margin: {{data.icon_margin}};
					<# } #>
				}

				#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-body {
					border-color: {{data.item_content_border_color}};
					border-width: {{data.item_content_border_width}};
					<# if(_.trim(data.item_content_border_width)){ #>
						border-style:solid;
					<# }
					if (_.isObject(data.item_content_padding)) { #>
						padding: {{data.item_content_padding.md}};
					<# } else { #>
						padding: {{data.item_content_padding}};
					<# } #>
				}

				#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-heading.active {
					background: {{data.active_title_bg_color}};
					color: {{data.active_title_text_color}};
				}

				#jwpf-addon-{{ data.id }} .jwpf-panel-custom .active .jwpf-accordion-icon-wrap {
					color:{{data.active_icon_color}};
					transform: rotate({{data.active_icon_rotate}}deg);
				}

				@media (min-width: 768px) and (max-width: 991px) {
					#jwpf-addon-{{ data.id }} .jwpf-panel.jwpf-panel-custom {
						<# if(_.isObject(data.item_margin)) { #>
							margin: {{data.item_margin.sm}};
						<# }
						if(_.isObject(data.item_padding)) { #>
							padding: {{data.item_padding.sm}};
						<# } #>
					}
					#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-heading {
						<# if (_.isObject(data.item_title_fontsize)) { #>
							font-size: {{data.item_title_fontsize.sm}}px;
						<# }
						if (_.isObject(data.item_title_lineheight)) { #>
							line-height: {{data.item_title_lineheight.sm}}px;
						<# }
						if (_.isObject(data.item_title_padding)) { #>
							padding: {{data.item_title_padding.sm}};
						<# } #>
					}
					<# if (!_.isEmpty(data.item_title_fontsize) && data.item_title_fontsize) { #>
						#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-heading .jwpf-panel-title{
							<# if(_.isObject(data.item_title_fontsize)){ #>
								font-size: {{data.item_title_fontsize.sm}}px;
							<# } #>
						}
					<# } #>

					#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-accordion-icon-wrap {
						<# if(_.isObject(data.icon_fontsize)){ #>
							font-size: {{data.icon_fontsize.sm}}px;
						<# }
						if(_.isObject(data.icon_margin)) { #>
							margin: {{data.icon_margin.sm}};
						<# } #>
					}
					#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-body {
						<# if (_.isObject(data.item_content_padding)) { #>
							padding: {{data.item_content_padding.sm}};
						<# } #>
					}
				}

				@media (max-width: 767px) {
					#jwpf-addon-{{ data.id }} .jwpf-panel.jwpf-panel-custom {
						<# if(_.isObject(data.item_margin)) { #>
							margin: {{data.item_margin.xs}};
						<# }
						if(_.isObject(data.item_padding)) { #>
							padding: {{data.item_padding.xs}};
						<# } #>
					}
					#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-heading {
						<# if (_.isObject(data.item_title_fontsize)) { #>
							font-size: {{data.item_title_fontsize.xs}}px;
						<# }
						if (_.isObject(data.item_title_lineheight)) { #>
							line-height: {{data.item_title_lineheight.xs}}px;
						<# }
						if (_.isObject(data.item_title_padding)) { #>
							padding: {{data.item_title_padding.xs}};
						<# } #>
					}
					<# if (!_.isEmpty(data.item_title_fontsize) && data.item_title_fontsize) { #>
						#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-heading .jwpf-panel-title{
							<# if(_.isObject(data.item_title_fontsize)){ #>
								font-size: {{data.item_title_fontsize.xs}}px;
							<# } #>
						}
					<# } #>
					#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-accordion-icon-wrap {
						<# if(_.isObject(data.icon_fontsize)){ #>
							font-size: {{data.icon_fontsize.xs}}px;
						<# }
						if(_.isObject(data.icon_margin)) { #>
							margin: {{data.icon_margin.xs}};
						<# } #>
					}
					#jwpf-addon-{{ data.id }} .jwpf-panel-custom .jwpf-panel-body {
						<# if (_.isObject(data.item_content_padding)) { #>
							padding: {{data.item_content_padding.xs}};
						<# } #>
					}
				}
			<# } #>
		</style>
		<div class="jwpf-addon jwpf-addon-accordion {{ data.class }}">
			<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title">{{ data.title }}</{{ data.heading_selector }}><# } #>
			<div class="jwpf-addon-content">
				<div class="jwpf-panel-group">
					<# _.each(data.jw_accordion_item, function(accordion_item, key){ #>
						<# var activeClass = ((key == 0 || data.openitem == "show") &&  data.openitem != "hide") ? "active" : ""; #>
						<div class="jwpf-panel jwpf-{{ data.style }}">
							<div class="jwpf-panel-heading {{ activeClass }} <# if(data.icon_position == "right"){ #> jwpf-accordion-icon-position-right <# } #>">
								<# if(accordion_item.icon != "" && data.style == "panel-custom"){ #>
									<span class="jwpf-accordion-icon-wrap">
										<#
										let icon_arr = (typeof accordion_item.icon !== "undefined" && accordion_item.icon) ? accordion_item.icon.split(" ") : "";
										let icon_name = icon_arr.length === 1 ? "fa "+accordion_item.icon : accordion_item.icon;
										#>
										<i class="{{ icon_name }}"></i>
									</span>
								<# } #>
								<span class="jwpf-panel-title">
									<# if(accordion_item.icon != "" && data.style !== "panel-custom"){
										let title_icon_arr = (typeof accordion_item.icon !== "undefined" && accordion_item.icon) ? accordion_item.icon.split(" ") : "";
										let title_icon_name = title_icon_arr.length === 1 ? "fa "+accordion_item.icon : accordion_item.icon;
									#>
										<i class="{{ title_icon_name }}"></i>
									<# } #>
									{{ accordion_item.title }}
								</span>
								<# if(data.style !== "panel-custom") { #>
									<span class="jwpf-toggle-direction"><i class="fa fa-chevron-right"></i></span>
								<# } #>
							</div>
							<# var panelStyle = ((key != 0 || data.openitem == "hide") && data.openitem != "show") ? "display: none;" : ""; #>
							<div class="jwpf-panel-collapse" style="{{ panelStyle }}">
								<div class="jwpf-panel-body">
									<#
									var htmlContent = "";
									_.each(accordion_item.content, function(content){
										htmlContent += content;
									});
									#>
									{{{ htmlContent }}}
								</div>
							</div>
						</div>
					<# }); #>
				</div>
			</div>
		</div>';
		return $output;
	}
}
