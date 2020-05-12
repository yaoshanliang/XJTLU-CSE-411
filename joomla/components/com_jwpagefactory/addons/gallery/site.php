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

class JwpagefactoryAddonGallery extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';
		$item_alignment = (isset($settings->item_alignment) && $settings->item_alignment) ? $settings->item_alignment : '';

		$output = '<div class="jwpf-addon jwpf-addon-gallery ' . $class . '">';
		$output .= ($title) ? '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';
		$output .= '<div class="jwpf-addon-content">';
		$output .= '<ul class="jwpf-gallery clearfix gallery-item-' . $item_alignment . '">';

		if (isset($settings->jw_gallery_item) && count((array)$settings->jw_gallery_item)) {
			foreach ($settings->jw_gallery_item as $key => $value) {
				if (isset($value->thumb) && $value->thumb) {
					//Lazyload image
					$dimension = $this->get_image_dimension($value->thumb);
					$dimension = implode(' ', $dimension);

					if (strpos($value->thumb, "http://") !== false || strpos($value->thumb, "https://") !== false) {
						$value->thumb = $value->thumb;
					} else {
						$value->thumb = JURI::base(true) . '/' . $value->thumb;
					}
					$placeholder = $value->thumb == '' ? false : $this->get_image_placeholder($value->thumb);

					$output .= '<li>';
					$output .= ($value->full) ? '<a href="' . $value->full . '" class="jwpf-gallery-btn">' : '';
					$output .= '<img class="jwpf-img-responsive' . ($placeholder ? ' jwpf-element-lazy' : '') . '" src="' . ($placeholder ? $placeholder : $value->thumb) . '" alt="' . (isset($value->title) ? $value->title : '') . '" ' . ($placeholder ? 'data-large="' . $value->thumb . '"' : '') . ' ' . ($dimension ? $dimension : '') . ' loading="lazy">';
					$output .= ($value->full) ? '</a>' : '';
					$output .= '</li>';
				}
			}
		}

		$output .= '</ul>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	public function stylesheets()
	{
		return array(JURI::base(true) . '/components/com_jwpagefactory/assets/css/magnific-popup.css');
	}

	public function scripts()
	{
		return array(JURI::base(true) . '/components/com_jwpagefactory/assets/js/jquery.magnific-popup.min.js');
	}

	public function js()
	{
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$js = 'jQuery(function($){
			$("' . $addon_id . ' ul li").magnificPopup({
				delegate: "a",
				type: "image",
				mainClass: "mfp-no-margins mfp-with-zoom",
				gallery:{
					enabled:true
				},
				image: {
					verticalFit: true
				},
				zoom: {
					enabled: true,
					duration: 300
				}
			});
		})';

		return $js;
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;

		$width = (isset($settings->width) && $settings->width) ? $settings->width : '';
		$width_sm = (isset($settings->width_sm) && $settings->width_sm) ? $settings->width_sm : '';
		$width_xs = (isset($settings->width_xs) && $settings->width_xs) ? $settings->width_xs : '';

		$height = (isset($settings->height) && $settings->height) ? $settings->height : '';
		$height_sm = (isset($settings->height_sm) && $settings->height_sm) ? $settings->height_sm : '';
		$height_xs = (isset($settings->height_xs) && $settings->height_xs) ? $settings->height_xs : '';

		$item_gap = (isset($settings->item_gap) && $settings->item_gap) ? $settings->item_gap : '';
		$item_gap_sm = (isset($settings->item_gap_sm) && $settings->item_gap_sm) ? $settings->item_gap_sm : '';
		$item_gap_xs = (isset($settings->item_gap_xs) && $settings->item_gap_xs) ? $settings->item_gap_xs : '';

		$css = '';
		if ($width || $height || $item_gap) {
			$css .= $addon_id . ' .jwpf-gallery img {';
			$css .= 'width:' . $width . 'px;';
			$css .= 'height:' . $height . 'px;';
			$css .= '}';

			if (!empty($item_gap)) {
				$css .= $addon_id . ' .jwpf-gallery li {';
				$css .= 'margin:' . $item_gap . 'px;';
				$css .= '}';
				$css .= $addon_id . ' .jwpf-gallery {';
				$css .= 'margin:-' . $item_gap . 'px;';
				$css .= '}';
			}

			$css .= '@media (min-width: 768px) and (max-width: 991px) {';

			$css .= $addon_id . ' .jwpf-gallery img {';
			$css .= 'width:' . $width_sm . 'px;';
			$css .= 'height:' . $height_sm . 'px;';
			$css .= '}';

			if (!empty($item_gap_sm)) {
				$css .= $addon_id . ' .jwpf-gallery li {';
				$css .= 'margin:' . $item_gap_sm . 'px;';
				$css .= '}';
				$css .= $addon_id . ' .jwpf-gallery {';
				$css .= 'margin:-' . $item_gap_sm . 'px;';
				$css .= '}';
			}

			$css .= '}';

			$css .= '@media (max-width: 767px) {';

			$css .= $addon_id . ' .jwpf-gallery img {';
			$css .= 'width:' . $width_xs . 'px;';
			$css .= 'height:' . $height_xs . 'px;';
			$css .= '}';

			if (!empty($item_gap_xs)) {
				$css .= $addon_id . ' .jwpf-gallery li {';
				$css .= 'margin:' . $item_gap_xs . 'px;';
				$css .= '}';
				$css .= $addon_id . ' .jwpf-gallery {';
				$css .= 'margin:-' . $item_gap_xs . 'px;';
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
            #jwpf-addon-{{ data.id }} .jwpf-gallery img {
                <# if(_.isObject(data.width)){ #>
                    width: {{data.width.md}}px;
                <# } else { #>
                    width: {{data.width}}px;
                <# } #>
                <# if(_.isObject(data.height)){ #>
                    height: {{data.height.md}}px;
                <# } else { #>
                    height: {{data.height}}px;
                <# } #>
            }
            #jwpf-addon-{{ data.id }} .jwpf-gallery li {
                <# if(_.isObject(data.item_gap)){ #>
                    margin: {{data.item_gap.md}}px;
                <# } else { #>
                    margin: {{data.item_gap}}px;
                <# } #>
            }
            #jwpf-addon-{{ data.id }} .jwpf-gallery {
                <# if(_.isObject(data.item_gap)){ #>
                    margin: -{{data.item_gap.md}}px;
                <# } else { #>
                    margin: -{{data.item_gap}}px;
                <# } #>
            }
            @media (min-width: 768px) and (max-width: 991px) {
                #jwpf-addon-{{ data.id }} .jwpf-gallery img {
                    <# if(_.isObject(data.width)){ #>
                        width: {{data.width.sm}}px;
                    <# } #>
                    <# if(_.isObject(data.height)){ #>
                        height: {{data.height.sm}}px;
                    <# } #>
                }
                
                #jwpf-addon-{{ data.id }} .jwpf-gallery li {
                    <# if(_.isObject(data.item_gap)){ #>
                        margin: {{data.item_gap.sm}}px;
                    <# } #>
                }
                #jwpf-addon-{{ data.id }} .jwpf-gallery {
                    <# if(_.isObject(data.item_gap)){ #>
                        margin: -{{data.item_gap.sm}}px;
                    <# } #>
                }
            }
            @media (max-width: 767px) {
                #jwpf-addon-{{ data.id }} .jwpf-gallery img {
                    <# if(_.isObject(data.width)){ #>
                        width: {{data.width.xs}}px;
                    <# } #>
                    <# if(_.isObject(data.height)){ #>
                        height: {{data.height.xs}}px;
                    <# } #>
                }
                
                #jwpf-addon-{{ data.id }} .jwpf-gallery li {
                    <# if(_.isObject(data.item_gap)){ #>
                        margin: {{data.item_gap.xs}}px;
                    <# } #>
                }
                #jwpf-addon-{{ data.id }} .jwpf-gallery {
                    <# if(_.isObject(data.item_gap)){ #>
                        margin: -{{data.item_gap.xs}}px;
                    <# } #>
                }
            }
        </style>
		<div class="jwpf-addon jwpf-addon-gallery {{ data.class }}">
			<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
			<div class="jwpf-addon-content">
				<ul class="jwpf-gallery clearfix gallery-item-{{data.item_alignment}}">
				<# _.each(data.jw_gallery_item, function (value, key) { #>
					<# if(value.thumb) { #>
						<li>
						<# if(value.full && value.full.indexOf("http://") == -1 && value.full.indexOf("https://") == -1){ #>
							<a href=\'{{ pagefactory_base + value.full }}\' class="jwpf-gallery-btn">
						<# } else if(value.full){ #>
							<a href=\'{{ value.full }}\' class="jwpf-gallery-btn">
						<# } #>
							<# if(value.thumb && value.thumb.indexOf("http://") == -1 && value.thumb.indexOf("https://") == -1){ #>
								<img class="jwpf-img-responsive" src=\'{{ pagefactory_base + value.thumb }}\' alt="{{ value.title }}">
							<# } else if(value.thumb){ #>
								<img class="jwpf-img-responsive" src=\'{{ value.thumb }}\' alt="{{ value.title }}">
							<# } #>
						<# if(value.full){ #>
							</a>
						<# } #>
						</li>
					<# } #>
				<# }); #>
				</ul>
			</div>
		</div>
		';

		return $output;
	}

}
