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

class JwpagefactoryAddonFeature extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		//Options
		$title_url = (isset($settings->title_url) && $settings->title_url) ? $settings->title_url : '';
		$link_open_new_window = (isset($settings->link_open_new_window) && $settings->link_open_new_window) ? $settings->link_open_new_window : '';
		$url_appear = (isset($settings->url_appear) && $settings->url_appear) ? $settings->url_appear : 'title';
		$title_position = (isset($settings->title_position) && $settings->title_position) ? $settings->title_position : 'before';
		$feature_type = (isset($settings->feature_type) && $settings->feature_type) ? $settings->feature_type : 'icon';
		$feature_image = (isset($settings->feature_image) && $settings->feature_image) ? $settings->feature_image : '';
		$icon_name = (isset($settings->icon_name) && $settings->icon_name) ? $settings->icon_name : '';
		$text = (isset($settings->text) && $settings->text) ? $settings->text : '';
		$text_alignment = (isset($settings->alignment) && $settings->alignment) ? $settings->alignment : '';

		//Lazyload image size
		$dimension = $this->get_image_dimension($feature_image);
		$dimension = implode(' ', $dimension);

		$feature_image_link = '';
		if (strpos($feature_image, "http://") !== false || strpos($feature_image, "https://") !== false) {
			$feature_image_link = $feature_image;
		} else {
			$feature_image_link = JURI::base(true) . '/' . $feature_image;
		}

		//Lazyload image
		$placeholder = $feature_image_link == '' ? false : $this->get_image_placeholder($feature_image_link);

		//Image or icon position
		$icon_image_position = '';
		if ($title_position == 'before') {
			$icon_image_position = 'after';
		} else if ($title_position == 'after') {
			$icon_image_position = 'before';
		} else {
			$icon_image_position = $title_position;
		}

		//Reset Alignment for left and right style
		$alignment = '';
		if (($icon_image_position == 'left') || ($icon_image_position == 'right')) {
			$alignment = 'jwpf-text-' . $icon_image_position;
		}

		//Icon or Image
		$media = '';
		if ($feature_type == 'icon') {
			if ($icon_name) {
				$media .= '<div class="jwpf-icon">';
				if (($title_url && $url_appear == 'icon') || ($title_url && $url_appear == 'both')) $media .= '<a href="' . $title_url . '"' . ($link_open_new_window ? ' rel="noopener noreferrer" target="_blank"' : '') . '>';
				$media .= '<span class="jwpf-icon-container" aria-label="' . strip_tags($title) . '">';

				$icon_arr = array_filter(explode(' ', $icon_name));
				if (count($icon_arr) === 1) {
					$icon_name = 'fa ' . $icon_name;
				}

				$media .= '<i class="' . $icon_name . '" aria-hidden="true"></i>';
				$media .= '</span>';
				if (($title_url && $url_appear == 'icon') || ($title_url && $url_appear == 'both')) $media .= '</a>';
				$media .= '</div>';
			}
		} else {
			if ($feature_image) {
				$media .= '<span class="jwpf-img-container">';
				if (($title_url && $url_appear == 'icon') || ($title_url && $url_appear == 'both')) $media .= '<a href="' . $title_url . '"' . ($link_open_new_window ? ' rel="noopener noreferrer" target="_blank"' : '') . '>';
				$media .= '<img class="jwpf-img-responsive' . ($placeholder ? ' jwpf-element-lazy' : '') . '" src="' . ($placeholder ? $placeholder : $feature_image_link) . '" alt="' . strip_tags($title) . '" ' . ($placeholder ? 'data-large="' . $feature_image_link . '"' : '') . ' ' . ($dimension ? $dimension : '') . ' loading="lazy">';
				if (($title_url && $url_appear == 'icon') || ($title_url && $url_appear == 'both')) $media .= '</a>';
				$media .= '</span>';
			}
		}
		//Image and icon
		$image_icon = '';
		if ($feature_type == 'both' && $icon_name) {
			$image_icon .= '<div class="jwpf-icon">';
			if (($title_url && $url_appear == 'icon') || ($title_url && $url_appear == 'both')) $image_icon .= '<a href="' . $title_url . '"' . ($link_open_new_window ? ' rel="noopener noreferrer" target="_blank"' : '') . '>';
			$image_icon .= '<span class="jwpf-icon-container" aria-label="' . strip_tags($title) . '">';

			$icon_arr = array_filter(explode(' ', $icon_name));
			if (count($icon_arr) === 1) {
				$icon_name = 'fa ' . $icon_name;
			}

			$image_icon .= '<i class="' . $icon_name . '" aria-hidden="true"></i>';
			$image_icon .= '</span>';
			if (($title_url && $url_appear == 'icon') || ($title_url && $url_appear == 'both')) $image_icon .= '</a>';
			$image_icon .= '</div>';
		}

		//Title
		$feature_title = '';
		if ($title) {
			$heading_class = '';
			if (($icon_image_position == 'left') || ($icon_image_position == 'right')) {
				$heading_class = ' jwpf-media-heading';
			}

			$feature_title .= '<' . $heading_selector . ' class="jwpf-addon-title jwpf-feature-box-title' . $heading_class . '">';
			if (($title_url && $url_appear == 'title') || ($title_url && $url_appear == 'both')) $feature_title .= '<a href="' . $title_url . '"' . ($link_open_new_window ? ' rel="noopener noreferrer" target="_blank"' : '') . '>';
			$feature_title .= $title;
			if (($title_url && $url_appear == 'title') || ($title_url && $url_appear == 'both')) $feature_title .= '</a>';
			$feature_title .= '</' . $heading_selector . '>';
		}

		//Feature Text
		$feature_text = '<div class="jwpf-addon-text">';
		$feature_text .= $text;
		$feature_text .= '</div>';

		//Output
		$output = '<div class="jwpf-addon jwpf-addon-feature ' . $alignment . ' ' . $class . '">';
		$output .= '<div class="jwpf-addon-content ' . $text_alignment . '">';

		if ($icon_image_position == 'before') {
			$output .= ($media) ? $media : '';
			$output .= '<div class="jwpf-media-content">';
			$output .= ($title) ? $feature_title : '';
			$output .= $feature_text;
			$output .= '</div>';
		} else if ($icon_image_position == 'after') {
			$output .= ($title) ? $feature_title : '';
			$output .= ($media) ? $media : '';
			$output .= '<div class="jwpf-media-content">';
			$output .= $feature_text;
			$output .= '</div>';
		} else {
			if ($media) {
				$output .= '<div class="jwpf-media">';
				$output .= '<div class="pull-' . $icon_image_position . '">';
				$output .= $media;
				$output .= '</div>';
				$output .= '<div class="jwpf-media-body">';
				$output .= '<div class="jwpf-media-content">';
				$output .= $image_icon;
				$output .= ($title) ? $feature_title : '';
				$output .= $feature_text;
				$output .= '</div>';//.jwpf-media-content
				$output .= '</div>';
				$output .= '</div>';
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
		//icon css
		$icon_color = (isset($settings->icon_color) && $settings->icon_color) ? $settings->icon_color : '';
		$icon_size = (isset($settings->icon_size) && $settings->icon_size) ? $settings->icon_size : '';
		$icon_size_sm = (isset($settings->icon_size_sm) && $settings->icon_size_sm) ? $settings->icon_size_sm : '';
		$icon_size_xs = (isset($settings->icon_size_xs) && $settings->icon_size_xs) ? $settings->icon_size_xs : '';
		$icon_border_color = (isset($settings->icon_border_color) && $settings->icon_border_color) ? $settings->icon_border_color : '';
		$icon_border_width = (isset($settings->icon_border_width) && $settings->icon_border_width) ? $settings->icon_border_width : '';
		$icon_border_width_sm = (isset($settings->icon_border_width_sm) && $settings->icon_border_width_sm) ? $settings->icon_border_width_sm : '';
		$icon_border_width_xs = (isset($settings->icon_border_width_xs) && $settings->icon_border_width_xs) ? $settings->icon_border_width_xs : '';
		$icon_border_radius = (isset($settings->icon_border_radius) && $settings->icon_border_radius) ? $settings->icon_border_radius : '';
		$icon_border_radius_sm = (isset($settings->icon_border_radius_sm) && $settings->icon_border_radius_sm) ? $settings->icon_border_radius_sm : '';
		$icon_border_radius_xs = (isset($settings->icon_border_radius_xs) && $settings->icon_border_radius_xs) ? $settings->icon_border_radius_xs : '';
		$icon_background = (isset($settings->icon_background) && $settings->icon_background) ? $settings->icon_background : '';
		$icon_margin_top = (isset($settings->icon_margin_top) && $settings->icon_margin_top) ? $settings->icon_margin_top : '';
		$icon_margin_top_sm = (isset($settings->icon_margin_top_sm) && $settings->icon_margin_top_sm) ? $settings->icon_margin_top_sm : '';
		$icon_margin_top_xs = (isset($settings->icon_margin_top_xs) && $settings->icon_margin_top_xs) ? $settings->icon_margin_top_xs : '';
		$icon_margin_bottom = (isset($settings->icon_margin_bottom) && $settings->icon_margin_bottom) ? $settings->icon_margin_bottom : '';
		$icon_margin_bottom_sm = (isset($settings->icon_margin_bottom_sm) && $settings->icon_margin_bottom_sm) ? $settings->icon_margin_bottom_sm : '';
		$icon_margin_bottom_xs = (isset($settings->icon_margin_bottom_xs) && $settings->icon_margin_bottom_xs) ? $settings->icon_margin_bottom_xs : '';
		$icon_padding = (isset($settings->icon_padding) && $settings->icon_padding) ? $settings->icon_padding : '';
		$feature_type = (isset($settings->feature_type) && $settings->feature_type) ? $settings->feature_type : 'icon';
		$feature_image = (isset($settings->feature_image) && $settings->feature_image) ? $settings->feature_image : '';
		$icon_name = (isset($settings->icon_name) && $settings->icon_name) ? $settings->icon_name : '';
		$title_position = (isset($settings->title_position) && $settings->title_position) ? $settings->title_position : '';

		//Css start
		$css = '';

		$text_style = '';
		$text_style_sm = '';
		$text_style_xs = '';

		$text_style .= (isset($settings->text_fontsize) && $settings->text_fontsize) ? "font-size: " . $settings->text_fontsize . "px;" : "";
		$text_style .= (isset($settings->text_fontweight) && $settings->text_fontweight) ? "font-weight: " . $settings->text_fontweight . ";" : "";
		$text_style_sm .= (isset($settings->text_fontsize_sm) && $settings->text_fontsize_sm) ? "font-size: " . $settings->text_fontsize_sm . "px;" : "";
		$text_style_xs .= (isset($settings->text_fontsize_xs) && $settings->text_fontsize_xs) ? "font-size: " . $settings->text_fontsize_xs . "px;" : "";

		$content_style = (isset($settings->text_background) && $settings->text_background) ? "background-color: " . $settings->text_background . ";" : "";
		$content_style .= (isset($settings->text_padding) && trim($settings->text_padding)) ? "padding: " . $settings->text_padding . ";" : "";
		$content_style_sm = (isset($settings->text_padding_sm) && trim($settings->text_padding_sm)) ? "padding: " . $settings->text_padding_sm . ";" : "";
		$content_style_xs = (isset($settings->text_padding_xs) && trim($settings->text_padding_xs)) ? "padding: " . $settings->text_padding_xs . ";" : "";

		$text_style .= (isset($settings->text_lineheight) && $settings->text_lineheight) ? "line-height: " . $settings->text_lineheight . "px;" : "";
		$text_style_sm .= (isset($settings->text_lineheight_sm) && $settings->text_lineheight_sm) ? "line-height: " . $settings->text_lineheight_sm . "px;" : "";
		$text_style_xs .= (isset($settings->text_lineheight_xs) && $settings->text_lineheight_xs) ? "line-height: " . $settings->text_lineheight_xs . "px;" : "";

		$image_size = (isset($settings->feature_image_width) && $settings->feature_image_width) ? "width: " . $settings->feature_image_width . "%;" : "";
		$image_size_sm = (isset($settings->feature_image_width_sm) && $settings->feature_image_width_sm) ? "width: " . $settings->feature_image_width_sm . "%;" : "";
		$image_size_xs = (isset($settings->feature_image_width_xs) && $settings->feature_image_width_xs) ? "width: " . $settings->feature_image_width_xs . "%;" : "";

		$image_margin = (isset($settings->feature_image_margin) && trim($settings->feature_image_margin)) ? "margin: " . $settings->feature_image_margin . ";" : "";
		$image_margin_sm = (isset($settings->feature_image_margin_sm) && trim($settings->feature_image_margin_sm)) ? "margin: " . $settings->feature_image_margin_sm . ";" : "";
		$image_margin_xs = (isset($settings->feature_image_margin_xs) && trim($settings->feature_image_margin_xs)) ? "margin: " . $settings->feature_image_margin_xs . ";" : "";

		if ($text_style) {
			$css .= $addon_id . ' .jwpf-addon-text {';
			$css .= $text_style;
			$css .= '}';
		}
		if (!empty($content_style)) {
			$css .= $addon_id . ' .jwpf-media-content {';
			$css .= $content_style;
			$css .= '}';
		}

		if ($text_style_sm || $content_style_sm) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			$css .= $addon_id . ' .jwpf-addon-text {';
			$css .= $text_style_sm;
			$css .= '}';
			if (!empty($content_style_sm)) {
				$css .= $addon_id . ' .jwpf-media-content {';
				$css .= $content_style_sm;
				$css .= '}';
			}
			$css .= '}';
		}

		if ($text_style_xs || $content_style_xs) {
			$css .= '@media (max-width: 767px) {';
			$css .= $addon_id . ' .jwpf-addon-text {';
			$css .= $text_style_xs;
			$css .= '}';
			if (!empty($content_style_xs)) {
				$css .= $addon_id . ' .jwpf-media-content {';
				$css .= $content_style_xs;
				$css .= '}';
			}
			$css .= '}';
		}

		if ($feature_type == 'icon' || $feature_type == 'both') {
			if ($icon_name) {
				$style = '';
				// Icon Box Shadow
				$icon_boxshadow = (isset($settings->icon_boxshadow) && $settings->icon_boxshadow) ? $settings->icon_boxshadow : '';
				if (is_object($icon_boxshadow)) {
					$ho = (isset($icon_boxshadow->ho) && $icon_boxshadow->ho != '') ? $icon_boxshadow->ho . 'px' : '0px';
					$vo = (isset($icon_boxshadow->vo) && $icon_boxshadow->vo != '') ? $icon_boxshadow->vo . 'px' : '0px';
					$blur = (isset($icon_boxshadow->blur) && $icon_boxshadow->blur != '') ? $icon_boxshadow->blur . 'px' : '0px';
					$spread = (isset($icon_boxshadow->spread) && $icon_boxshadow->spread != '') ? $icon_boxshadow->spread . 'px' : '0px';
					$color = (isset($icon_boxshadow->color) && $icon_boxshadow->color != '') ? $icon_boxshadow->color : '#fff';
					$style .= "box-shadow: ${ho} ${vo} ${blur} ${spread} ${color};";
				} else {
					$style .= "box-shadow: " . $icon_boxshadow . ";";
				}
				$style .= 'display:inline-block;text-align:center;';
				$style_sm = '';
				$style_xs = '';

				$icon_margin_tp = '';
				$icon_margin_tp_sm = '';
				$icon_margin_tp_xs = '';

				$icon_margin_tp .= ($icon_margin_top) ? 'margin-top:' . (int)$icon_margin_top . 'px;' : '';
				$icon_margin_tp_sm .= ($icon_margin_top_sm) ? 'margin-top:' . (int)$icon_margin_top_sm . 'px;' : '';
				$icon_margin_tp_xs .= ($icon_margin_top_xs) ? 'margin-top:' . (int)$icon_margin_top_xs . 'px;' : '';

				$icon_margin_btm = '';
				$icon_margin_btm_sm = '';
				$icon_margin_btm_xs = '';

				$icon_margin_btm .= ($icon_margin_bottom) ? 'margin-bottom:' . (int)$icon_margin_bottom . 'px;' : '';
				$icon_margin_btm_sm .= ($icon_margin_bottom_sm) ? 'margin-bottom:' . (int)$icon_margin_bottom_sm . 'px;' : '';
				$icon_margin_btm_xs .= ($icon_margin_bottom_xs) ? 'margin-bottom:' . (int)$icon_margin_bottom_xs . 'px;' : '';

				$icon_padding_md = '';
				$icon_paddings_md = explode(' ', $icon_padding);
				foreach ($icon_paddings_md as $icon_padding_md_item) {
					$icon_padding_md_item = trim($icon_padding_md_item);
					if (empty($icon_padding_md_item)) {
						$icon_padding_md .= ' 0';
					} else {
						$icon_padding_md .= ' ' . $icon_padding_md_item;
					}

				}
				$style .= ($icon_padding_md) ? 'padding:' . $icon_padding_md . ';' : '';

				$icon_padding_sm = '';

				if (trim($icon_padding_sm) != "") {
					$icon_paddings_sm = explode(' ', $icon_padding_sm);
					foreach ($icon_paddings_sm as $icon_padding_sm_item) {
						$icon_padding_sm_item = trim($icon_padding_sm_item);
						if (empty($icon_padding_sm_item)) {
							$icon_padding_sm .= ' 0';
						} else {
							$icon_padding_sm .= ' ' . $icon_padding_sm_item;
						}

					}
				}

				$style_sm .= ($icon_padding_sm) ? 'padding:' . $icon_padding_sm . ';' : '';

				$icon_padding_xs = '';

				if (trim($icon_padding_xs) != "") {
					$icon_paddings_xs = explode(' ', $icon_padding_xs);
					foreach ($icon_paddings_xs as $icon_padding_xs_item) {
						$icon_padding_xs_item = trim($icon_padding_xs_item);
						if (empty($icon_padding_xs_item)) {
							$icon_padding_xs .= ' 0';
						} else {
							$icon_padding_xs .= ' ' . $icon_padding_xs_item;
						}

					}
				}

				$style_xs .= ($icon_padding_xs) ? 'padding:' . $icon_padding_xs . ';' : '';

				$style .= ($icon_color) ? 'color:' . $icon_color . ';' : '';
				$style .= ($icon_background) ? 'background-color:' . $icon_background . ';' : '';
				$style .= ($icon_border_color) ? 'border-style:solid;border-color:' . $icon_border_color . ';' : '';

				$style .= ($icon_border_width) ? 'border-width:' . (int)$icon_border_width . 'px;' : 'border-width:0px;';
				$style_sm .= ($icon_border_width_sm) ? 'border-width:' . (int)$icon_border_width_sm . 'px;' : '';
				$style_xs .= ($icon_border_width_xs) ? 'border-width:' . (int)$icon_border_width_xs . 'px;' : '';

				$style .= ($icon_border_radius) ? 'border-radius:' . (int)$icon_border_radius . 'px;' : '';
				$style_sm .= ($icon_border_radius_sm) ? 'border-radius:' . (int)$icon_border_radius_sm . 'px;' : '';
				$style_xs .= ($icon_border_radius_xs) ? 'border-radius:' . (int)$icon_border_radius_xs . 'px;' : '';

				$font_size = (isset($icon_size) && $icon_size) ? 'font-size:' . (int)$icon_size . 'px;width:' . (int)$icon_size . 'px;height:' . (int)$icon_size . 'px;line-height:' . (int)$icon_size . 'px;' : '';
				$font_size_sm = (isset($icon_size_sm) && $icon_size_sm) ? 'font-size:' . (int)$icon_size_sm . 'px;width:' . (int)$icon_size_sm . 'px;height:' . (int)$icon_size_sm . 'px;line-height:' . (int)$icon_size_sm . 'px;' : '';
				$font_size_xs = (isset($icon_size_xs) && $icon_size_xs) ? 'font-size:' . (int)$icon_size_xs . 'px;width:' . (int)$icon_size_xs . 'px;height:' . (int)$icon_size_xs . 'px;line-height:' . (int)$icon_size_xs . 'px;' : '';


				if ($icon_margin_tp || $icon_margin_btm) {
					$css .= $addon_id . ' .jwpf-icon {';
					$css .= $icon_margin_tp;
					$css .= $icon_margin_btm;
					$css .= '}';
				}

				if ($style) {
					$css .= $addon_id . ' .jwpf-icon .jwpf-icon-container {';
					$css .= $style;
					$css .= '}';
				}

				if ($font_size) {
					$css .= $addon_id . ' .jwpf-icon .jwpf-icon-container > i {';
					$css .= $font_size;
					$css .= '}';
				}
				if (!empty($style_sm) || !empty($font_size_sm)) {
					$css .= '@media (min-width: 768px) and (max-width: 991px) {';
					if ($icon_margin_btm_sm || $icon_margin_tp_sm) {
						$css .= $addon_id . ' .jwpf-icon {';
						$css .= $icon_margin_tp_sm;
						$css .= $icon_margin_btm_sm;
						$css .= '}';
					}
					if ($style_sm) {
						$css .= $addon_id . ' .jwpf-icon .jwpf-icon-container {';
						$css .= $style_sm;
						$css .= '}';
					}

					if ($font_size_sm) {
						$css .= $addon_id . ' .jwpf-icon .jwpf-icon-container > i {';
						$css .= $font_size_sm;
						$css .= '}';
					}
					$css .= '}';
				}

				if (!empty($style_xs) || !empty($font_size_xs)) {
					$css .= '@media (max-width: 767px) {';
					if ($icon_margin_btm_xs || $icon_margin_tp_xs) {
						$css .= $addon_id . ' .jwpf-icon {';
						$css .= $icon_margin_tp_xs;
						$css .= $icon_margin_btm_xs;
						$css .= '}';
					}
					if ($style_xs) {
						$css .= $addon_id . ' .jwpf-icon .jwpf-icon-container {';
						$css .= $style_xs;
						$css .= '}';
					}

					if ($font_size_xs) {
						$css .= $addon_id . ' .jwpf-icon .jwpf-icon-container > i {';
						$css .= $font_size_xs;
						$css .= '}';
					}
					$css .= '}';
				}
			}
		}
		if ($feature_image && ($feature_type == 'both' || $feature_type == 'image')) {
			$img_style = 'display:block;';

			if ($img_style) {
				$css .= $addon_id . ' .jwpf-img-container {';
				$css .= $img_style;
				$css .= '}';
			}
			if (!empty($image_size)) {
				$css .= $addon_id . ' .jwpf-media .pull-left, ' . $addon_id . ' .jwpf-media .pull-right {';
				$css .= $image_size;
				$css .= '}';
			}
			if (isset($settings->feature_image_width) && $settings->feature_image_width == '100') {
				$css .= $addon_id . ' .jwpf-media .jwpf-media-body {';
				$css .= 'width: 100%;';
				$css .= '}';
			}
			if (!empty($image_margin) && ($title_position == 'left' || $title_position == 'right')) {
				$css .= $addon_id . ' .jwpf-media .pull-left, ' . $addon_id . ' .jwpf-media .pull-right {';
				$css .= $image_margin;
				$css .= '}';
			}
			if (!empty($image_margin) && ($title_position == 'after' || $title_position == 'before')) {
				$css .= $addon_id . ' .jwpf-img-container {';
				$css .= $image_margin;
				$css .= '}';
			}
		}


		$css .= '@media (min-width: 768px) and (max-width: 991px) {';
		if (!empty($image_size_sm)) {
			$css .= $addon_id . ' .jwpf-media .pull-left, ' . $addon_id . ' .jwpf-media .pull-right {';
			$css .= $image_size_sm;
			$css .= '}';
		}
		if (!empty($image_margin_sm) && ($title_position == 'left' || $title_position == 'right')) {
			$css .= $addon_id . ' .jwpf-media .pull-left, ' . $addon_id . ' .jwpf-media .pull-right {';
			$css .= $image_margin_sm;
			$css .= '}';
		}
		if (!empty($image_margin_sm) && ($title_position == 'after' || $title_position == 'before')) {
			$css .= $addon_id . ' .jwpf-img-container {';
			$css .= $image_margin_sm;
			$css .= '}';
		}
		if (isset($settings->feature_image_width_sm) && $settings->feature_image_width_sm == '100') {
			$css .= $addon_id . ' .jwpf-media .jwpf-media-body {';
			$css .= 'width: 100%;';
			$css .= '}';
		} else {
			$css .= $addon_id . ' .jwpf-media .jwpf-media-body {';
			$css .= 'width: auto;';
			$css .= '}';
		}
		$css .= '}';


		$css .= '@media (max-width: 767px) {';
		if (!empty($image_size_xs)) {
			$css .= $addon_id . ' .jwpf-media .pull-left, ' . $addon_id . ' .jwpf-media .pull-right {';
			$css .= $image_size_xs;
			$css .= '}';
		}
		if (!empty($image_margin_xs) && ($title_position == 'left' || $title_position == 'right')) {
			$css .= $addon_id . ' .jwpf-media .pull-left, ' . $addon_id . ' .jwpf-media .pull-right {';
			$css .= $image_margin_xs;
			$css .= '}';
		}
		if (!empty($image_margin_xs) && ($title_position == 'after' || $title_position == 'before')) {
			$css .= $addon_id . ' .jwpf-img-container {';
			$css .= $image_margin_xs;
			$css .= '}';
		}
		if (isset($settings->feature_image_width_xs) && $settings->feature_image_width_xs == '100') {
			$css .= $addon_id . ' .jwpf-media .jwpf-media-body {';
			$css .= 'width: 100%;';
			$css .= '}';
		} else {
			$css .= $addon_id . ' .jwpf-media .jwpf-media-body {';
			$css .= 'width: auto;';
			$css .= '}';
		}
		$css .= '}';

		//Hover options
		$addon_hover = '';
		$addon_hover .= (isset($settings->addon_hover_bg) && $settings->addon_hover_bg) ? 'background:' . $settings->addon_hover_bg . ';' : '';
		$addon_hover .= (isset($settings->addon_hover_color) && $settings->addon_hover_color) ? 'color:' . $settings->addon_hover_color . ';' : '';
		$addon_hover_boxshadow = (isset($settings->addon_hover_boxshadow) && $settings->addon_hover_boxshadow) ? $settings->addon_hover_boxshadow : '';
		if (is_object($addon_hover_boxshadow)) {
			$ho = (isset($addon_hover_boxshadow->ho) && $addon_hover_boxshadow->ho != '') ? $addon_hover_boxshadow->ho . 'px' : '0px';
			$vo = (isset($addon_hover_boxshadow->vo) && $addon_hover_boxshadow->vo != '') ? $addon_hover_boxshadow->vo . 'px' : '0px';
			$blur = (isset($addon_hover_boxshadow->blur) && $addon_hover_boxshadow->blur != '') ? $addon_hover_boxshadow->blur . 'px' : '0px';
			$spread = (isset($addon_hover_boxshadow->spread) && $addon_hover_boxshadow->spread != '') ? $addon_hover_boxshadow->spread . 'px' : '0px';
			$color = (isset($addon_hover_boxshadow->color) && $addon_hover_boxshadow->color != '') ? $addon_hover_boxshadow->color : '#fff';
			$addon_hover .= "box-shadow: ${ho} ${vo} ${blur} ${spread} ${color};";
		} else {
			$addon_hover .= "box-shadow: " . $addon_hover_boxshadow . ";";
		}
		if (!empty($addon_hover)) {
			$css .= $addon_id . '{';
			$css .= 'transition:.3s;';
			$css .= '}';
			$css .= $addon_id . ':hover{';
			$css .= $addon_hover;
			$css .= '}';
		}

		if (isset($settings->title_hover_color) && $settings->title_hover_color) {
			$css .= $addon_id . ' .jwpf-feature-box-title{';
			$css .= 'transition:.3s;';
			$css .= '}';
			$css .= $addon_id . ':hover .jwpf-feature-box-title {';
			$css .= 'color:' . $settings->title_hover_color . ';';
			$css .= '}';
		}
		if ((isset($settings->icon_hover_bg) && $settings->icon_hover_bg) || (isset($settings->icon_hover_color) && $settings->icon_hover_color)) {
			$css .= $addon_id . ' .jwpf-icon .jwpf-icon-container{';
			$css .= 'transition:.3s;';
			$css .= '}';
			$css .= $addon_id . ':hover .jwpf-icon .jwpf-icon-container{';
			if (isset($settings->icon_hover_bg) && $settings->icon_hover_bg) {
				$css .= 'background:' . $settings->icon_hover_bg . ';';
			}
			if (isset($settings->icon_hover_color) && $settings->icon_hover_color) {
				$css .= 'color:' . $settings->icon_hover_color . ';';
			}
			$css .= '}';
		}

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
		<#
			var text_alignment = (data.alignment) ? data.alignment : "";
			
			let icon_arr = (typeof data.icon_name !== "undefined" && data.icon_name) ? data.icon_name.split(" ") : "";
			let icon_name = icon_arr.length === 1 ? "fa "+data.icon_name : data.icon_name;

			var icon_image_position = "";
			if(data.title_position == "before") {
				icon_image_position = "after";
			} else if(data.title_position == "after") {
				icon_image_position = "before";
			} else {
				icon_image_position = data.title_position;
			}

			var alignment = "";
			if( ( icon_image_position == "left" ) || ( icon_image_position == "right" ) ) {
				alignment = "jwpf-text-" + icon_image_position;
			}

			var media = "";
			if(data.feature_type == "icon") {
				if(data.icon_name){
					media += \'<div class="jwpf-icon">\';
						if( (data.title_url && data.url_appear == "icon") || (data.title_url && data.url_appear == "both" ) ){
							media += \'<a href="\'+data.title_url+\'">\';
						}
						media  += \'<span class="jwpf-icon-container">\';
							media  += \'<i class="\'+icon_name+\'"></i>\';
						media  += \'</span>\';
						if( (data.title_url && data.url_appear == "icon") || (data.title_url && data.url_appear == "both" ) ){
							media += \'</a>\';
						}
					media += \'</div>\';
				}
			} else {
					if(data.feature_image){
						media  += \'<span class="jwpf-img-container">\';
						if( (data.title_url && data.url_appear == "icon") || (data.title_url && data.url_appear == "both" ) ){
							media += \'<a href="\'+data.title_url+\'">\';
						}
						if(data.feature_image.indexOf("http://") != -1 || data.feature_image.indexOf("https://") != -1){
							media  += \'<img class="jwpf-img-responsive" src="\'+data.feature_image+\'" alt="\'+data.title+\'">\';
						} else {
							media  += \'<img class="jwpf-img-responsive" src="\'+pagefactory_base+data.feature_image+\'" alt="\'+data.title+\'">\';
						}
						if( (data.title_url && data.url_appear == "icon") || (data.title_url && data.url_appear == "both" ) ){
							media += \'</a>\';
						}
						media  += \'</span>\';
					}
				}
				var image_icon = "";
				if(data.feature_type == "both" && data.icon_name) {
					image_icon += \'<div class="jwpf-icon">\';
						if( (data.title_url && data.url_appear == "icon") || (data.title_url && data.url_appear == "both" ) ){
							image_icon += \'<a href="\'+data.title_url+\'">\';
						}
						image_icon  += \'<span class="jwpf-icon-container">\';
							image_icon  += \'<i class="\'+icon_name+\'"></i>\';
						image_icon  += \'</span>\';
						if( (data.title_url && data.url_appear == "icon") || (data.title_url && data.url_appear == "both" ) ){
							image_icon += \'</a>\';
						}
					image_icon += \'</div>\';
				}

			var feature_title = "";
			if(data.title) {
				var heading_class = "";
				if( ( icon_image_position == "left" ) || ( icon_image_position == "right" ) ) {
					heading_class = " jwpf-media-heading";
				}

				feature_title += \'<\'+data.heading_selector+\' class="jwpf-addon-title jwpf-feature-box-title  \'+heading_class+\'">\';
				if( (data.title_url && data.url_appear == "title") || (data.title_url && data.url_appear == "both" ) ){
					feature_title += \'<a href="\'+data.title_url+\'" class="jw-inline-editable-element" data-id="\'+data.id+\'" data-fieldName="title" contenteditable="true">\';
				}
				if(_.isEmpty(data.title_url)){
					feature_title += \'<span class="jw-inline-editable-element" data-id="\'+data.id+\'" data-fieldName="title" contenteditable="true">\';
				}
				feature_title +=data.title;
				if(_.isEmpty(data.title_url)){
					feature_title +=\'</span>\';
				}
				if( (data.title_url && data.url_appear == "title") || (data.title_url && data.url_appear == "both" ) ){
					feature_title += \'</a>\';
				}
				feature_title += \'</\'+data.heading_selector+\'>\';
			}

			var feature_text  = \'<div id="addon-text-\'+data.id+\'" class="jwpf-addon-text jw-editable-content" data-id="\'+data.id+\'" data-fieldName="text">\';
			feature_text += data.text;
			feature_text += \'</div>\';

			var title_font_style = data.title_fontstyle || "";

			var icon_padding = "";
			var icon_padding_sm = "";
			var icon_padding_xs = "";
			if(data.icon_padding){
				if(_.isObject(data.icon_padding)){
					if(_.trim(data.icon_padding.md) !== ""){
						icon_padding = _.split(data.icon_padding.md, " ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}

					if(_.trim(data.icon_padding.sm) !== ""){
						icon_padding_sm = _.split(data.icon_padding.sm, " ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}

					if(_.trim(data.icon_padding.xs) !== ""){
						icon_padding_xs = _.split(data.icon_padding.xs, " ").map(item => {
							if(_.isEmpty(item)){
								return "0";
							}
							return item;
						}).join(" ")
					}
				} else {
					if(_.trim(data.icon_padding) !== ""){
						icon_padding = _.split(data.icon_padding, " ").map(item => {
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
		<# if(data.feature_type == "icon" || data.feature_type == "both"){ #>
			<# if(data.icon_name){ #>
				#jwpf-addon-{{ data.id }} .jwpf-icon {
					<# if(_.isObject(data.icon_margin_top)){ #>
						margin-top: {{ data.icon_margin_top.md }}px;
					<# } else { #>
						margin-top: {{ data.icon_margin_top }}px;
					<# } #>
					<# if(_.isObject(data.icon_margin_bottom)){ #>
						margin-bottom: {{ data.icon_margin_bottom.md }}px;
					<# } else { #>
						margin-bottom: {{ data.icon_margin_bottom }}px;
					<# } #>
				}
				#jwpf-addon-{{ data.id }} .jwpf-icon .jwpf-icon-container{
					display:inline-block;
					text-align:center;
					padding: {{ icon_padding }};
					color: {{ data.icon_color }};
					background-color: {{ data.icon_background }};
					<# if(data.icon_border_color){ #>
						border-style:solid;
						border-color: {{ data.icon_border_color }};
					<# } #>
					<# if(_.isObject(data.icon_border_width) && !_.isEmpty(data.icon_border_width.md)){ #>
						border-width: {{ data.icon_border_width.md }}px;
					<# } else { #>
						border-width: 0px;
					<# } #>
					<# if(_.isObject(data.icon_border_radius)){ #>
						border-radius: {{ data.icon_border_radius.md }}px;
					<# } else { #>
						border-radius: {{ data.icon_border_radius }}px;
					<# }
					if(_.isObject(data.icon_boxshadow)){
						let ho = data.icon_boxshadow.ho || 0,
							vo = data.icon_boxshadow.vo || 0,
							blur = data.icon_boxshadow.blur || 0,
							spread = data.icon_boxshadow.spread || 0,
							color = data.icon_boxshadow.color || 0;
					#>
						box-shadow: {{ho}}px {{vo}}px {{blur}}px {{spread}}px {{color}};
					<# } else { #>
						box-shadow: {{data.icon_boxshadow}};
					<# } #>
				}

				#jwpf-addon-{{ data.id }} .jwpf-icon .jwpf-icon-container > i{
					<# if(_.isObject(data.icon_size)){ #>
						font-size: {{ data.icon_size.md }}px;
						width: {{ data.icon_size.md }}px;
						height: {{ data.icon_size.md }}px;
						line-height: {{ data.icon_size.md }}px;
					<# } else { #>
						font-size: {{ data.icon_size }}px;
						width: {{ data.icon_size }}px;
						height: {{ data.icon_size }}px;
						line-height: {{ data.icon_size }}px;
					<# } #>

				}
				@media (min-width: 768px) and (max-width: 991px) {
					#jwpf-addon-{{ data.id }} .jwpf-icon {
						<# if(_.isObject(data.icon_margin_top)){ #>
							margin-top: {{ data.icon_margin_top.sm }}px;
						<# } #>
						<# if(_.isObject(data.icon_margin_bottom)){ #>
							margin-bottom: {{ data.icon_margin_bottom.sm }}px;
						<# } #>
					}
					#jwpf-addon-{{ data.id }} .jwpf-icon .jwpf-icon-container{
						padding: {{ icon_padding_sm }};
						<# if(_.isObject(data.icon_border_width) && !_.isEmpty(data.icon_border_width.sm)){ #>
							border-width: {{ data.icon_border_width.sm }}px;
						<# } #>
						<# if(_.isObject(data.icon_border_radius)){ #>
							border-radius: {{ data.icon_border_radius.sm }}px;
						<# } #>
					}

					#jwpf-addon-{{ data.id }} .jwpf-icon .jwpf-icon-container > i{
						<# if(_.isObject(data.icon_size)){ #>
							font-size: {{ data.icon_size.sm }}px;
							width: {{ data.icon_size.sm }}px;
							height: {{ data.icon_size.sm }}px;
							line-height: {{ data.icon_size.sm }}px;
						<# } #>
					}
				}
				@media (max-width: 767px) {
					#jwpf-addon-{{ data.id }} .jwpf-icon {
						<# if(_.isObject(data.icon_margin_top)){ #>
							margin-top: {{ data.icon_margin_top.xs }}px;
						<# } #>
						<# if(_.isObject(data.icon_margin_bottom)){ #>
							margin-bottom: {{ data.icon_margin_bottom.xs }}px;
						<# } #>
					}
					#jwpf-addon-{{ data.id }} .jwpf-icon .jwpf-icon-container{
						padding: {{ icon_padding_xs }};
						<# if(_.isObject(data.icon_border_width) && !_.isEmpty(data.icon_border_width.xs)){ #>
							border-width: {{ data.icon_border_width.xs }}px;
						<# } #>
						<# if(_.isObject(data.icon_border_radius)){ #>
							border-radius: {{ data.icon_border_radius.xs }}px;
						<# } #>
					}

					#jwpf-addon-{{ data.id }} .jwpf-icon .jwpf-icon-container > i{
						<# if(_.isObject(data.icon_size)){ #>
							font-size: {{ data.icon_size.xs }}px;
							width: {{ data.icon_size.xs }}px;
							height: {{ data.icon_size.xs }}px;
							line-height: {{ data.icon_size.xs }}px;
						<# } #>
					}
				}
			<# } #>
			<# } if(data.feature_type == "image" || data.feature_type == "both") { #>
				#jwpf-addon-{{ data.id }} .jwpf-img-container {
					display:block;
				}
				<# if(!_.isEmpty(data.feature_image_margin) && (data.title_position == "left" || data.title_position == "right")){ #>
					#jwpf-addon-{{ data.id }} .jwpf-media .pull-left, #jwpf-addon-{{ data.id }} .jwpf-media .pull-right {
						<# if(_.isObject(data.feature_image_margin)){ #>
							margin: {{data.feature_image_margin.md}};
						<# } else { #>
							margin: {{data.feature_image_margin}};
						<# } #>
					}
				<# }
				if(_.isObject(data.feature_image_width) && data.feature_image_width.md === "100"){ #>
					#jwpf-addon-{{ data.id }} .jwpf-media .jwpf-media-body {
						width: 100%;
					}
				<# }
				if(!_.isEmpty(data.feature_image_margin) && (data.title_position == "after" || data.title_position == "before")) { #>
					#jwpf-addon-{{ data.id }} .jwpf-img-container {
						<# if(_.isObject(data.feature_image_margin)){ #>
							margin: {{data.feature_image_margin.md}};
						<# } else { #>
							margin: {{data.feature_image_margin}};
						<# } #>
					}
				<# } #>
				#jwpf-addon-{{ data.id }} .jwpf-media .pull-left, #jwpf-addon-{{ data.id }} .jwpf-media .pull-right {
					<# if(_.isObject(data.feature_image_width)){ #>
						width: {{ data.feature_image_width.md }}%;
					<# } else { #>
						width: {{ data.feature_image_width }}%;
					<# } #>
				}
				@media (min-width: 768px) and (max-width: 991px) {
					#jwpf-addon-{{ data.id }} .jwpf-media .pull-left, #jwpf-addon-{{ data.id }} .jwpf-media .pull-right {
						<# if(_.isObject(data.feature_image_width)){ #>
							width: {{ data.feature_image_width.sm }}%;
						<# } #>
					}
					<# if(!_.isEmpty(data.feature_image_margin) && (data.title_position == "left" || data.title_position == "right")){ #>
						#jwpf-addon-{{ data.id }} .jwpf-media .pull-left, #jwpf-addon-{{ data.id }} .jwpf-media .pull-right {
							<# if(_.isObject(data.feature_image_margin)){ #>
								margin: {{data.feature_image_margin.sm}};
							<# } #>
						}
					<# }
					if(_.isObject(data.feature_image_width) && (data.feature_image_width.sm === "100")){ #>
						#jwpf-addon-{{ data.id }} .jwpf-media .jwpf-media-body {
							width: 100%;
						}
					<# } else { #>
						#jwpf-addon-{{ data.id }} .jwpf-media .jwpf-media-body {
							width: auto;
						}
					<# }
					if(!_.isEmpty(data.feature_image_margin) && (data.title_position == "after" || data.title_position == "before")) { #>
						#jwpf-addon-{{ data.id }} .jwpf-img-container {
							<# if(_.isObject(data.feature_image_margin)){ #>
								margin: {{data.feature_image_margin.sm}};
							<# } #>
						}
					<# } #>
				}
				@media (max-width: 767px) {
					#jwpf-addon-{{ data.id }} .jwpf-media .pull-left, #jwpf-addon-{{ data.id }} .jwpf-media .pull-right {
						<# if(_.isObject(data.feature_image_width)){ #>
							width: {{ data.feature_image_width.xs }}%;
						<# } #>
					}
					<# if(!_.isEmpty(data.feature_image_margin) && (data.title_position == "left" || data.title_position == "right")){ #>
						#jwpf-addon-{{ data.id }} .jwpf-media .pull-left, #jwpf-addon-{{ data.id }} .jwpf-media .pull-right {
							<# if(_.isObject(data.feature_image_margin)){ #>
								margin: {{data.feature_image_margin.xs}};
							<# } #>
						}
					<# }
					if(_.isObject(data.feature_image_width) && data.feature_image_width.xs === "100"){ #>
						#jwpf-addon-{{ data.id }} .jwpf-media .jwpf-media-body {
							width: 100%;
						}
					<# } else { #>
						#jwpf-addon-{{ data.id }} .jwpf-media .jwpf-media-body {
							width: auto;
						}
					<# }
					if(!_.isEmpty(data.feature_image_margin) && (data.title_position == "after" || data.title_position == "before")) { #>
						#jwpf-addon-{{ data.id }} .jwpf-img-container {
							<# if(_.isObject(data.feature_image_margin)){ #>
								margin: {{data.feature_image_margin.xs}};
							<# } #>
						}
					<# } #>
				}
		<# } #>

		#jwpf-addon-{{ data.id }} .jwpf-addon-text {
			<# if(_.isObject(data.text_fontsize)){ #>
				font-size: {{ data.text_fontsize.md }}px;
			<# } else { #>
				font-size: {{ data.text_fontsize }}px;
			<# } #>
			font-weight: {{data.text_fontweight}};
			<# if(_.isObject(data.text_lineheight)){ #>
				line-height: {{ data.text_lineheight.md }}px;
			<# } else { #>
				line-height: {{ data.text_lineheight }}px;
			<# } #>
		}
				
		#jwpf-addon-{{ data.id }} .jwpf-media-content {
			background-color: {{data.text_background}};
			<# if(_.isObject(data.text_padding)){ #>
				padding: {{ data.text_padding.md }};
			<# } else { #>
				padding: {{ data.text_padding }};
			<# } #>
		}

		@media (min-width: 768px) and (max-width: 991px) {
			#jwpf-addon-{{ data.id }} .jwpf-addon-text {
				<# if(_.isObject(data.text_fontsize)){ #>
					font-size: {{ data.text_fontsize.sm }}px;
				<# } #>

				<# if(_.isObject(data.text_lineheight)){ #>
					line-height: {{ data.text_lineheight.sm }}px;
				<# } #>
			}
			#jwpf-addon-{{ data.id }} .jwpf-media-content {
				<# if(_.isObject(data.text_padding)){ #>
					padding: {{ data.text_padding.sm }};
				<# } #>
			}
		}

		@media (max-width: 767px) {
			#jwpf-addon-{{ data.id }} .jwpf-addon-text {
				<# if(_.isObject(data.text_fontsize)){ #>
					font-size: {{ data.text_fontsize.xs }}px;
				<# } #>

				<# if(_.isObject(data.text_lineheight)){ #>
					line-height: {{ data.text_lineheight.xs }}px;
				<# } #>
			}
			#jwpf-addon-{{ data.id }} .jwpf-media-content {
				<# if(_.isObject(data.text_padding)){ #>
					padding: {{ data.text_padding.xs }};
				<# } #>
			}
		}
		<# if(data.addon_hover_bg || data.addon_hover_boxshadow || data.addon_hover_color) { #>
			#jwpf-addon-{{ data.id }} {
				transition:.3s;
			}
			#jwpf-addon-{{ data.id }}:hover{
				background:{{data.addon_hover_bg}};
				<# if(_.isObject(data.addon_hover_boxshadow)){
					let ho = data.addon_hover_boxshadow.ho || 0,
						vo = data.addon_hover_boxshadow.vo || 0,
						blur = data.addon_hover_boxshadow.blur || 0,
						spread = data.addon_hover_boxshadow.spread || 0,
						color = data.addon_hover_boxshadow.color || 0;
				#>
					box-shadow: {{ho}}px {{vo}}px {{blur}}px {{spread}}px {{color}};
				<# } else { #>
					box-shadow: {{data.addon_hover_boxshadow}};
				<# } #>
				color: {{data.addon_hover_color}};
			}
		<# }
		if(data.title_hover_color) { #>
			#jwpf-addon-{{ data.id }} .jwpf-feature-box-title{
				transition:.3s;
			}
			#jwpf-addon-{{ data.id }}:hover .jwpf-feature-box-title{
				color:{{data.title_hover_color}};
			}
		<# }
		if(data.icon_hover_bg || data.icon_hover_color) { #>
			#jwpf-addon-{{ data.id }} .jwpf-icon .jwpf-icon-container{
				transition:.3s;
			}
			#jwpf-addon-{{ data.id }}:hover .jwpf-icon .jwpf-icon-container{
				background:{{data.icon_hover_bg}};
				color:{{data.icon_hover_color}};
			}
		<# } #>

		</style>
		<div class="jwpf-addon jwpf-addon-feature {{ data.class }} {{ alignment }}">
			<div class="jwpf-addon-content {{text_alignment}}">
				<# if (icon_image_position == "before") { #>
					<# if(media){ #>
						{{{ media }}}
					<# } #>
					<div class="jwpf-media-content">
						<# if(data.title){ #>
							{{{ feature_title }}}
						<# } #>
						{{{ feature_text }}}
					</div>
				<# } else if (icon_image_position == "after") { #>
					<# if(data.title){ #>
						{{{ feature_title }}}
					<# } #>
					<# if(media){ #>
						{{{ media }}}
					<# } #>
					<div class="jwpf-media-content">
					{{{ feature_text }}}
					</div>
				<# } else { #>
					<# if(media) { #>
						<div class="jwpf-media">
							<div class="pull-{{ icon_image_position }}">{{{ media }}}</div>
							<div class="jwpf-media-body">
								<div class="jwpf-media-content">
									{{{image_icon}}}
									<# if(data.title){ #>
										{{{ feature_title }}}
									<# } #>
									{{{ feature_text }}}
								</div>
							</div>
						</div>
					<# } else { #>
						<div class="jwpf-media">
							<div class="jwpf-media-body">
								<div class="jwpf-media-content">
									{{{image_icon}}}
									<# if(data.title){ #>
										{{{ feature_title }}}
									<# } #>
									{{{ feature_text }}}
								</div>
							</div>
						</div>
					<# } #>
				<# } #>
			</div>
		</div>
		';

		return $output;
	}

}