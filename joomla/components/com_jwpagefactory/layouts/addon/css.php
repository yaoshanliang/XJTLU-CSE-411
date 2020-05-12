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

$doc = JFactory::getDocument();

//Image lazy load
$config = JComponentHelper::getParams('com_jwpagefactory');
$lazyload = $config->get('lazyloadimg', '0');
$placeholder = $config->get('lazyplaceholder', '');
$lazy_bg_image = '';
$placeholder_bg_image = '';

if (!class_exists('JwpfCustomCssParser')) {
	require_once JPATH_ROOT . '/components/com_jwpagefactory/helpers/css-parser.php';
}

$selector_css = new JLayoutFile('addon.css.selector', JPATH_ROOT . '/components/com_jwpagefactory/layouts');

$addon = $displayData['addon'];

$inlineCSS = '';
$addon_css = '';
$addon_link_css = '';
$addon_link_hover_css = '';
$addon_id = "#jwpf-addon-" . $addon->id;
$addon_wrapper_id = "#jwpf-addon-wrapper-" . $addon->id;

// Color
if (isset($addon->settings->global_text_color) && $addon->settings->global_text_color) {
	$addon_css .= "\tcolor: " . $addon->settings->global_text_color . ";\n";
}

if (isset($addon->settings->global_link_color) && $addon->settings->global_link_color) {
	$addon_link_css .= "\tcolor: " . $addon->settings->global_link_color . ";\n";
}

if (isset($addon->settings->global_link_hover_color) && $addon->settings->global_link_hover_color) {
	$addon_link_hover_css .= "\tcolor: " . $addon->settings->global_link_hover_color . ";\n";
}

// Background
if (!isset($addon->settings->global_background_type) && isset($addon->settings->global_use_background) && $addon->settings->global_use_background) {
	if (isset($addon->settings->global_background_color) && $addon->settings->global_background_color) {
		$addon_css .= "\tbackground-color: " . $addon->settings->global_background_color . ";\n";
	}

	if (isset($addon->settings->global_background_image) && $addon->settings->global_background_image) {

		if ($lazyload) {
			if ($placeholder) {
				$placeholder_bg_image .= 'background-image:url(' . $placeholder . ');';
			}
			if (strpos($addon->settings->global_background_image, "http://") !== false || strpos($addon->settings->global_background_image, "https://") !== false) {
				$lazy_bg_image .= "\tbackground-image: url(" . $addon->settings->global_background_image . ");\n";
			} else {
				$lazy_bg_image .= "\tbackground-image: url(" . JURI::base(true) . '/' . $addon->settings->global_background_image . ");\n";
			}
		} else {
			if (strpos($addon->settings->global_background_image, "http://") !== false || strpos($addon->settings->global_background_image, "https://") !== false) {
				$addon_css .= "\tbackground-image: url(" . $addon->settings->global_background_image . ");\n";
			} else {
				$addon_css .= "\tbackground-image: url(" . JURI::base(true) . '/' . $addon->settings->global_background_image . ");\n";
			}
		}

		if (isset($addon->settings->global_background_repeat) && $addon->settings->global_background_repeat) {
			$addon_css .= "\tbackground-repeat: " . $addon->settings->global_background_repeat . ";\n";
		}

		if (isset($addon->settings->global_background_size) && $addon->settings->global_background_size) {
			$addon_css .= "\tbackground-size: " . $addon->settings->global_background_size . ";\n";
		}

		if (isset($addon->settings->global_background_attachment) && $addon->settings->global_background_attachment) {
			$addon_css .= "\tbackground-attachment: " . $addon->settings->global_background_attachment . ";\n";
		}

		if (isset($addon->settings->global_background_position) && $addon->settings->global_background_position) {
			$addon_css .= "\tbackground-position:" . $addon->settings->global_background_position . ";";
		}
	}
} else if (isset($addon->settings->global_background_type)) {
	if (($addon->settings->global_background_type == 'color' || $addon->settings->global_background_type == 'image') && isset($addon->settings->global_background_color) && $addon->settings->global_background_color) {
		$addon_css .= "\tbackground-color: " . $addon->settings->global_background_color . ";\n";
	}

	if ($addon->settings->global_background_type == 'gradient' && isset($addon->settings->global_background_gradient) && is_object($addon->settings->global_background_gradient)) {
		$radialPos = (isset($addon->settings->global_background_gradient->radialPos) && !empty($addon->settings->global_background_gradient->radialPos)) ? $addon->settings->global_background_gradient->radialPos : 'center center';

		$gradientColor = (isset($addon->settings->global_background_gradient->color) && !empty($addon->settings->global_background_gradient->color)) ? $addon->settings->global_background_gradient->color : '';

		$gradientColor2 = (isset($addon->settings->global_background_gradient->color2) && !empty($addon->settings->global_background_gradient->color2)) ? $addon->settings->global_background_gradient->color2 : '';

		$gradientDeg = (isset($addon->settings->global_background_gradient->deg) && !empty($addon->settings->global_background_gradient->deg)) ? $addon->settings->global_background_gradient->deg : '0';

		$gradientPos = (isset($addon->settings->global_background_gradient->pos) && !empty($addon->settings->global_background_gradient->pos)) ? $addon->settings->global_background_gradient->pos : '0';

		$gradientPos2 = (isset($addon->settings->global_background_gradient->pos2) && !empty($addon->settings->global_background_gradient->pos2)) ? $addon->settings->global_background_gradient->pos2 : '100';

		if (isset($addon->settings->global_background_gradient->type) && $addon->settings->global_background_gradient->type == 'radial') {
			$addon_css .= "\tbackground-image: radial-gradient(at " . $radialPos . ", " . $gradientColor . " " . $gradientPos . "%, " . $gradientColor2 . " " . $gradientPos2 . "%);\n";
		} else {
			$addon_css .= "\tbackground-image: linear-gradient(" . $gradientDeg . "deg, " . $gradientColor . " " . $gradientPos . "%, " . $gradientColor2 . " " . $gradientPos2 . "%);\n";
		}
	}

	if ($addon->settings->global_background_type == 'image' && isset($addon->settings->global_background_image) && $addon->settings->global_background_image) {

		if ($lazyload) {
			if ($placeholder) {
				$placeholder_bg_image .= 'background-image:url(' . $placeholder . ');';
			}
			if (strpos($addon->settings->global_background_image, "http://") !== false || strpos($addon->settings->global_background_image, "https://") !== false) {
				$lazy_bg_image .= "\tbackground-image: url(" . $addon->settings->global_background_image . ");\n";
			} else {
				$lazy_bg_image .= "\tbackground-image: url(" . JURI::base(true) . '/' . $addon->settings->global_background_image . ");\n";
			}
		} else {
			if (strpos($addon->settings->global_background_image, "http://") !== false || strpos($addon->settings->global_background_image, "https://") !== false) {
				$addon_css .= "\tbackground-image: url(" . $addon->settings->global_background_image . ");\n";
			} else {
				$addon_css .= "\tbackground-image: url(" . JURI::base(true) . '/' . $addon->settings->global_background_image . ");\n";
			}
		}

		if (isset($addon->settings->global_background_repeat) && $addon->settings->global_background_repeat) {
			$addon_css .= "\tbackground-repeat: " . $addon->settings->global_background_repeat . ";\n";
		}

		if (isset($addon->settings->global_background_size) && $addon->settings->global_background_size) {
			$addon_css .= "\tbackground-size: " . $addon->settings->global_background_size . ";\n";
		}

		if (isset($addon->settings->global_background_attachment) && $addon->settings->global_background_attachment) {
			$addon_css .= "\tbackground-attachment: " . $addon->settings->global_background_attachment . ";\n";
		}

		if (isset($addon->settings->global_background_position) && $addon->settings->global_background_position) {
			$addon_css .= "\tbackground-position:" . $addon->settings->global_background_position . ";";
		}
	}
}


// Box Shadow
if (isset($addon->settings->global_boxshadow) && $addon->settings->global_boxshadow) {
	if (is_object($addon->settings->global_boxshadow)) {
		$ho = (isset($addon->settings->global_boxshadow->ho) && $addon->settings->global_boxshadow->ho != '') ? $addon->settings->global_boxshadow->ho . 'px' : '0px';
		$vo = (isset($addon->settings->global_boxshadow->vo) && $addon->settings->global_boxshadow->vo != '') ? $addon->settings->global_boxshadow->vo . 'px' : '0px';
		$blur = (isset($addon->settings->global_boxshadow->blur) && $addon->settings->global_boxshadow->blur != '') ? $addon->settings->global_boxshadow->blur . 'px' : '0px';
		$spread = (isset($addon->settings->global_boxshadow->spread) && $addon->settings->global_boxshadow->spread != '') ? $addon->settings->global_boxshadow->spread . 'px' : '0px';
		$color = (isset($addon->settings->global_boxshadow->color) && $addon->settings->global_boxshadow->color != '') ? $addon->settings->global_boxshadow->color : '#fff';

		$addon_css .= "\tbox-shadow: ${ho} ${vo} ${blur} ${spread} ${color};\n";
	} else {
		$addon_css .= "\tbox-shadow: " . $addon->settings->global_boxshadow . ";\n";
	}
}

// Border
if (isset($addon->settings->global_user_border) && $addon->settings->global_user_border) {

	if (isset($addon->settings->global_border_width) && is_object($addon->settings->global_border_width) && $addon->settings->global_border_width != '') {
		$addon_css .= isset($addon->settings->global_border_width->md) && $addon->settings->global_border_width->md != '' ? "border-width: " . $addon->settings->global_border_width->md . "px;\n" : "";
	} else {
		$addon_css .= isset($addon->settings->global_border_width) && $addon->settings->global_border_width != '' ? "border-width: " . $addon->settings->global_border_width . "px;\n" : "";
	}

	if (isset($addon->settings->global_border_color) && $addon->settings->global_border_color) {
		$addon_css .= "border-color: " . $addon->settings->global_border_color . ";\n";
	}

	if (isset($addon->settings->global_boder_style) && $addon->settings->global_boder_style) {
		$addon_css .= "border-style: " . $addon->settings->global_boder_style . ";\n";
	}
}

// Border radius

if (isset($addon->settings->global_border_radius)) {
	if (is_object($addon->settings->global_border_radius)) {
		$addon_css .= (isset($addon->settings->global_border_radius->md) && $addon->settings->global_border_radius->md) ? "border-radius: " . $addon->settings->global_border_radius->md . "px;\n" : "";
	} else {
		$addon_css .= ($addon->settings->global_border_radius) ? "border-radius: " . $addon->settings->global_border_radius . "px;\n" : "";
	}
}

if (isset($addon->settings->global_padding)) {
	if (is_object($addon->settings->global_padding)) {
		$addon_css .= JwpagefactoryHelperSite::getPaddingMargin($addon->settings->global_padding, 'padding');
	} else {
		$addon->settings->global_padding = trim($addon->settings->global_padding);
		if (!empty($addon->settings->global_padding)) {
			$addon_css .= 'padding:' . $addon->settings->global_padding . ';';
		}
	}
}

if (isset($addon->settings->global_use_overlay) && $addon->settings->global_use_overlay) {
	$addon_css .= "position: relative;\noverflow: hidden;\n";
}

//Custom position
$position_css = '';
if (isset($addon->settings->global_custom_position) && $addon->settings->global_custom_position && isset($addon->settings->global_seclect_position) && $addon->settings->global_seclect_position && $addon->settings->global_seclect_position != '') {

	if ($addon->settings->global_seclect_position) {
		$position_css .= 'position:' . $addon->settings->global_seclect_position . ';';
	}
	if (isset($addon->settings->global_addon_position_left) && is_object($addon->settings->global_addon_position_left) && isset($addon->settings->global_addon_position_left->md)) {
		$position_css .= 'left:' . $addon->settings->global_addon_position_left->md . ';';
	} else if (isset($addon->settings->global_addon_position_left) && !is_object($addon->settings->global_addon_position_left)) {
		$position_css .= 'left:' . $addon->settings->global_addon_position_left . ';';
	}
	if (isset($addon->settings->global_addon_position_top) && is_object($addon->settings->global_addon_position_top) && isset($addon->settings->global_addon_position_top->md)) {
		$position_css .= 'top:' . $addon->settings->global_addon_position_top->md . ';';
	} else if (isset($addon->settings->global_addon_position_top) && !is_object($addon->settings->global_addon_position_top)) {
		$position_css .= 'top:' . $addon->settings->global_addon_position_top . ';';
	}
	if (isset($addon->settings->global_addon_z_index) && $addon->settings->global_addon_z_index != '') {
		$position_css .= 'z-index:' . $addon->settings->global_addon_z_index . ';';
	}
}

if (isset($addon->settings->global_margin)) {
	if (is_object($addon->settings->global_margin)) {
		$position_css .= JwpagefactoryHelperSite::getPaddingMargin($addon->settings->global_margin, 'margin');
	} else if (!empty(trim($addon->settings->global_margin))) {
		$position_css .= 'margin:' . $addon->settings->global_margin . ';';
	}
}

if (isset($addon->settings->use_global_width) && $addon->settings->use_global_width && isset($addon->settings->global_width) && $addon->settings->global_width) {
	$position_css .= "width: " . (int)$addon->settings->global_width . "%;\n";
}

if ($position_css) {
	$inlineCSS .= '#jwpf-addon-wrapper-' . $addon->id . " {\n" . $position_css . "}\n";
}

if ($addon_css) {
	$inlineCSS .= $addon_id . " {\n" . $addon_css . "}\n";
	$inlineCSS .= $addon_id . " {\n" . $placeholder_bg_image . "}\n";
	$inlineCSS .= $addon_id . ".jwpf-element-loaded {\n" . $lazy_bg_image . "}\n";
}

if (!isset($addon->settings->global_overlay_type)) {
	$addon->settings->global_overlay_type = 'overlay_color';
}

if (isset($addon->settings->global_use_overlay) && $addon->settings->global_use_overlay && isset($addon->settings->global_background_overlay) && $addon->settings->global_background_overlay) {
	$inlineCSS .= $addon_id . " .jwpf-addon-overlayer { background-color: {$addon->settings->global_background_overlay}; }\n";
}
if (isset($addon->settings->global_use_overlay) && $addon->settings->global_use_overlay) {
	$inlineCSS .= $addon_id . " > .jwpf-addon { position: relative; }\n";
}

// Overlay
if (isset($addon->settings->global_background_type)) {
	if ($addon->settings->global_background_type == 'image') {
		if (isset($addon->settings->global_gradient_overlay) && $addon->settings->global_overlay_type == 'overlay_gradient') {
			$overlay_radialPos = (isset($addon->settings->global_gradient_overlay->radialPos) && !empty($addon->settings->global_gradient_overlay->radialPos)) ? $addon->settings->global_gradient_overlay->radialPos : 'center center';

			$overlay_gradientColor = (isset($addon->settings->global_gradient_overlay->color) && !empty($addon->settings->global_gradient_overlay->color)) ? $addon->settings->global_gradient_overlay->color : '';

			$overlay_gradientColor2 = (isset($addon->settings->global_gradient_overlay->color2) && !empty($addon->settings->global_gradient_overlay->color2)) ? $addon->settings->global_gradient_overlay->color2 : '';

			$overlay_gradientDeg = (isset($addon->settings->global_gradient_overlay->deg) && !empty($addon->settings->global_gradient_overlay->deg)) ? $addon->settings->global_gradient_overlay->deg : '0';

			$overlay_gradientPos = (isset($addon->settings->global_gradient_overlay->pos) && !empty($addon->settings->global_gradient_overlay->pos)) ? $addon->settings->global_gradient_overlay->pos : '0';

			$overlay_gradientPos2 = (isset($addon->settings->global_gradient_overlay->pos2) && !empty($addon->settings->global_gradient_overlay->pos2)) ? $addon->settings->global_gradient_overlay->pos2 : '100';

			if (isset($addon->settings->global_gradient_overlay->type) && $addon->settings->global_gradient_overlay->type == 'radial') {
				$inlineCSS .= $addon_id . ' .jwpf-addon-overlayer {
					background: radial-gradient(at ' . $overlay_radialPos . ', ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';

			} else {
				$inlineCSS .= $addon_id . ' .jwpf-addon-overlayer {
					background: linear-gradient(' . $overlay_gradientDeg . 'deg, ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';
			}
		}
		if (isset($addon->settings->global_pattern_overlay) && $addon->settings->global_overlay_type == 'overlay_pattern') {
			if (strpos($addon->settings->global_pattern_overlay, "http://") !== false || strpos($addon->settings->global_pattern_overlay, "https://") !== false) {
				$inlineCSS .= $addon_id . ' .jwpf-addon-overlayer {
					background-image:url(' . $addon->settings->global_pattern_overlay . ');
					background-attachment: scroll;
				}';
				if (isset($addon->settings->global_overlay_pattern_color)) {
					$inlineCSS .= $addon_id . ' .jwpf-addon-overlayer {
						background-color:' . $addon->settings->global_overlay_pattern_color . ';
					}';
				}
			} else {
				$inlineCSS .= $addon_id . ' .jwpf-addon-overlayer {
					background-image:url(' . JURI::base() . '/' . $addon->settings->global_pattern_overlay . ');
					background-attachment: scroll;
				}';
				if (isset($addon->settings->global_overlay_pattern_color)) {
					$inlineCSS .= $addon_id . ' .jwpf-addon-overlayer {
						background-color:' . $addon->settings->global_overlay_pattern_color . ';
					}';
				}
			}
		}
	}
}

//Blend Mode
if (isset($addon->settings->global_background_type) && $addon->settings->global_background_type) {
	if ($addon->settings->global_background_type == 'image') {
		if (isset($addon->settings->blend_mode) && $addon->settings->blend_mode) {
			$inlineCSS .= $addon_id . ' .jwpf-addon-overlayer {
				mix-blend-mode:' . $addon->settings->blend_mode . ';
			}';
		}
	}
}

if ($addon_link_css) {
	$inlineCSS .= $addon_id . " a {\n" . $addon_link_css . "}\n";
}

if ($addon_link_hover_css) {
	$inlineCSS .= $addon_id . " a:hover,\n#jwpf-addon-" . $addon->id . " a:focus,\n#jwpf-addon-" . $addon->id . " a:active {\n" . $addon_link_hover_css . "}\n";
}

//Addon Title
if (isset($addon->settings->title) && $addon->settings->title) {

	$title_style = '';

	if (isset($addon->settings->title_margin_top) && is_object($addon->settings->title_margin_top)) {
		$title_style .= (isset($addon->settings->title_margin_top->md) && $addon->settings->title_margin_top->md != '') ? 'margin-top:' . (int)$addon->settings->title_margin_top->md . 'px;' : '';
	} else {
		$title_style .= (isset($addon->settings->title_margin_top) && $addon->settings->title_margin_top != '') ? 'margin-top:' . (int)$addon->settings->title_margin_top . 'px;' : '';
	}

	if (isset($addon->settings->title_margin_bottom) && is_object($addon->settings->title_margin_bottom)) {
		$title_style .= (isset($addon->settings->title_margin_bottom->md) && $addon->settings->title_margin_bottom->md != '') ? 'margin-bottom:' . (int)$addon->settings->title_margin_bottom->md . 'px;' : '';
	} else {
		$title_style .= (isset($addon->settings->title_margin_bottom) && $addon->settings->title_margin_bottom != '') ? 'margin-bottom:' . (int)$addon->settings->title_margin_bottom . 'px;' : '';
	}

	$title_style .= (isset($addon->settings->title_text_color) && $addon->settings->title_text_color) ? 'color:' . $addon->settings->title_text_color . ';' : '';

	if (isset($addon->settings->title_fontsize) && is_object($addon->settings->title_fontsize)) {
		$title_style .= (isset($addon->settings->title_fontsize->md) && $addon->settings->title_fontsize->md) ? 'font-size:' . $addon->settings->title_fontsize->md . 'px;' : '';
	} else {
		$title_style .= (isset($addon->settings->title_fontsize) && $addon->settings->title_fontsize) ? 'font-size:' . $addon->settings->title_fontsize . 'px;' : '';
	}

	if (isset($addon->settings->title_lineheight) && is_object($addon->settings->title_lineheight)) {
		$title_style .= (isset($addon->settings->title_lineheight->md) && $addon->settings->title_lineheight->md) ? 'line-height:' . $addon->settings->title_lineheight->md . 'px;' : '';
	} else {
		$title_style .= (isset($addon->settings->title_lineheight) && $addon->settings->title_lineheight) ? 'line-height:' . $addon->settings->title_lineheight . 'px;' : '';
	}

	$title_style .= (isset($addon->settings->title_letterspace) && $addon->settings->title_letterspace) ? 'letter-spacing:' . $addon->settings->title_letterspace . ';' : '';

	// Font Style
	$modern_font_style = false;
	if (isset($addon->settings->title_font_style->underline) && $addon->settings->title_font_style->underline) {
		$title_style .= 'text-decoration: underline;';
		$modern_font_style = true;
	}

	if (isset($addon->settings->title_font_style->italic) && $addon->settings->title_font_style->italic) {
		$title_style .= 'font-style: italic;';
		$modern_font_style = true;
	}

	if (isset($addon->settings->title_font_style->uppercase) && $addon->settings->title_font_style->uppercase) {
		$title_style .= 'text-transform: uppercase;';
		$modern_font_style = true;
	}

	if (isset($addon->settings->title_font_style->weight) && $addon->settings->title_font_style->weight) {
		$title_style .= 'font-weight: ' . $addon->settings->title_font_style->weight . ';';
		$modern_font_style = true;
	}

	// legcy font style
	if (!$modern_font_style) {
		$title_fontstyle = (isset($addon->settings->title_fontstyle) && $addon->settings->title_fontstyle) ? $addon->settings->title_fontstyle : '';
		if (is_array($title_fontstyle) && count($title_fontstyle)) {

			if (in_array('underline', $title_fontstyle)) {
				$title_style .= 'text-decoration: underline;';
			}

			if (in_array('uppercase', $title_fontstyle)) {
				$title_style .= 'text-transform: uppercase;';
			}

			if (in_array('italic', $title_fontstyle)) {
				$title_style .= 'font-style: italic;';
			}

			if (in_array('lighter', $title_fontstyle)) {
				$title_style .= 'font-weight: lighter;';
			} else if (in_array('normal', $title_fontstyle)) {
				$title_style .= 'font-weight: normal;';
			} else if (in_array('bold', $title_fontstyle)) {
				$title_style .= 'font-weight: bold;';
			} else if (in_array('bolder', $title_fontstyle)) {
				$title_style .= 'font-weight: bolder;';
			}

			$title_style .= (isset($addon->settings->title_fontweight) && $addon->settings->title_fontweight) ? 'font-weight:' . $addon->settings->title_fontweight . ';' : '';

		}
	}

	if ($title_style) {
		$inlineCSS .= $addon_id . " .jwpf-addon-title {\n" . $title_style . "}\n";
	}
}

// Responsive Tablet
$inlineCSS .= "@media (min-width: 768px) and (max-width: 991px) {";
$inlineCSS .= $addon_id . " {";

$inlineCSS .= (isset($addon->settings->global_padding_sm) && $addon->settings->global_padding_sm) ? JwpagefactoryHelperSite::getPaddingMargin($addon->settings->global_padding_sm, 'padding') : '';

$inlineCSS .= (isset($addon->settings->global_border_radius_sm) && $addon->settings->global_border_radius_sm) ? "border-radius: " . $addon->settings->global_border_radius_sm . "px;\n" : "";

if (isset($addon->settings->global_user_border) && $addon->settings->global_user_border) {
	$inlineCSS .= isset($addon->settings->global_border_width_sm) && $addon->settings->global_border_width_sm != '' ? "border-width: " . $addon->settings->global_border_width_sm . "px;\n" : "";
}

$inlineCSS .= "}";

if (isset($addon->settings->title) && $addon->settings->title) {
	$title_style_sm = (isset($addon->settings->title_fontsize_sm) && $addon->settings->title_fontsize_sm) ? 'font-size:' . $addon->settings->title_fontsize_sm . 'px;line-height:' . $addon->settings->title_fontsize_sm . 'px;' : '';
	$title_style_sm .= (isset($addon->settings->title_lineheight_sm) && $addon->settings->title_lineheight_sm) ? 'line-height:' . $addon->settings->title_lineheight_sm . 'px;' : '';
	$title_style_sm .= (isset($addon->settings->title_margin_top_sm) && $addon->settings->title_margin_top_sm != '') ? 'margin-top:' . (int)$addon->settings->title_margin_top_sm . 'px;' : '';
	$title_style_sm .= (isset($addon->settings->title_margin_bottom_sm) && $addon->settings->title_margin_bottom_sm != '') ? 'margin-bottom:' . (int)$addon->settings->title_margin_bottom_sm . 'px;' : '';

	if ($title_style_sm) {
		$inlineCSS .= $addon_id . " .jwpf-addon-title {\n" . $title_style_sm . "}\n";
	}
}

//Custom position tablet
$position_style_sm = '';
if (isset($addon->settings->global_custom_position) && $addon->settings->global_custom_position) {
	if (isset($addon->settings->global_addon_position_left_sm) && is_object($addon->settings->global_addon_position_left_sm) && isset($addon->settings->global_addon_position_left_sm->sm)) {
		$position_style_sm .= 'left:' . $addon->settings->global_addon_position_left_sm->sm . ';';
	} else if (isset($addon->settings->global_addon_position_left_sm) && !is_object($addon->settings->global_addon_position_left_sm)) {
		$position_style_sm .= 'left:' . $addon->settings->global_addon_position_left_sm . ';';
	}
	if (isset($addon->settings->global_addon_position_top_sm) && is_object($addon->settings->global_addon_position_top_sm) && isset($addon->settings->global_addon_position_top_sm->sm)) {
		$position_style_sm .= 'top:' . $addon->settings->global_addon_position_top_sm->sm . ';';
	} else if (isset($addon->settings->global_addon_position_top_sm) && !is_object($addon->settings->global_addon_position_top_sm)) {
		$position_style_sm .= 'top:' . $addon->settings->global_addon_position_top_sm . ';';
	}
}
if (isset($addon->settings->use_global_width) && $addon->settings->use_global_width && isset($addon->settings->global_width_sm) && $addon->settings->global_width_sm) {
	$position_style_sm .= "width: " . $addon->settings->global_width_sm . "%;\n";
}
$position_style_sm .= (isset($addon->settings->global_margin_sm) && $addon->settings->global_margin_sm) ? JwpagefactoryHelperSite::getPaddingMargin($addon->settings->global_margin_sm, 'margin') : '';
if ($position_style_sm) {
	$inlineCSS .= '#jwpf-addon-wrapper-' . $addon->id . ' {' . $position_style_sm . '}';
}

$inlineCSS .= "}";

// Responsive Phone
$inlineCSS .= "@media (max-width: 767px) {";
$inlineCSS .= $addon_id . " {";

$inlineCSS .= (isset($addon->settings->global_padding_xs) && $addon->settings->global_padding_xs) ? JwpagefactoryHelperSite::getPaddingMargin($addon->settings->global_padding_xs, 'padding') : '';

$inlineCSS .= (isset($addon->settings->global_border_radius_xs) && $addon->settings->global_border_radius_xs) ? "border-radius: " . $addon->settings->global_border_radius_xs . "px;\n" : "";

if (isset($addon->settings->global_user_border) && $addon->settings->global_user_border) {
	$inlineCSS .= isset($addon->settings->global_border_width_xs) && $addon->settings->global_border_width_xs != '' ? "border-width: " . $addon->settings->global_border_width_xs . "px;\n" : "";
}

$inlineCSS .= "}";

if (isset($addon->settings->title) && $addon->settings->title) {
	$title_style_xs = (isset($addon->settings->title_fontsize_xs) && $addon->settings->title_fontsize_xs) ? 'font-size:' . $addon->settings->title_fontsize_xs . 'px;line-height:' . $addon->settings->title_fontsize_xs . 'px;' : '';
	$title_style_xs .= (isset($addon->settings->title_lineheight_xs) && $addon->settings->title_lineheight_xs) ? 'line-height:' . $addon->settings->title_lineheight_xs . 'px;' : '';
	$title_style_xs .= (isset($addon->settings->title_margin_top_xs) && $addon->settings->title_margin_top_xs != '') ? 'margin-top:' . (int)$addon->settings->title_margin_top_xs . 'px;' : '';
	$title_style_xs .= (isset($addon->settings->title_margin_bottom_xs) && $addon->settings->title_margin_bottom_xs != '') ? 'margin-bottom:' . (int)$addon->settings->title_margin_bottom_xs . 'px;' : '';

	if ($title_style_xs) {
		$inlineCSS .= $addon_id . " .jwpf-addon-title {\n" . $title_style_xs . "}\n";
	}
}

//Custom position mobile
$position_style_xs = '';
if (isset($addon->settings->global_custom_position) && $addon->settings->global_custom_position) {

	if (isset($addon->settings->global_addon_position_left_xs) && is_object($addon->settings->global_addon_position_left_xs) && isset($addon->settings->global_addon_position_left_xs->xs)) {
		$position_style_xs .= 'left:' . $addon->settings->global_addon_position_left_xs->xs . ';';
	} else if (isset($addon->settings->global_addon_position_left_xs) && !is_object($addon->settings->global_addon_position_left_xs)) {
		$position_style_xs .= 'left:' . $addon->settings->global_addon_position_left_xs . ';';
	}
	if (isset($addon->settings->global_addon_position_top_xs) && is_object($addon->settings->global_addon_position_top_xs) && isset($addon->settings->global_addon_position_top_xs->xs)) {
		$position_style_xs .= 'top:' . $addon->settings->global_addon_position_top_xs->xs . ';';
	} else if (isset($addon->settings->global_addon_position_top_xs) && !is_object($addon->settings->global_addon_position_top_xs)) {
		$position_style_xs .= 'top:' . $addon->settings->global_addon_position_top_xs . ';';
	}
}
if (isset($addon->settings->use_global_width) && $addon->settings->use_global_width && isset($addon->settings->global_width_xs) && $addon->settings->global_width_xs) {
	$position_style_xs .= "width: " . $addon->settings->global_width_xs . "%;\n";
}
$position_style_xs .= (isset($addon->settings->global_margin_xs) && $addon->settings->global_margin_xs) ? JwpagefactoryHelperSite::getPaddingMargin($addon->settings->global_margin_xs, 'margin') : '';
if ($position_style_xs) {
	$inlineCSS .= '#jwpf-addon-wrapper-' . $addon->id . ' {' . $position_style_xs . '}';
}

$inlineCSS .= "}";

// Selector
$inlineCSS .= $selector_css->render(
	array(
		'options' => $addon->settings,
		'addon_id' => $addon_id
	)
);

if (class_exists('JwpfCustomCssParser') && isset($addon->settings->global_custom_css) && !empty($addon->settings->global_custom_css)) {
	$inlineCSS .= JwpfCustomCssParser::getCss($addon->settings->global_custom_css, $addon_id, $addon_wrapper_id);
}

echo $inlineCSS;
