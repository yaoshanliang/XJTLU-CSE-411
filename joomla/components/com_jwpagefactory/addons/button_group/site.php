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

class JwpagefactoryAddonButton_group extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? ' ' . $settings->class : '';
		$class .= (isset($settings->alignment) && $settings->alignment) ? ' ' . $settings->alignment : '';

		$output = '<div class="jwpf-addon jwpf-addon-button-group' . $class . '">';
		$output .= '<div class="jwpf-addon-content">';

		if (isset($settings->jw_button_group_item) && count((array)$settings->jw_button_group_item)) {

			foreach ($settings->jw_button_group_item as $key => $value) {
				if ($value->title || $value->icon) {
					$class = (isset($value->type) && $value->type) ? ' jwpf-btn-' . $value->type : '';
					$class .= (isset($value->size) && $value->size) ? ' jwpf-btn-' . $value->size : '';
					$class .= (isset($value->block) && $value->block) ? ' ' . $value->block : '';
					$class .= (isset($value->shape) && $value->shape) ? ' jwpf-btn-' . $value->shape : ' jwpf-btn-rounded';
					$class .= (isset($value->appearance) && $value->appearance) ? ' jwpf-btn-' . $value->appearance : '';
					$attribs = (isset($value->target) && $value->target) ? ' rel="noopener noreferrer" target="' . $value->target . '"' : '';
					$attribs .= (isset($value->url) && $value->url) ? ' href="' . $value->url . '"' : '';
					$attribs .= ' id="btn-' . ($this->addon->id + $key) . '"';
					$text = (isset($value->title) && $value->title) ? $value->title : '';
					$icon = (isset($value->icon) && $value->icon) ? $value->icon : '';
					$icon_position = (isset($value->icon_position) && $value->icon_position) ? $value->icon_position : 'left';

					$icon_arr = array_filter(explode(' ', $icon));
					if (count($icon_arr) === 1) {
						$icon = 'fa ' . $icon;
					}

					if ($icon_position == 'left') {
						$text = ($icon) ? '<i class="' . $icon . '" aria-hidden="true"></i> ' . $text : $text;
					} else {
						$text = ($icon) ? $text . ' <i class="' . $icon . '" aria-hidden="true"></i>' : $text;
					}

					$output .= '<a' . $attribs . ' class="jwpf-btn ' . $class . '">' . $text . '</a>';
				}
			}
		}

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_jwpagefactory/layouts';
		$margin = (isset($settings->margin) && $settings->margin) ? $settings->margin : '';
		$margin_sm = ((isset($settings->margin_sm)) && $settings->margin_sm) ? $settings->margin_sm : '';
		$margin_xs = ((isset($settings->margin_xs)) && $settings->margin_xs) ? $settings->margin_xs : '';

		$css = '';
		if ($margin) {
			$css .= $addon_id . ' .jwpf-addon-content {';
			$css .= 'margin: -' . (int)$margin . 'px;';
			$css .= '}';

			$css .= $addon_id . ' .jwpf-addon-content .jwpf-btn {';
			$css .= 'margin: ' . (int)$margin . 'px;';
			$css .= '}';
		}

		if ($margin_sm) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			$css .= $addon_id . ' .jwpf-addon-content {';
			$css .= 'margin: -' . (int)$margin_sm . 'px;';
			$css .= '}';

			$css .= $addon_id . ' .jwpf-addon-content .jwpf-btn {';
			$css .= 'margin: ' . (int)$margin_sm . 'px;';
			$css .= '}';
			$css .= '}';
		}

		if ($margin_xs) {
			$css .= '@media (max-width: 767px) {';
			$css .= $addon_id . ' .jwpf-addon-content {';
			$css .= 'margin: -' . (int)$margin_xs . 'px;';
			$css .= '}';

			$css .= $addon_id . ' .jwpf-addon-content .jwpf-btn {';
			$css .= 'margin: ' . (int)$margin_xs . 'px;';
			$css .= '}';
			$css .= '}';
		}

		// Buttons style
		if (isset($settings->jw_button_group_item) && count((array)$settings->jw_button_group_item)) {
			foreach ($settings->jw_button_group_item as $key => $value) {
				if ($value->title) {
					$css_path = new JLayoutFile('addon.css.button', $layout_path);

					$options = new stdClass;
					$options->button_type = (isset($value->type) && $value->type) ? $value->type : '';
					$options->button_appearance = (isset($value->appearance) && $value->appearance) ? $value->appearance : '';
					$options->button_color = (isset($value->color) && $value->color) ? $value->color : '';
					$options->button_color_hover = (isset($value->color_hover) && $value->color_hover) ? $value->color_hover : '';
					$options->button_background_color = (isset($value->background_color) && $value->background_color) ? $value->background_color : '';
					$options->button_background_color_hover = (isset($value->background_color_hover) && $value->background_color_hover) ? $value->background_color_hover : '';
					$options->button_padding = (isset($value->button_padding) && $value->button_padding) ? $value->button_padding : '';
					$options->button_padding_sm = (isset($value->button_padding_sm) && $value->button_padding_sm) ? $value->button_padding_sm : '';
					$options->button_padding_xs = (isset($value->button_padding_xs) && $value->button_padding_xs) ? $value->button_padding_xs : '';
					$options->fontsize = (isset($value->fontsize) && $value->fontsize) ? $value->fontsize : '';
					//Button Type Link
					$options->link_button_color = (isset($value->link_button_color) && $value->link_button_color) ? $value->link_button_color : '';
					$options->link_border_color = (isset($value->link_border_color) && $value->link_border_color) ? $value->link_border_color : '';
					$options->link_button_border_width = (isset($value->link_button_border_width) && $value->link_button_border_width) ? $value->link_button_border_width : '';
					$options->link_button_padding_bottom = (isset($value->link_button_padding_bottom) && gettype($value->link_button_padding_bottom) == 'string') ? $value->link_button_padding_bottom : '';
					//Link Hover
					$options->link_button_hover_color = (isset($value->link_button_hover_color) && $value->link_button_hover_color) ? $value->link_button_hover_color : '';
					$options->link_button_border_hover_color = (isset($value->link_button_border_hover_color) && $value->link_button_border_hover_color) ? $value->link_button_border_hover_color : '';

					$options->fontsize_sm = (isset($value->fontsize_sm) && $value->fontsize_sm) ? $value->fontsize_sm : '';
					$options->fontsize_xs = (isset($value->fontsize_xs) && $value->fontsize_xs) ? $value->fontsize_xs : '';
					$options->button_font_family = (isset($value->font_family) && $value->font_family) ? $value->font_family : '';
					$options->button_font_family_selector = (isset($value->font_family_selector) && $value->font_family_selector) ? $value->font_family_selector : '';
					$options->button_fontstyle = (isset($value->fontstyle) && $value->fontstyle) ? $value->fontstyle : '';
					$options->button_font_style = (isset($value->font_style) && $value->font_style) ? $value->font_style : '';
					$options->button_letterspace = (isset($value->letterspace) && $value->letterspace) ? $value->letterspace : '';
					$options->button_background_gradient = (isset($value->background_gradient) && $value->background_gradient) ? $value->background_gradient : new stdClass();
					$options->button_background_gradient_hover = (isset($value->background_gradient_hover) && $value->background_gradient_hover) ? $value->background_gradient_hover : new stdClass();

					$selector_css = new JLayoutFile('addon.css.selector', $layout_path);
					$css .= $selector_css->render(
						array(
							'options' => $value,
							'addon_id' => $addon_id,
							'selector' => '#btn-' . ($this->addon->id + $key)
						)
					);

					$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $options, 'id' => 'btn-' . ($this->addon->id + $key)));
				}
			}
		}

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
		<#
			var addonId = data.id;
		#>
		<style type="text/css">
			#jwpf-addon-{{ addonId }} .jwpf-addon-content {
				<# if(_.isObject(data.margin)){ #>
					margin: -{{ data.margin.md }}px;
				<# } else { #>
					margin: -{{ data.margin }}px;
				<# } #>
			}
			#jwpf-addon-{{ addonId }} .jwpf-addon-content .jwpf-btn {
				<# if(_.isObject(data.margin)){ #>
					margin: {{ data.margin.md }}px;
				<# } else { #>
					margin: {{ data.margin }}px;
				<# } #>
			}
			<# _.each(data.jw_button_group_item, function(button, key){ #>
				<#

					let button_fontstyle = button.fontstyle || "";
					let button_font_style = button.font_style || "";
					let modern_font_style = false;

					let button_padding = "";
					let button_padding_sm = "";
					let button_padding_xs = "";

					let font_size = "";
					let font_size_sm = "";
					let font_size_xs = "";
					if(button.button_padding){
						if(_.isObject(button.button_padding)){
							if(_.trim(button.button_padding.md) !== ""){
								button_padding = _.split(button.button_padding.md, " ").map(item => {
									if(_.isEmpty(item)){
										return "0";
									}
									return item;
								}).join(" ")
							}

							if(_.trim(button.button_padding.sm) !== ""){
								button_padding_sm = _.split(button.button_padding.sm, " ").map(item => {
									if(_.isEmpty(item)){
										return "0";
									}
									return item;
								}).join(" ")
							}

							if(_.trim(button.button_padding.xs) !== ""){
								button_padding_xs = _.split(button.button_padding.xs, " ").map(item => {
									if(_.isEmpty(item)){
										return "0";
									}
									return item;
								}).join(" ")
							}
						} else {
							button_padding = _.split(button.button_padding, " ").map(item => {
								if(_.isEmpty(item)){
									return "0";
								}
								return item;
							}).join(" ")
						}

					}
					if(_.isObject(button.fontsize)){
						font_size += \'font-size:\'+ button.fontsize.md + \'px;\';
					} else {
						font_size += \'font-size:\'+ button.fontsize + \'px;\';
					}
					if(_.isObject(button.fontsize)){
						font_size_sm += \'font-size:\'+ button.fontsize.sm + \'px;\';
					} else {
						font_size_sm += \'font-size:\'+ button.fontsize + \'px;\';
					}
					if(_.isObject(button.fontsize)){
						font_size_xs += \'font-size:\'+ button.fontsize.xs + \'px;\';
					} else {
						font_size_xs += \'font-size:\'+ button.fontsize + \'px;\';
					}
				#>
				#jwpf-addon-{{ addonId }} #btn-{{ addonId }}{{ key }}.jwpf-btn-{{ button.type }}{
					letter-spacing: {{ button.letterspace }};
					<# if(_.isObject(button_font_style) && button_font_style.underline) { #>
						text-decoration: underline;
						<# modern_font_style = true #>
					<# } #>

					<# if(_.isObject(button_font_style) && button_font_style.italic) { #>
						font-style: italic;
						<# modern_font_style = true #>
					<# } #>

					<# if(_.isObject(button_font_style) && button_font_style.uppercase) { #>
						text-transform: uppercase;
						<# modern_font_style = true #>
					<# } #>

					<# if(_.isObject(button_font_style) && button_font_style.weight) { #>
						font-weight: {{ button_font_style.weight }};
						<# modern_font_style = true #>
					<# } #>

					<# if(!modern_font_style) { #>
						<# if(_.isArray(button_fontstyle)) { #>
							<# if(button_fontstyle.indexOf("underline") !== -1){ #>
								text-decoration: underline;
							<# } #>
							<# if(button_fontstyle.indexOf("uppercase") !== -1){ #>
								text-transform: uppercase;
							<# } #>
							<# if(button_fontstyle.indexOf("italic") !== -1){ #>
								font-style: italic;
							<# } #>
							<# if(button_fontstyle.indexOf("lighter") !== -1){ #>
								font-weight: lighter;
							<# } else if(button_fontstyle.indexOf("normal") !== -1){#>
								font-weight: normal;
							<# } else if(button_fontstyle.indexOf("bold") !== -1){#>
								font-weight: bold;
							<# } else if(button_fontstyle.indexOf("bolder") !== -1){#>
								font-weight: bolder;
							<# } #>
						<# } #>
					<# } #>
				}

				<# if(button.type == "custom"){ #>
					#jwpf-addon-{{ addonId }} #btn-{{ addonId }}{{ key }}.jwpf-btn-custom{
						color: {{ button.color }};
						padding: {{ button_padding }};
						{{font_size}}
						<# if(button.appearance == "outline"){ #>
							border-color: {{ button.background_color }};
							background-color: transparent;
						<# } else if(button.appearance == "3d"){ #>
							border-bottom-color: {{ button.background_color_hover }};
							background-color: {{ button.background_color }};
						<# } else if(button.appearance == "gradient" && _.isObject(button.background_gradient)){ #>
							border: none;
							<# if(typeof button.background_gradient.type !== "undefined" && button.background_gradient.type == "radial"){ #>
								background-image: radial-gradient(at {{ button.background_gradient.radialPos || "center center"}}, {{ button.background_gradient.color }} {{ button.background_gradient.pos || 0 }}%, {{ button.background_gradient.color2 }} {{ button.background_gradient.pos2 || 100 }}%);
							<# } else { #>
								background-image: linear-gradient({{ button.background_gradient.deg || 0}}deg, {{ button.background_gradient.color }} {{ button.background_gradient.pos || 0 }}%, {{ button.background_gradient.color2 }} {{ button.background_gradient.pos2 || 100 }}%);
							<# } #>
						<# } else { #>
							background-color: {{ button.background_color }};
						<# } #>
					}

					#jwpf-addon-{{ addonId }} #btn-{{ addonId }}{{ key }}.jwpf-btn-custom:hover{
						color: {{ button.color_hover }};
						background-color: {{ button.background_color_hover }};
						<# if(button.appearance == "outline"){ #>
							border-color: {{ button.background_color_hover }};
						<# } else if(button.appearance == "gradient" && _.isObject(button.background_gradient_hover)){ #>
							<# if(typeof button.background_gradient_hover.type !== "undefined" && button.background_gradient_hover.type == "radial"){ #>
								background-image: radial-gradient(at {{ button.background_gradient_hover.radialPos || "center center"}}, {{ button.background_gradient_hover.color }} {{ button.background_gradient_hover.pos || 0 }}%, {{ button.background_gradient_hover.color2 }} {{ button.background_gradient_hover.pos2 || 100 }}%);
							<# } else { #>
								background-image: linear-gradient({{ button.background_gradient_hover.deg || 0}}deg, {{ button.background_gradient_hover.color }} {{ button.background_gradient_hover.pos || 0 }}%, {{ button.background_gradient_hover.color2 }} {{ button.background_gradient_hover.pos2 || 100 }}%);
							<# } #>
						<# } #>
					}
					@media (min-width: 768px) and (max-width: 991px) {
						#jwpf-addon-{{ addonId }} #btn-{{ addonId }}{{ key }}.jwpf-btn-custom{
							padding: {{ button_padding_sm }};
							{{font_size_sm}}
						}
					}
					@media (max-width: 767px) {
						#jwpf-addon-{{ addonId }} #btn-{{ addonId }}{{ key }}.jwpf-btn-custom{
							padding: {{ button_padding_xs }};
							{{font_size_xs}}
						}
					}
				<# } #>
				<# if(button.type == "link"){ #>
					#jwpf-addon-{{ addonId }} #btn-{{ addonId }}{{ key }}.jwpf-btn-link{
						color: {{button.link_button_color}};
						border-color: {{button.link_border_color}};
						border-width: 0 0 {{button.link_button_border_width}}px 0;
						padding: 0 0 {{button.link_button_padding_bottom}}px 0;
						text-decoration: none;
						border-radius: 0;
					}
					<# if(button.link_button_status == "hover") { #>
						#jwpf-addon-{{ addonId }} #btn-{{ addonId }}{{ key }}.jwpf-btn-link:hover,
						#jwpf-addon-{{ addonId }} #btn-{{ addonId }}{{ key }}.jwpf-btn-link:focus{
							color: {{button.link_button_hover_color}};
							border-color: {{button.link_button_border_hover_color}};
						}
					<# } #>
				<# } #>
			<# }); #>

			@media (min-width: 768px) and (max-width: 991px) {
				#jwpf-addon-{{ addonId }} .jwpf-addon-content {
					<# if(_.isObject(data.margin)){ #>
						margin: -{{ data.margin.sm }}px;
					<# } #>
				}
				#jwpf-addon-{{ addonId }} .jwpf-addon-content .jwpf-btn {
					<# if(_.isObject(data.margin)){ #>
						margin: {{ data.margin.sm }}px;
					<# } #>
				}
			}
			@media (max-width: 767px) {
				#jwpf-addon-{{ addonId }} .jwpf-addon-content {
					<# if(_.isObject(data.margin)){ #>
						margin: -{{ data.margin.xs }}px;
					<# } #>
				}
				#jwpf-addon-{{ addonId }} .jwpf-addon-content .jwpf-btn {
					<# if(_.isObject(data.margin)){ #>
						margin: {{ data.margin.xs }}px;
					<# } #>
				}
			}

		</style>
		<div class="jwpf-addon jwpf-addon-button-group {{ data.alignment }} {{ data.class }}">
			<div class="jwpf-addon-content">
				<# _.each(data.jw_button_group_item, function(button, key){ #>
					<#
					var classList = button.class;
					classList += " jwpf-btn-"+button.type;
					classList += " jwpf-btn-"+button.size;
					classList += " jwpf-btn-"+button.shape;
					if(!_.isEmpty(button.appearance)){
						classList += " jwpf-btn-"+button.appearance;
					}

					classList += " "+button.block;
					#>
					<a href="{{ button.url }}" id="btn-{{ addonId }}{{ key }}" target="{{ button.target }}" class="jwpf-btn {{ classList }}"><# if(button.icon_position == "left" && !_.isEmpty(button.icon)) { #><i class="{{ icon_name }}"></i> <# } #>{{ button.title }}<# if(button.icon_position == "right" && !_.isEmpty(button.icon)) { #> <i class="{{ icon_name }}"></i><# } #></a>
				<# }); #>
			</div>
		</div>
		<# if(!data.jw_button_group_item.length){ #>
			<div class="jwpf-empty-addon">
				<span class="pbfont pbfont-pagefactory"></span>
			</div>
		<# } #>
		';

		return $output;
	}

}
