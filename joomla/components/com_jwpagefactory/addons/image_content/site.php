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

class JwpagefactoryAddonImage_content extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		//Options
		$image = (isset($settings->image) && $settings->image) ? $settings->image : '';
		$image_width = (isset($settings->image_width) && $settings->image_width) ? $settings->image_width : '';
		$image_alignment = (isset($settings->image_alignment) && $settings->image_alignment) ? $settings->image_alignment : '';
		$text = (isset($settings->text) && $settings->text) ? $settings->text : '';
		$button_text = (isset($settings->button_text) && $settings->button_text) ? $settings->button_text : '';
		$button_url = (isset($settings->button_url) && $settings->button_url) ? $settings->button_url : '';
		$button_classes = (isset($settings->button_size) && $settings->button_size) ? ' jwpf-btn-' . $settings->button_size : '';
		$button_classes .= (isset($settings->button_type) && $settings->button_type) ? ' jwpf-btn-' . $settings->button_type : '';
		$button_classes .= (isset($settings->button_shape) && $settings->button_shape) ? ' jwpf-btn-' . $settings->button_shape : ' jwpf-btn-rounded';
		$button_classes .= (isset($settings->button_appearance) && $settings->button_appearance) ? ' jwpf-btn-' . $settings->button_appearance : '';
		$button_classes .= (isset($settings->button_block) && $settings->button_block) ? ' ' . $settings->button_block : '';
		$button_icon = (isset($settings->button_icon) && $settings->button_icon) ? $settings->button_icon : '';
		$button_icon_position = (isset($settings->button_icon_position) && $settings->button_icon_position) ? $settings->button_icon_position : 'left';
		$button_position = (isset($settings->button_position) && $settings->button_position) ? $settings->button_position : '';
		$button_attribs = (isset($settings->button_target) && $settings->button_target) ? ' target="' . $settings->button_target . '" rel="noopener noreferrer"' : '';
		$button_attribs .= (isset($settings->button_url) && $settings->button_url) ? ' href="' . $settings->button_url . '"' : '';

		$icon_arr = array_filter(explode(' ', $button_icon));
		if (count($icon_arr) === 1) {
			$button_icon = 'fa ' . $button_icon;
		}

		if ($button_icon_position == 'left') {
			$button_text = ($button_icon) ? '<i class="' . $button_icon . '" aria-hidden="true"></i> ' . $button_text : $button_text;
		} else {
			$button_text = ($button_icon) ? $button_text . ' <i class="' . $button_icon . '" aria-hidden="true"></i>' : $button_text;
		}

		$button_output = !empty($button_text) ? '<a' . $button_attribs . ' id="btn-' . $this->addon->id . '" class="jwpf-btn' . $button_classes . '">' . $button_text . '</a>' : '';

		if ($image_alignment == 'left') {
			$content_class = ' jwpf-col-sm-offset-6';
		} else {
			$content_class = '';
		}

		if ($image && $text) {

			$output = '<div class="jwpf-addon jwpf-addon-image-content aligment-' . $image_alignment . ' clearfix ' . $class . '">';

			//Image
			if (strpos($image, 'http://') !== false || strpos($image, 'https://') !== false) {
				$output .= '<div class="jwpf-image-holder" style="background-image: url(' . $image . ');" role="img" aria-label="' . strip_tags($title) . '">';
			} else {
				$output .= '<div class="jwpf-image-holder" style="background-image: url(' . JURI::base(true) . '/' . $image . ');" role="img" aria-label="' . strip_tags($title) . '">';
			}
			$output .= '</div>';

			//Content
			$output .= '<div class="jwpf-container">';
			$output .= '<div class="jwpf-row">';

			if ($image_alignment == 'left') {
				$output .= '<div class="jwpf-col-sm-6"></div>';
			}

			$output .= '<div class="jwpf-col-sm-6' . $content_class . '">';
			$output .= '<div class="jwpf-content-holder">';
			$output .= ($title) ? '<' . $heading_selector . ' class="jwpf-image-content-title jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';
			$output .= ($text) ? '<p class="jwpf-image-content-text">' . $text . '</p>' : '';

			$output .= $button_output;

			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

			$output .= '</div>';

			return $output;
		}

		return;
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_jwpagefactory/layouts';
		$css_path = new JLayoutFile('addon.css.button', $layout_path);
		$css = '';

		$padding = (isset($settings->content_padding)) ? JwpagefactoryHelperSite::getPaddingMargin($settings->content_padding, 'padding') : '';
		$padding_sm = (isset($settings->content_padding_sm)) ? JwpagefactoryHelperSite::getPaddingMargin($settings->content_padding_sm, 'padding') : '';
		$padding_xs = (isset($settings->content_padding_xs)) ? JwpagefactoryHelperSite::getPaddingMargin($settings->content_padding_xs, 'padding') : '';


		$css .= (!empty($padding)) ? $addon_id . ' .jwpf-addon-image-content .jwpf-content-holder{' . $padding . '}' : '';
		$css .= (!empty($padding_sm)) ? '@media (min-width: 768px) and (max-width: 991px) {' . $addon_id . ' .jwpf-addon-image-content .jwpf-content-holder{' . $padding_sm . '}}' : '';
		$css .= (!empty($padding_xs)) ? '@media (max-width: 767px) {' . $addon_id . ' .jwpf-addon-image-content .jwpf-content-holder{' . $padding_xs . '}}' : '';

		$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $settings, 'id' => 'btn-' . $this->addon->id));

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
		<#
			var content_class = "";
			if(data.image_alignment == "left") {
				content_class = " jwpf-col-sm-offset-6";
			}

			var button_text = data.button_text;
			let icon_arr = (typeof data.button_icon !== "undefined" && data.button_icon) ? data.button_icon.split(" ") : "";
			let icon_name = icon_arr.length === 1 ? "fa "+data.button_icon : data.button_icon;
			if(data.button_icon_position == "left" && data.button_icon) {
				button_text = \'<i class="\' + icon_name + \'"></i> \' + data.button_text;
			} else if(data.button_icon){
				button_text = data.button_text + \' <i class="\' + icon_name + \'"></i>\';
			}

			var button_classes = "";

			if(data.button_size){
				button_classes = button_classes + " jwpf-btn-" + data.button_size;
			}

			if(data.button_type){
				button_classes = button_classes + " jwpf-btn-" + data.button_type;
			}

			if(data.button_shape){
				button_classes = button_classes + " jwpf-btn-" + data.button_shape;
			} else {
				button_classes = button_classes + " jwpf-btn-rounded";
			}

			if(data.button_appearance){
				button_classes = button_classes + " jwpf-btn-" + data.button_appearance;
			}

			if(data.button_block){
				button_classes = button_classes + " " + data.button_block;
			}

			var button_fontstyle = data.button_font_style || "";

			var padding = "";
			var padding_sm = "";
			var padding_xs = "";
			if(data.content_padding){
				if(_.isObject(data.content_padding)){
					if(data.content_padding.md.trim() !== ""){
						padding = data.content_padding.md.split(" ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}

					if(data.content_padding.sm.trim() !== ""){
						padding_sm = data.content_padding.sm.split(" ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}

					if(data.content_padding.xs.trim() !== ""){
						padding_xs = data.content_padding.xs.split(" ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}
				}
			}
		#>
		<style type="text/css">
			#jwpf-addon-{{ data.id }} .jwpf-image-holder{
				<# if(data.image.indexOf("https://") == -1 && data.image.indexOf("https://") == -1){ #>
					background-image: url({{ pagefactory_base + data.image }});
				<# } else { #>
					background-image: url({{ data.image }});
				<# } #>
			}
			#jwpf-addon-{{ data.id }} .jwpf-addon-image-content .jwpf-content-holder{
				padding: {{ padding }};
			}
			#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-{{ data.button_type }}{
				letter-spacing: {{ data.button_letterspace }};
				<# if(typeof data.button_margin_top !== "undefined" && typeof data.button_margin_top.md !== "undefined"){ #>
					margin-top: {{ data.button_margin_top.md }}px;
				<# } #>
				<# if(_.isObject(button_fontstyle)) { #>
					<# if(button_fontstyle.underline == 1){ #>
						text-decoration: underline;
					<# } #>
					<# if(button_fontstyle.uppercase == 1){ #>
						text-transform: uppercase;
					<# } #>
					<# if(button_fontstyle.italic == 1){ #>
						font-style: italic;
					<# } #>
					<# if(button_fontstyle.weight == 100){ #>
						font-weight: 100;
					<# } else if(button_fontstyle.weight == 200){#>
						font-weight: 200;
					<# } else if(button_fontstyle.weight == 300){#>
						font-weight: 300;
					<# } else if(button_fontstyle.weight == 400){#>
						font-weight: 400;
					<# } else if(button_fontstyle.weight == 500){#>
						font-weight: 500;
					<# } else if(button_fontstyle.weight == 600){#>
						font-weight: 600;
					<# } else if(button_fontstyle.weight == 700){#>
						font-weight: 700;
					<# } else if(button_fontstyle.weight == 800){#>
						font-weight: 800;
					<# } else if(button_fontstyle.weight == 900){#>
						font-weight: 900;
					<# } #>
				<# } #>
			}

			<# if(data.button_type == "custom"){ #>
				#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom{
					color: {{ data.button_color }};
					<# if(data.button_appearance == "outline"){ #>
						border-color: {{ data.button_background_color }}
					<# } else if(data.button_appearance == "3d"){ #>
						border-bottom-color: {{ data.button_background_color_hover }};
						background-color: {{ data.button_background_color }};
					<# } else if(data.button_appearance == "gradient"){ #>
						border: none;
						<# if(typeof data.button_background_gradient.type !== "undefined" && data.button_background_gradient.type == "radial"){ #>
							background-image: radial-gradient(at {{ data.button_background_gradient.radialPos || "center center"}}, {{ data.button_background_gradient.color }} {{ data.button_background_gradient.pos || 0 }}%, {{ data.button_background_gradient.color2 }} {{ data.button_background_gradient.pos2 || 100 }}%);
						<# } else { #>
							background-image: linear-gradient({{ data.button_background_gradient.deg || 0}}deg, {{ data.button_background_gradient.color }} {{ data.button_background_gradient.pos || 0 }}%, {{ data.button_background_gradient.color2 }} {{ data.button_background_gradient.pos2 || 100 }}%);
						<# } #>
					<# } else { #>
						background-color: {{ data.button_background_color }};
					<# } #>
				}

				#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-custom:hover{
					color: {{ data.button_color_hover }};
					background-color: {{ data.button_background_color_hover }};
					<# if(data.appearance == "outline"){ #>
						border-color: {{ data.button_background_color_hover }};
					<# } else if(data.button_appearance == "gradient"){ #>
						<# if(typeof data.button_background_gradient_hover.type !== "undefined" && data.button_background_gradient_hover.type == "radial"){ #>
							background-image: radial-gradient(at {{ data.button_background_gradient_hover.radialPos || "center center"}}, {{ data.button_background_gradient_hover.color }} {{ data.button_background_gradient_hover.pos || 0 }}%, {{ data.button_background_gradient_hover.color2 }} {{ data.button_background_gradient_hover.pos2 || 100 }}%);
						<# } else { #>
							background-image: linear-gradient({{ data.button_background_gradient_hover.deg || 0}}deg, {{ data.button_background_gradient_hover.color }} {{ data.button_background_gradient_hover.pos || 0 }}%, {{ data.button_background_gradient_hover.color2 }} {{ data.button_background_gradient_hover.pos2 || 100 }}%);
						<# } #>
					<# } #>
				}
			<# } #>

			@media (min-width: 768px) and (max-width: 991px) {
				#jwpf-addon-{{ data.id }} .jwpf-addon-image-content .jwpf-content-holder{
					padding: {{ padding_sm }};
				}
				#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-{{ data.button_type }}{
					<# if(typeof data.button_margin_top !== "undefined" && typeof data.button_margin_top.sm !== "undefined"){ #>
						margin-top: {{ data.button_margin_top.sm }}px;
					<# } #>
				}
			}
			@media (max-width: 767px) {
				#jwpf-addon-{{ data.id }} .jwpf-addon-image-content .jwpf-content-holder{
					padding: {{ padding_xs }};
				}
				#jwpf-addon-{{ data.id }} #btn-{{ data.id }}.jwpf-btn-{{ data.button_type }}{
					<# if(typeof data.button_margin_top !== "undefined" && typeof data.button_margin_top.xs !== "undefined"){ #>
						margin-top: {{ data.button_margin_top.xs }}px;
					<# } #>
				}
			}
		</style>
		<div class="jwpf-addon jwpf-addon-image-content aligment-{{ data.image_alignment }} clearfix {{ data.class }}">
			<div class="jwpf-image-holder"></div>
			<div class="jwpf-container">
				<div class="jwpf-row">
					<# if(data.image_alignment == "left") { #>
						<div class="jwpf-col-sm-6"></div>
					<# } #>
					<div class="jwpf-col-sm-6 {{ content_class }}">
						<div class="jwpf-content-holder">
                            <# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-image-content-title jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
                            <# if(data.text){ #><p id="addon-text-{{data.id}}" class="jwpf-image-content-text jw-editable-content" data-id={{data.id}} data-fieldName="text">{{{ data.text }}}</p><# } #>
						    <# if(button_text){ #>
                                <a href=\'{{ data.button_url }}\' target="{{ data.button_target }}" id="btn-{{ data.id }}" class="jwpf-btn {{ button_classes }}">{{{ button_text }}}</a>
                            <# } #>
						</div>
					</div>
				</div>
			</div>
		</div>
		';

		return $output;
	}

}