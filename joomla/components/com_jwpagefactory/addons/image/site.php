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

class JwpagefactoryAddonImage extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$title_position = (isset($settings->title_position) && $settings->title_position) ? $settings->title_position : 'top';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		//Options
		$image = (isset($settings->image) && $settings->image) ? $settings->image : '';
		$alt_text = (isset($settings->alt_text) && $settings->alt_text) ? $settings->alt_text : '';
		$position = (isset($settings->position) && $settings->position) ? $settings->position : '';
		$link = (isset($settings->link) && $settings->link) ? $settings->link : '';
		$target = (isset($settings->target) && $settings->target) ? 'target="' . $settings->target . '" rel="noopener noreferrer"' : '';
		$open_lightbox = (isset($settings->open_lightbox) && $settings->open_lightbox) ? $settings->open_lightbox : 0;
		$image_overlay = (isset($settings->overlay_color) && $settings->overlay_color) ? 1 : 0;

		//Lazyload image
		$dimension = $this->get_image_dimension($image);
		$dimension = implode(' ', $dimension);

		$placeholder = $image == '' ? false : $this->get_image_placeholder($image);
		if (strpos($image, "http://") !== false || strpos($image, "https://") !== false) {
			$image = $image;
		} else {
			$image = JURI::base(true) . '/' . $image;
		}

		$output = '';

		if ($image) {
			$output .= '<div class="jwpf-addon jwpf-addon-single-image ' . $position . ' ' . $class . '">';
			$output .= ($title && $title_position != 'bottom') ? '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';
			$output .= '<div class="jwpf-addon-content">';
			$output .= '<div class="jwpf-addon-single-image-container">';

			if (empty($alt_text)) {
				if (!empty($title)) {
					$alt_text = $title;
				} else {
					$alt_text = basename($image);
				}
			}

			if ($image_overlay && $open_lightbox) {
				$output .= '<div class="jwpf-addon-image-overlay">';
				$output .= '</div>';
			}

			if ($open_lightbox) {
				$output .= '<a class="jwpf-magnific-popup jwpf-addon-image-overlay-icon" data-popup_type="image" data-mainclass="mfp-no-margins mfp-with-zoom" href="' . $image . '">+</a>';
			}

			if (!$open_lightbox) {
				$output .= ($link) ? '<a ' . $target . ' href="' . $link . '">' : '';
			}

			$output .= '<img class="jwpf-img-responsive' . ($placeholder ? ' jwpf-element-lazy' : '') . '" src="' . ($placeholder ? $placeholder : $image) . '" ' . ($placeholder ? 'data-large="' . $image . '"' : '') . ' alt="' . $alt_text . '" title="' . $title . '" ' . ($dimension ? $dimension : '') . ' loading="lazy">';

			if (!$open_lightbox) {
				$output .= ($link) ? '</a>' : '';
			}

			$output .= '</div>';
			$output .= ($title && $title_position == 'bottom') ? '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	public function scripts()
	{
		return array(JURI::base(true) . '/components/com_jwpagefactory/assets/js/jquery.magnific-popup.min.js');
	}

	public function stylesheets()
	{
		return array(JURI::base(true) . '/components/com_jwpagefactory/assets/css/magnific-popup.css');
	}

	public function css()
	{
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$settings = $this->addon->settings;
		$open_lightbox = (isset($settings->open_lightbox) && $settings->open_lightbox) ? $settings->open_lightbox : 0;
		$style = (isset($settings->overlay_color) && $settings->overlay_color) ? 'background-color: ' . $settings->overlay_color . ';' : '';
		$title_padding = (isset($settings->title_padding) && trim($settings->title_padding)) ? $settings->title_padding : '';
		$title_padding_sm = (isset($settings->title_padding_sm) && trim($settings->title_padding_sm)) ? $settings->title_padding_sm : '';
		$title_padding_xs = (isset($settings->title_padding_xs) && trim($settings->title_padding_xs)) ? $settings->title_padding_xs : '';

		$style_img = '';
		$style_img_sm = '';
		$style_img_xs = '';
		$style_img = (isset($settings->border_radius) && $settings->border_radius) ? 'border-radius: ' . $settings->border_radius . 'px;' : '';
		$style_img .= (isset($settings->image_width) && $settings->image_width) ? 'width:' . $settings->image_width . 'px;' : '';
		$style_img .= (isset($settings->image_width) && $settings->image_width) ? 'max-width:' . $settings->image_width . 'px;' : '';
		$style_img .= (isset($settings->image_height) && $settings->image_height) ? 'height:' . $settings->image_height . 'px;' : '';

		$style_img_sm .= (isset($settings->image_width_sm) && $settings->image_width_sm) ? 'max-width:' . $settings->image_width_sm . 'px;' : '';
		$style_img_sm .= (isset($settings->image_height_sm) && $settings->image_height_sm) ? 'height:' . $settings->image_height_sm . 'px;' : '';

		$style_img_xs .= (isset($settings->image_width_xs) && $settings->image_width_xs) ? 'max-width:' . $settings->image_width_xs . 'px;' : '';
		$style_img_xs .= (isset($settings->image_height_xs) && $settings->image_height_xs) ? 'height:' . $settings->image_height_xs . 'px;' : '';

		$css = '';
		if ($open_lightbox && $style) {
			$css .= $addon_id . ' .jwpf-addon-image-overlay{';
			$css .= $style;
			$css .= $style_img;
			$css .= '}';
		}

		$css .= $addon_id . ' img{' . $style_img . '}';
		if ($title_padding) {
			$css .= $addon_id . ' .jwpf-addon-title{padding: ' . $title_padding . '}';
		}

		$css .= '@media (min-width: 768px) and (max-width: 991px) {';
		if ($title_padding_sm) {
			$css .= $addon_id . ' .jwpf-addon-title{padding: ' . $title_padding_sm . '}';
		}
		$css .= $addon_id . ' img{' . $style_img_sm . '}';
		$css .= '}';
		$css .= '@media (max-width: 767px) {';
		if ($title_padding_xs) {
			$css .= $addon_id . ' .jwpf-addon-title{padding: ' . $title_padding_xs . '}';
		}
		$css .= $addon_id . ' img{' . $style_img_xs . '}';
		$css .= '}';

		return $css;

	}

	public static function getTemplate()
	{
		$output = '
		<#
			var image_overlay = 0;
			if(!_.isEmpty(data.overlay_color)){
				image_overlay = 1;
			}
			var open_lightbox = parseInt(data.open_lightbox);
			var title_font_style = data.title_fontstyle || "";

			var alt_text = data.alt_text;

			if(_.isEmpty(alt_text)){
				if(!_.isEmpty(data.title)){
					alt_text = data.title;
				}
			}
		#>
		<style type="text/css">
			<# if(open_lightbox && data.overlay_color){ #>
				#jwpf-addon-{{ data.id }} .jwpf-addon-image-overlay{
					background-color: {{ data.overlay_color }};
					border-radius: {{ data.border_radius }}px;
				}
			<# } #>

			#jwpf-addon-{{ data.id }} img{
				border-radius: {{ data.border_radius }}px;
				<# if(_.isObject(data.image_height)) { #>
					height: {{data.image_height.md}}px;
				<# } else { #>
					height: {{data.image_height}}px;
				<# } #>
				<# if(_.isObject(data.image_width)) { #>
					width: {{data.image_width.md}}px;
				<# } else { #>
					width: {{data.image_width}}px;
				<# } #>
				<# if(_.isObject(data.image_width)) { #>
					max-width: {{data.image_width.md}}px;
				<# } else { #>
					max-width: {{data.image_width}}px;
				<# } #>
			}
			#jwpf-addon-{{ data.id }} .jwpf-addon-title{
				<# if(_.isObject(data.title_padding)) { #>
					padding:{{data.title_padding.md}};
				<# } else { #>
					padding:{{data.title_padding}};
				<# } #>
			}
			@media (min-width: 768px) and (max-width: 991px) {
				<# if(_.isObject(data.title_padding)) { #>
					#jwpf-addon-{{ data.id }} .jwpf-addon-title{
						padding: {{data.title_padding.sm}};
					}
				<# } #>
				#jwpf-addon-{{ data.id }} img{
					<# if(_.isObject(data.image_height)) { #>
						height: {{data.image_height.sm}}px;
					<# } #>
					<# if(_.isObject(data.image_width)) { #>
						width: {{data.image_width.sm}}px;
					<# } #>
					<# if(_.isObject(data.image_width)) { #>
						max-width: {{data.image_width.sm}}px;
					<# } #>
				}
			}
			@media (max-width: 767px) {
				<# if(_.isObject(data.title_padding)) { #>
					#jwpf-addon-{{ data.id }} .jwpf-addon-title{
						padding: {{data.title_padding.xs}};
					}
				<# } #>
				#jwpf-addon-{{ data.id }} img{
					<# if(_.isObject(data.image_height)) { #>
						height: {{data.image_height.xs}}px;
					<# } #>
					<# if(_.isObject(data.image_width)) { #>
						width: {{data.image_width.xs}}px;
					<# } #>
					<# if(_.isObject(data.image_width)) { #>
						max-width: {{data.image_width.xs}}px;
					<# } #>
				}
			}
		</style>
		<# if(data.image){ #>
			<div class="jwpf-addon jwpf-addon-single-image {{ data.position }} {{ data.class }}">
				<# if( !_.isEmpty( data.title ) && data.title_position != "bottom" ){ #><{{ data.heading_selector }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
				<div class="jwpf-addon-content">
					<div class="jwpf-addon-single-image-container">
						<# if(image_overlay && open_lightbox) { #>
							<div class="jwpf-addon-image-overlay"></div>
						<# } #>
						<# if(open_lightbox) { #>
							<a class="jwpf-magnific-popup jwpf-addon-image-overlay-icon" data-popup_type="image" data-mainclass="mfp-no-margins mfp-with-zoom" href=\'{{ data.image }}\'>+</a>
						<# } #>
			
						<# if(!open_lightbox) { #>
							<a target="{{ data.target }}" href=\'{{ data.link }}\'>
						<# } #>

						<# if(data.image.indexOf("http://") == -1 && data.image.indexOf("https://") == -1){ #>
							<img class="jwpf-img-responsive" src=\'{{ pagefactory_base + data.image }}\' alt="{{ alt_text }}" title="{{ data.title }}">
						<# } else { #>
							<img class="jwpf-img-responsive" src=\'{{ data.image }}\' alt="{{ alt_text }}" title="{{ data.title }}">
						<# } #>

						<# if(!open_lightbox) { #>
							</a>
						<# } #>

					</div>
					<# if( !_.isEmpty( data.title ) && data.title_position == "bottom" ){ #><{{ data.heading_selector }} class="jwpf-addon-title">{{ data.title }}</{{ data.heading_selector }}><# } #>
				</div>
			</div>
		<# } #>
		';

		return $output;
	}

}
