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

$options = $displayData['options'];

$doc = JFactory::getDocument();

//Image lazy load
$config = JComponentHelper::getParams('com_jwpagefactory');
$lazyload = $config->get('lazyloadimg', '0');

$custom_class = (isset($options->class) && ($options->class)) ? ' ' . $options->class : '';
$row_id = (isset($options->id) && $options->id) ? $options->id : 'section-id-' . $options->dynamicId;
$fluid_row = (isset($options->fullscreen) && $options->fullscreen) ? $options->fullscreen : 0;
$row_class = (isset($options->no_gutter) && $options->no_gutter) ? ' jwpf-no-gutter' : '';

if ($lazyload && isset($options->background_type) && $options->background_type) {
	if ($options->background_type == 'image' || $options->background_type == 'video') {
		$custom_class .= ' jwpf-element-lazy';
	}
}

if (isset($options->columns_content_alignment) && $options->columns_content_alignment == 'top') {
	$row_class .= (isset($options->columns_align_center) && $options->columns_align_center) ? ' jwpf-align-top' : '';
} else if (isset($options->columns_content_alignment) && $options->columns_content_alignment == 'bottom') {
	$row_class .= (isset($options->columns_align_center) && $options->columns_align_center) ? ' jwpf-align-bottom' : '';
} else {
	$row_class .= (isset($options->columns_align_center) && $options->columns_align_center) ? ' jwpf-align-center' : '';
}

$external_video = (isset($options->background_external_video) && $options->background_external_video) ? $options->background_external_video : '';
$background_parallax = (isset($options->background_parallax) && $options->background_parallax) ? (int)$options->background_parallax : 0;

$sec_cont_center = '';
if (isset($options->columns_content_alignment) && $options->columns_content_alignment == 'top') {
	$sec_cont_center = (isset($options->columns_align_center) && $options->columns_align_center) ? ' jwpf-section-content-top' : '';
} else if (isset($options->columns_content_alignment) && $options->columns_content_alignment == 'bottom') {
	$sec_cont_center = (isset($options->columns_align_center) && $options->columns_align_center) ? ' jwpf-section-content-bottom' : '';
} else {
	$sec_cont_center = (isset($options->columns_align_center) && $options->columns_align_center) ? ' jwpf-section-content-center' : '';
}

// Visibility
if (isset($options->hidden_md) && $options->hidden_md) {
	$custom_class .= ' jwpf-hidden-md jwpf-hidden-lg';
}

if (isset($options->hidden_sm) && $options->hidden_sm) {
	$custom_class .= ' jwpf-hidden-sm';
}

if (isset($options->hidden_xs) && $options->hidden_xs) {
	$custom_class .= ' jwpf-hidden-xs';
}

$addon_attr = '';

// Animation
if (isset($options->animation) && $options->animation) {

	$custom_class .= ' jwpf-wow ' . $options->animation;

	if (isset($options->animationduration) && $options->animationduration) {
		$addon_attr .= ' data-jwpf-wow-duration="' . $options->animationduration . 'ms"';
	}

	if (isset($options->animationdelay) && $options->animationdelay) {
		$addon_attr .= ' data-jwpf-wow-delay="' . $options->animationdelay . 'ms"';
	}
}

if (!empty($external_video)) {
	$custom_class .= ' jwpf-row-have-ext-bg';
}

// Top Shape CSS
$top_shape_css = '';
$top_shape_css_sm = '';
$top_shape_css_xs = '';

if (isset($options->shape_width) && is_object($options->shape_width)) {
	$top_shape_css .= (isset($options->shape_width->md) && !empty($options->shape_width->md)) ? 'width:' . $options->shape_width->md . '%;max-width:' . $options->shape_width->md . '%;' : '';
	$top_shape_css_sm .= (isset($options->shape_width->sm) && !empty($options->shape_width->sm)) ? 'width:' . $options->shape_width->sm . '%;max-width:' . $options->shape_width->sm . '%;' : '';
	$top_shape_css_xs .= (isset($options->shape_width->xs) && !empty($options->shape_width->xs)) ? 'width:' . $options->shape_width->xs . '%;max-width:' . $options->shape_width->xs . '%;' : '';
}

if (isset($options->shape_height) && is_object($options->shape_height)) {
	$top_shape_css .= (isset($options->shape_height->md) && !empty($options->shape_height->md)) ? 'height:' . $options->shape_height->md . 'px;' : '';
	$top_shape_css_sm .= (isset($options->shape_height->sm) && !empty($options->shape_height->sm)) ? 'height:' . $options->shape_height->sm . 'px;' : '';
	$top_shape_css_xs .= (isset($options->shape_height->xs) && !empty($options->shape_height->xs)) ? 'height:' . $options->shape_height->xs . 'px;' : '';
}

$top_shape_path_css = (isset($options->shape_color) && !empty($options->shape_color)) ? 'fill:' . $options->shape_color . ';' : '';

if (isset($options->show_top_shape) && $options->show_top_shape && !empty($top_shape_path_css)) {
	$doc->addStyledeclaration('#' . $row_id . ' .jwpf-shape-container.jwpf-top-shape > svg path, #' . $row_id . ' .jwpf-shape-container.jwpf-top-shape > svg polygon{' . $top_shape_path_css . '}');
}

if (isset($options->show_top_shape) && $options->show_top_shape && !empty($top_shape_css)) {
	$doc->addStyledeclaration('#' . $row_id . ' .jwpf-shape-container.jwpf-top-shape > svg{' . $top_shape_css . '}');
}

if (isset($options->show_top_shape) && $options->show_top_shape && !empty($top_shape_css_sm)) {
	$doc->addStyledeclaration('@media (min-width: 768px) and (max-width: 991px) { #' . $row_id . ' .jwpf-shape-container.jwpf-top-shape > svg{' . $top_shape_css_sm . '} }');
}
if (isset($options->show_top_shape) && $options->show_top_shape && !empty($top_shape_css_xs)) {
	$doc->addStyledeclaration('@media (max-width: 767px) { #' . $row_id . ' .jwpf-shape-container.jwpf-top-shape > svg{' . $top_shape_css_xs . '} }');
}

// Top Shape CSS
$bottom_shape_css = '';
$bottom_shape_css_sm = '';
$bottom_shape_css_xs = '';

if (isset($options->bottom_shape_width) && is_object($options->bottom_shape_width)) {
	$bottom_shape_css .= (isset($options->bottom_shape_width->md) && !empty($options->bottom_shape_width->md)) ? 'width:' . $options->bottom_shape_width->md . '%;max-width:' . $options->bottom_shape_width->md . '%;' : '';
	$bottom_shape_css_sm .= (isset($options->bottom_shape_width->sm) && !empty($options->bottom_shape_width->sm)) ? 'width:' . $options->bottom_shape_width->sm . '%;max-width:' . $options->bottom_shape_width->sm . '%;' : '';
	$bottom_shape_css_xs .= (isset($options->bottom_shape_width->xs) && !empty($options->bottom_shape_width->xs)) ? 'width:' . $options->bottom_shape_width->xs . '%;max-width:' . $options->bottom_shape_width->xs . '%;' : '';
}

if (isset($options->bottom_shape_height) && is_object($options->bottom_shape_height)) {
	$bottom_shape_css .= (isset($options->bottom_shape_height->md) && !empty($options->bottom_shape_height->md)) ? 'height:' . $options->bottom_shape_height->md . 'px;' : '';
	$bottom_shape_css_sm .= (isset($options->bottom_shape_height->sm) && !empty($options->bottom_shape_height->sm)) ? 'height:' . $options->bottom_shape_height->sm . 'px;' : '';
	$bottom_shape_css_xs .= (isset($options->bottom_shape_height->xs) && !empty($options->bottom_shape_height->xs)) ? 'height:' . $options->bottom_shape_height->xs . 'px;' : '';
}

$bottom_shape_path_css = (isset($options->bottom_shape_color) && !empty($options->bottom_shape_color)) ? 'fill:' . $options->bottom_shape_color . ';' : '';

if (isset($options->show_bottom_shape) && $options->show_bottom_shape && !empty($bottom_shape_path_css)) {
	$doc->addStyledeclaration('#' . $row_id . ' .jwpf-shape-container.jwpf-bottom-shape > svg path, #' . $row_id . ' .jwpf-shape-container.jwpf-bottom-shape > svg polygon{' . $bottom_shape_path_css . '}');
}

if (isset($options->show_bottom_shape) && $options->show_bottom_shape && !empty($bottom_shape_css)) {
	$doc->addStyledeclaration('#' . $row_id . ' .jwpf-shape-container.jwpf-bottom-shape > svg{' . $bottom_shape_css . '}');
}

if (isset($options->show_bottom_shape) && $options->show_bottom_shape && !empty($bottom_shape_css_sm)) {
	$doc->addStyledeclaration('@media (min-width: 768px) and (max-width: 991px) { #' . $row_id . ' .jwpf-shape-container.jwpf-bottom-shape > svg{' . $bottom_shape_css_sm . '} }');
}
if (isset($options->show_bottom_shape) && $options->show_bottom_shape && !empty($bottom_shape_css_xs)) {
	$doc->addStyledeclaration('@media (max-width: 767px) { #' . $row_id . ' .jwpf-shape-container.jwpf-bottom-shape > svg{' . $bottom_shape_css_xs . '} }');
}

// Video
$video_loop = '';
if (isset($options->video_loop) && $options->video_loop == true) {
	$video_loop = 'loop';
} else {
	$video_loop = '';
}

$video_params = '';
$video_poster = '';
$mp4_url = '';
$ogv_url = '';

$external_background_video = 0;

if (isset($options->external_background_video) && $options->external_background_video) {
	$external_background_video = $options->external_background_video;
}

if (isset($options->background_type)) {
	if ($options->background_type == 'video' && !$external_background_video) {
		if (isset($options->background_image) && $options->background_image) {
			if (strpos($options->background_image, "http://") !== false || strpos($options->background_image, "https://") !== false) {
				$video_poster .= $options->background_image;
				$video_params .= $video_loop;
			} else {
				$video_poster .= JURI::base(true) . '/' . $options->background_image;
			}
		}

		if (isset($options->background_video_mp4) && $options->background_video_mp4) {
			$mp4_parsed = parse_url($options->background_video_mp4);
			$mp4_url = (isset($mp4_parsed['host']) && $mp4_parsed['host']) ? $options->background_video_mp4 : JURI::base(true) . '/' . $options->background_video_mp4;
		}

		if (isset($options->background_video_ogv) && $options->background_video_ogv) {
			$ogv_parsed = parse_url($options->background_video_ogv);
			$ogv_url = (isset($ogv_parsed['host']) && $ogv_parsed['host']) ? $options->background_video_ogv : JURI::base(true) . '/' . $options->background_video_ogv;

		}
	}

} else {
	if (isset($options->background_video) && $options->background_video && !$external_background_video) {
		if (isset($options->background_image) && $options->background_image) {
			if (strpos($options->background_image, "http://") !== false || strpos($options->background_image, "https://") !== false) {
				$video_poster .= $options->background_image;
				$video_params .= $video_loop;
			} else {
				$video_poster .= JURI::base(true) . '/' . $options->background_image;
				$video_params .= $video_loop;
			}
		}

		if (isset($options->background_video_mp4) && $options->background_video_mp4) {
			$mp4_parsed = parse_url($options->background_video_mp4);
			$mp4_url = (isset($mp4_parsed['host']) && $mp4_parsed['host']) ? $options->background_video_mp4 : JURI::base(true) . '/' . $options->background_video_mp4;
		}

		if (isset($options->background_video_ogv) && $options->background_video_ogv) {
			$ogv_parsed = parse_url($options->background_video_ogv);
			$ogv_url = (isset($ogv_parsed['host']) && $ogv_parsed['host']) ? $options->background_video_ogv : JURI::base(true) . '/' . $options->background_video_ogv;

		}
	}
}

$parallax_params = '';
if ($background_parallax && isset($options->background_image) && $options->background_image) {
	$parallax_params = ' data-jwpf-parallax="on"';
}

$video_content = '';
if (isset($options->background_type)) {
	if (!empty($external_video) && $options->external_background_video && $options->background_type == 'video') {
		$video = parse_url($external_video);
		$src = '';
		$vidId = '';
		switch ($video['host']) {
			case 'youtu.be':
				$id = trim($video['path'], '/');
				$src = '//www.youtube.com/embed/' . $id . '?playlist=' . $id . '&iv_load_policy=3&enablejsapi=1&disablekb=1&autoplay=1&controls=0&showinfo=0&rel=0&loop=1&wmode=transparent&widgetid=1&mute=1';
				break;

			case 'www.youtube.com':
			case 'youtube.com':
				parse_str($video['query'], $query);
				$id = $query['v'];
				$src = '//www.youtube.com/embed/' . $id . '?playlist=' . $id . '&iv_load_policy=3&enablejsapi=1&disablekb=1&autoplay=1&controls=0&showinfo=0&rel=0&loop=1&wmode=transparent&widgetid=1&mute=1';
				break;
			case 'vimeo.com':
			case 'www.vimeo.com':
				$id = trim($video['path'], '/');
				$src = "//player.vimeo.com/video/{$id}?background=1&autoplay=1&loop=1&title=0&byline=0&portrait=0";
		}
		$video_content .= '<div class="jwpf-youtube-video-bg hidden"><iframe class="jwpf-youtube-iframe" ' . ($lazyload ? 'data-src="' . $src . '"' : 'src="' . $src . '"') . ' frameborder="0" allowfullscreen></iframe></div>';
	}
} else {
	if (!empty($external_video) && $options->external_background_video && $options->background_video) {
		$video = parse_url($external_video);
		$src = '';
		switch ($video['host']) {
			case 'youtu.be':
				$id = trim($video['path'], '/');
				$src = '//www.youtube.com/embed/' . $id . '?playlist=' . $id . '&iv_load_policy=3&enablejsapi=1&disablekb=1&autoplay=1&controls=0&showinfo=0&rel=0&loop=1&wmode=transparent&widgetid=1&mute=1';
				break;

			case 'www.youtube.com':
			case 'youtube.com':
				parse_str($video['query'], $query);
				$id = $query['v'];
				$src = '//www.youtube.com/embed/' . $id . '?playlist=' . $id . '&iv_load_policy=3&enablejsapi=1&disablekb=1&autoplay=1&controls=0&showinfo=0&rel=0&loop=1&wmode=transparent&widgetid=1&mute=1';
				break;
			case 'vimeo.com':
			case 'www.vimeo.com':
				$id = trim($video['path'], '/');
				$src = "//player.vimeo.com/video/{$id}?background=1&autoplay=1&loop=1&title=0&byline=0&portrait=0";
		}
		$video_content .= '<div class="jwpf-youtube-video-bg hidden"><iframe class="jwpf-youtube-iframe" ' . ($lazyload ? 'data-src="' . $src . '"' : 'src="' . $src . '"') . ' frameborder="0" allowfullscreen></iframe></div>';
	}
}

$shape_content = '';
if (isset($options->show_top_shape) && $options->show_top_shape && isset($options->shape_name) && $options->shape_name) {
	$shape_class = '';
	$shape_code = '';
	$shape_invert = isset($options->shape_invert) && $options->shape_invert ? 1 : 0;
	if (class_exists('JwpagefactoryHelperSite')) {
		$shape_code = JwpagefactoryHelperSite::getSvgShapeCode($options->shape_name, $shape_invert);
	}

	if (isset($options->shape_flip) && $options->shape_flip) {
		$shape_class .= ' jwpf-shape-flip';
	}

	if (isset($options->shape_invert) && $options->shape_invert && !empty($shape_code)) {
		$shape_class .= ' jwpf-shape-invert';
	}

	if (isset($options->shape_to_front) && $options->shape_to_front) {
		$shape_class .= ' jwpf-shape-to-front';
	}

	$shape_content .= '<div class="jwpf-shape-container jwpf-top-shape ' . $shape_class . '">';
	$shape_content .= $shape_code;

	$shape_content .= '</div>';
}

if (isset($options->show_bottom_shape) && $options->show_bottom_shape && isset($options->bottom_shape_name) && $options->bottom_shape_name) {
	$bottom_shape_class = '';
	$bottom_shape_code = '';
	$bottom_shape_invert = isset($options->bottom_shape_invert) && $options->bottom_shape_invert ? 1 : 0;
	if (class_exists('JwpagefactoryHelperSite')) {
		$bottom_shape_code = JwpagefactoryHelperSite::getSvgShapeCode($options->bottom_shape_name, $bottom_shape_invert);
	}

	if (isset($options->bottom_shape_flip) && $options->bottom_shape_flip) {
		$bottom_shape_class .= ' jwpf-shape-flip';
	}

	if (isset($options->bottom_shape_invert) && $options->bottom_shape_invert && !empty($bottom_shape_code)) {
		$bottom_shape_class .= ' jwpf-shape-invert';
	}

	if (isset($options->bottom_shape_to_front) && $options->bottom_shape_to_front) {
		$bottom_shape_class .= ' jwpf-shape-to-front';
	}

	$shape_content .= '<div class="jwpf-shape-container jwpf-bottom-shape ' . $bottom_shape_class . '">';
	$shape_content .= $bottom_shape_code;
	$shape_content .= '</div>';
}

$html = '';

if (!$fluid_row) {
	$html .= '<section id="' . $row_id . '" class="jwpf-section ' . $custom_class . ' ' . $sec_cont_center . '" ' . $addon_attr . $parallax_params . '>';

	if ($mp4_url || $ogv_url) {
		$html .= '<div class="jwpf-section-background-video">';
		$html .= '<video class="section-bg-video" autoplay muted playsinline ' . $video_loop . '' . $video_params . '' . ($lazyload ? ' data-poster="' . $video_poster . '"' : ' poster="' . $video_poster . '"') . '>';
		if ($mp4_url) {
			$html .= '<source ' . ($lazyload ? 'data-large="' . $mp4_url . '"' : 'src="' . $mp4_url . '"') . ' type="video/mp4">';
		}
		if ($ogv_url) {
			$html .= '<source ' . ($lazyload ? 'data-large="' . $ogv_url . '"' : 'src="' . $ogv_url . '"') . ' type="video/ogg">';
		}
		$html .= '</video>';
		$html .= '</div>';
	}
	//When there was no gradient or pattern overlay after adding those option need Backward Compatiblity for pervious color overlay
	if (isset($options->overlay) && $options->overlay) {
		$options->overlay_type = 'overlay_color';
	}
	if ($shape_content) {
		$html .= $shape_content;
	}
	if ($video_content) {
		$html .= $video_content;
	}
	if (isset($options->overlay_type) && $options->overlay_type !== 'overlay_none') {
		$html .= '<div class="jwpf-row-overlay"></div>';
	}
	$html .= '<div class="jwpf-row-container">';
} else {
	$html .= '<div id="' . $row_id . '" class="jwpf-section ' . $custom_class . ' ' . $sec_cont_center . '" ' . $addon_attr . $parallax_params . '>';

	if ($mp4_url || $ogv_url) {
		$html .= '<div class="jwpf-section-background-video">';
		$html .= '<video class="section-bg-video" autoplay muted playsinline ' . $video_loop . '' . $video_params . '' . ($lazyload ? ' data-poster="' . $video_poster . '"' : ' poster="' . $video_poster . '"') . '>';
		if ($mp4_url) {
			$html .= '<source ' . ($lazyload ? 'data-large="' . $mp4_url . '"' : 'src="' . $mp4_url . '"') . ' type="video/mp4">';
		}
		if ($ogv_url) {
			$html .= '<source ' . ($lazyload ? 'data-large="' . $ogv_url . '"' : 'src="' . $ogv_url . '"') . ' type="video/ogg">';
		}
		$html .= '</video>';
		$html .= '</div>';
	}
	//When theere was no gradient or pattern overlay after adding those option need Backward Compatiblity for pervious color overlay
	if (isset($options->overlay) && $options->overlay) {
		$options->overlay_type = 'overlay_color';
	}
	if ($shape_content) {
		$html .= $shape_content;
	}
	if ($video_content) {
		$html .= $video_content;
	}
	if (isset($options->overlay_type) && $options->overlay_type !== 'overlay_none') {
		$html .= '<div class="jwpf-row-overlay"></div>';
	}
	$html .= '<div class="jwpf-container-inner">';
}

// Row Title
if ((isset($options->title) && $options->title) || (isset($options->subtitle) && $options->subtitle)) {
	$title_position = '';
	if (isset($options->title_position) && $options->title_position) {
		$title_position = $options->title_position;
	}

	if ($fluid_row) {
		$html .= '<div class="jwpf-container">';
	}
	$html .= '<div class="jwpf-section-title ' . $title_position . '">';

	if (isset($options->title) && $options->title) {
		$heading_selector = 'h2';
		if (isset($options->heading_selector) && $options->heading_selector) {
			$heading_selector = $options->heading_selector;
		}
		$html .= '<' . $heading_selector . ' class="jwpf-title-heading">' . $options->title . '</' . $heading_selector . '>';
	}

	if (isset($options->subtitle) && $options->subtitle) {
		$html .= '<p class="jwpf-title-subheading">' . $options->subtitle . '</p>';
	}
	$html .= '</div>';

	if ($fluid_row) {
		$html .= '</div>';
	}
}

$html .= '<div class="jwpf-row' . $row_class . '">';

echo $html;
