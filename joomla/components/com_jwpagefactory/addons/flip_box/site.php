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

class JwpagefactoryAddonFlip_box extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		//get data
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$front_text = (isset($settings->front_text) && $settings->front_text) ? $settings->front_text : '';
		$flip_text = (isset($settings->flip_text) && $settings->flip_text) ? $settings->flip_text : '';
		$rotate = (isset($settings->rotate) && $settings->rotate) ? $settings->rotate : 'flip_right';
		$flip_bhave = (isset($settings->flip_bhave) && $settings->flip_bhave) ? $settings->flip_bhave : 'hover';
		$text_align = (isset($settings->text_align) && $settings->text_align) ? $settings->text_align : 'hover';

		//Flip Style
		$flip_style = (isset($settings->flip_style) && $settings->flip_style) ? $settings->flip_style : 'rotate_style';

		if ($flip_style != '') {
			if ($flip_style == 'slide_style') {
				$flip_style = 'slide-flipbox';
			} elseif ($flip_style == 'fade_style') {
				$flip_style = 'fade-flipbox';
			} elseif ($flip_style == 'threeD_style') {
				$flip_style = 'threeD-flipbox';
			}
		}

		$output = '';
		$output .= '<div class="jwpf-addon jwpf-addon-jwpf-flibox ' . $class . ' ' . $flip_style . ' ' . $rotate . ' flipon-' . $flip_bhave . ' jwpf-text-' . $text_align . '">';

		if ($flip_style == 'threeD-flipbox') {

			$output .= '<div class="threeD-content-wrap">';
			$output .= '<div class="threeD-item">';
			$output .= '<div class = "threeD-flip-front">';
			$output .= '<div class = "threeD-content-inner">';
			$output .= $front_text;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '<div class = "threeD-flip-back">';
			$output .= '<div class = "threeD-content-inner">';
			$output .= $flip_text;
			$output .= '</div>';
			$output .= '</div >';
			$output .= '</div>'; //.threeD-item
			$output .= '</div>'; //.threeD-content-wrap
		} else {

			$output .= '<div class="jwpf-flipbox-panel">';
			$output .= '<div class="jwpf-flipbox-front flip-box">';
			$output .= '<div class="flip-box-inner">';
			$output .= $front_text;
			$output .= '</div>'; //.flip-box-inner
			$output .= '</div>'; //.front
			$output .= '<div class="jwpf-flipbox-back flip-box">';
			$output .= '<div class="flip-box-inner">';
			$output .= $flip_text;
			$output .= '</div>'; //.flip-box-inner
			$output .= '</div>'; //.back
			$output .= '</div>'; //.jwpf-flipbox-panel
		}
		$output .= '</div>'; //.jwpf-addon-jwpf-flibox
		return $output;
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;

		//flip style
		$flip_syles = '';
		$flip_syles_sm = '';
		$flip_syles_xs = '';
		//$flip_syles .= (isset($settings->text_align) && $settings->text_align) ? "text-align: " . $settings->text_align  . ";" : "text-align: center";
		$flip_syles .= (isset($settings->height) && $settings->height) ? "height: " . $settings->height . "px;" : "";
		$flip_syles_sm .= (isset($settings->height_sm) && $settings->height_sm) ? "height: " . $settings->height_sm . "px;" : "";
		$flip_syles_xs .= (isset($settings->height_xs) && $settings->height_xs) ? "height: " . $settings->height_xs . "px;" : "";

		$border_styles = (isset($settings->border_styles) && $settings->border_styles) ? $settings->border_styles : "";
		if (is_array($border_styles) && count($border_styles)) {
			if (in_array('border_radius', $border_styles)) {
				$flip_syles .= 'border-radius: 8px;';
			}
			if (in_array('dashed', $border_styles)) {
				$flip_syles .= 'border-style: dashed;';
			} else if (in_array('solid', $border_styles)) {
				$flip_syles .= 'border-style: solid;';
			} else if (in_array('dotted', $border_styles)) {
				$flip_syles .= 'border-style: dotted;';
			}

			if (in_array('dashed', $border_styles) || in_array('solid', $border_styles) || in_array('dotted', $border_styles)) {
				$flip_syles .= 'border-width: 2px;';
				$flip_syles .= 'border-color:' . $settings->border_color . ';';
			}
		}

		//front style
		$front_style = '';
		if (isset($settings->front_bgimg) && $settings->front_bgimg) {
			if (strpos($settings->front_bgimg, "http://") !== false || strpos($settings->front_bgimg, "https://") !== false) {
				$front_bgimg = $settings->front_bgimg;
			} else {
				$front_bgimg = JURI::root() . $settings->front_bgimg;
			}
			$front_style .= "background-image: url(" . $front_bgimg . ");";
		}

		$front_style .= (isset($settings->front_textcolor) && $settings->front_textcolor) ? "color: " . $settings->front_textcolor . ";" : "";

		//back style
		$back_style = '';
		if (isset($settings->back_bgimg) && $settings->back_bgimg) {
			if (strpos($settings->back_bgimg, "http://") !== false || strpos($settings->back_bgimg, "https://") !== false) {
				$back_bgimg = $settings->back_bgimg;
			} else {
				$back_bgimg = JURI::root() . $settings->back_bgimg;
			}
			$back_style .= "background-image: url(" . $back_bgimg . ");";
		}

		$back_style .= (isset($settings->back_textcolor) && $settings->back_textcolor) ? "color: " . $settings->back_textcolor . ";" : "";

		//front bg color
		$front_bg_color = '';
		$front_bg_color .= (isset($settings->front_bgcolor) && $settings->front_bgcolor) ? "background-color: " . $settings->front_bgcolor . ";" : "";
		//Bg color back
		$back_bg_color = '';
		$back_bg_color .= (isset($settings->back_bgcolor) && $settings->back_bgcolor) ? "background-color: " . $settings->back_bgcolor . ";" : "";


		$css = '';

		if ($flip_syles) {
			$css .= $addon_id . ' .jwpf-flipbox-panel {';
			$css .= $flip_syles;
			$css .= '}';
		}
		if ($flip_syles) {
			$css .= $addon_id . ' .threeD-item {';
			$css .= $flip_syles;
			$css .= '}';
		}

		if ($front_style) {
			$css .= $addon_id . ' .jwpf-flipbox-front {';
			$css .= $front_style;
			$css .= '}';
		}
		if ($front_style) {
			$css .= $addon_id . ' .threeD-flip-front {';
			$css .= $front_style;
			$css .= '}';
		}

		if ($back_style) {
			$css .= $addon_id . ' .jwpf-flipbox-back {';
			$css .= $back_style;
			$css .= '}';
		}
		if ($back_style) {
			$css .= $addon_id . ' .threeD-flip-back {';
			$css .= $back_style;
			$css .= '}';
		}
		//front bg color
		if ($front_bg_color) {
			$css .= $addon_id . ' .threeD-flip-front:before{';
			$css .= $front_bg_color;
			$css .= '}';
		}
		if ($front_bg_color) {
			$css .= $addon_id . ' .jwpf-flipbox-front.flip-box:before{';
			$css .= $front_bg_color;
			$css .= '}';
		}
		//Back bg color
		if ($back_bg_color) {
			$css .= $addon_id . ' .threeD-flip-back:before{';
			$css .= $back_bg_color;
			$css .= '}';
		}
		if ($back_bg_color) {
			$css .= $addon_id . ' .jwpf-flipbox-back.flip-box:before{';
			$css .= $back_bg_color;
			$css .= '}';
		}

		if ($flip_syles_sm) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			$css .= $addon_id . ' .jwpf-flipbox-panel {';
			$css .= $flip_syles_sm;
			$css .= '}';

			$css .= $addon_id . ' .threeD-item {';
			$css .= $flip_syles_sm;
			$css .= '}';
			$css .= '}';
		}

		if ($flip_syles_xs) {
			$css .= '@media (max-width: 767px) {';
			$css .= $addon_id . ' .jwpf-flipbox-panel {';
			$css .= $flip_syles_xs;
			$css .= '}';

			$css .= $addon_id . ' .threeD-item {';
			$css .= $flip_syles_xs;
			$css .= '}';
			$css .= '}';
		}

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
        <#
            var flip_style = (data.flip_style) ? data.flip_style : "rotate_style";

            if (flip_style != "") {
                if (flip_style == "slide_style") {
                    flip_style = "slide-flipbox";
                } else if (flip_style == "fade_style") {
                    flip_style = "fade-flipbox";
                } else if (flip_style == "threeD_style") {
                    flip_style = "threeD-flipbox";
                }
            }

            var border_styles = (data.border_styles) ? data.border_styles : "";

            var flip_styles = "";

            if(_.isObject(data.height)){
                flip_styles += (data.height.md) ? "height: " + data.height.md + "px;" : "";
            } else {
                flip_styles += (data.height) ? "height: " + data.height + "px;" : "";
            }


            if(_.isArray(border_styles)) {
                if(border_styles.indexOf("border_radius") !== -1){
                    flip_styles += "border-radius: 8px;";
                }

                if(border_styles.indexOf("dashed") !== -1){
                    flip_styles += "border-style: dashed;";
                } else if(border_styles.indexOf("solid") !== -1){
                    flip_styles += "border-style: solid;";
                } else if(border_styles.indexOf("dotted") !== -1){
                    flip_styles += "border-style: dotted;";
                }

                if(border_styles.indexOf("dashed") !== -1 || border_styles.indexOf("solid") !== -1 || border_styles.indexOf("dotted") !== -1){
                    flip_styles += "border-width: 2px;";
                    flip_styles += "border-color:"+data.border_color+";";
                }
            }

            let front_bgimg = "";
            if(data.front_bgimg.indexOf("http://") !== -1 || data.front_bgimg.indexOf("https://") !== -1){
                front_bgimg = data.front_bgimg;
            } else {
                front_bgimg = pagefactory_base + data.front_bgimg;
            }

            let back_bgimg = "";
            if(data.back_bgimg.indexOf("http://") !== -1 || data.back_bgimg.indexOf("https://") !== -1){
                back_bgimg = data.back_bgimg;
            } else {
                back_bgimg = pagefactory_base + data.back_bgimg;
            }
        #>
        <style type="text/css">
            #jwpf-addon-{{ data.id }} .jwpf-flipbox-panel,
            #jwpf-addon-{{ data.id }} .threeD-item{
                {{ flip_styles }}
            }

            #jwpf-addon-{{ data.id }} .jwpf-flipbox-front,
            #jwpf-addon-{{ data.id }} .threeD-flip-front{
                background-image: url({{ front_bgimg }});
                color: {{ data.front_textcolor }};
            }

            #jwpf-addon-{{ data.id }} .jwpf-flipbox-back,
            #jwpf-addon-{{ data.id }} .threeD-flip-back{
                background-image: url({{ back_bgimg }});
                color: {{ data.back_textcolor }};
            }

            #jwpf-addon-{{ data.id }} .threeD-flip-front:before,
            #jwpf-addon-{{ data.id }} .jwpf-flipbox-front.flip-box:before{
                background-color: {{ data.front_bgcolor }};
            }

            #jwpf-addon-{{ data.id }} .threeD-flip-back:before,
            #jwpf-addon-{{ data.id }} .jwpf-flipbox-back.flip-box:before{
                background-color: {{ data.back_bgcolor }};
            }

            @media (min-width: 768px) and (max-width: 991px) {
                #jwpf-addon-{{ data.id }} .jwpf-flipbox-panel,
                #jwpf-addon-{{ data.id }} .threeD-item{
                    <# if(_.isObject(data.height)){ #>
                        height: {{ data.height.sm }}px;
                    <# } #>
                }
            }

            @media (max-width: 767px) {
                #jwpf-addon-{{ data.id }} .jwpf-flipbox-panel,
                #jwpf-addon-{{ data.id }} .threeD-item{
                    <# if(_.isObject(data.height)){ #>
                        height: {{ data.height.xs }}px;
                    <# } #>
                }
            }
        </style>
        <div class="jwpf-addon jwpf-addon-jwpf-flibox {{ data.class }} {{ flip_style }} {{ data.rotate }} flipon-{{ data.flip_bhave }} jwpf-text-{{ data.text_align }}">
            <# if (flip_style == "threeD-flipbox") { #>
                <div class="threeD-content-wrap">
                    <div class="threeD-item">
                        <div class="threeD-flip-front">
                            <div class="threeD-content-inner jw-inline-editable-element" data-id={{data.id}} data-fieldName="front_text" contenteditable="true">{{{ data.front_text }}}</div>
                        </div>
                        <div class="threeD-flip-back">
                            <div class="threeD-content-inner jw-inline-editable-element" data-id={{data.id}} data-fieldName="flip_text" contenteditable="true">{{{ data.flip_text }}}</div>
                        </div >
                    </div>
                </div>
            <# } else { #>
                <div class="jwpf-flipbox-panel">
                    <div class="jwpf-flipbox-front flip-box">
                        <div class="flip-box-inner jw-inline-editable-element" data-id={{data.id}} data-fieldName="front_text" contenteditable="true">{{{ data.front_text }}}</div>
                    </div>
                    <div class="jwpf-flipbox-back flip-box">
                        <div class="flip-box-inner jw-inline-editable-element" data-id={{data.id}} data-fieldName="flip_text" contenteditable="true">{{{ data.flip_text }}}</div>
                    </div>
                </div>
            <# } #>
        </div>
        ';

		return $output;
	}

}
