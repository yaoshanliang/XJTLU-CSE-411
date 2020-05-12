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

class JwpagefactoryAddonClients extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? ' ' . $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$alignment = (isset($settings->alignment) && $settings->alignment) ? ' ' . $settings->alignment : '';
		$columns = (isset($settings->count) && $settings->count) ? $settings->count : 2;
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';
		//Carousel
		$create_carousel = (isset($settings->create_carousel) && $settings->create_carousel) ? $settings->create_carousel : 0;
		$carousel_autoplay = (isset($settings->carousel_autoplay) && $settings->carousel_autoplay) ? $settings->carousel_autoplay : 0;
		$carousel_speed = (isset($settings->carousel_speed) && $settings->carousel_speed) ? $settings->carousel_speed : 2000;
		$carousel_interval = (isset($settings->carousel_interval) && $settings->carousel_interval) ? $settings->carousel_interval : 3500;
		$carousel_margin = (isset($settings->carousel_margin) && $settings->carousel_margin) ? $settings->carousel_margin : 20;
		$carousel_item_number = (isset($settings->carousel_item_number) && $settings->carousel_item_number) ? $settings->carousel_item_number : 4;
		$carousel_item_number_sm = (isset($settings->carousel_item_number_sm) && $settings->carousel_item_number_sm) ? $settings->carousel_item_number_sm : 3;
		$carousel_item_number_xs = (isset($settings->carousel_item_number_xs) && $settings->carousel_item_number_xs) ? $settings->carousel_item_number_xs : 2;
		$carousel_bullet = (isset($settings->carousel_bullet) && $settings->carousel_bullet) ? $settings->carousel_bullet : 0;
		$carousel_arrow = (isset($settings->carousel_arrow) && $settings->carousel_arrow) ? $settings->carousel_arrow : 0;

		$output = '';
		$output = '<div class="jwpf-addon jwpf-addon-clients' . $alignment . '' . $class . '' . ($create_carousel ? ' jwpf-carousel-extended' : '') . '"  data-arrow="' . $carousel_arrow . '" data-left-arrow="fa-angle-left" data-right-arrow="fa-angle-right" data-dots="' . $carousel_bullet . '" data-autoplay="' . $carousel_autoplay . '" data-speed="' . $carousel_speed . '" data-interval="' . $carousel_interval . '" data-margin="' . $carousel_margin . '" data-item-number="' . $carousel_item_number . '" data-item-number-sm="' . $carousel_item_number_sm . '" data-item-number-xs="' . $carousel_item_number_xs . '">';

		if ($title) {
			$output .= '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>';
		}
		if ($create_carousel) {
			if (isset($settings->jw_clients_item) && is_array($settings->jw_clients_item)) {
				foreach ($settings->jw_clients_item as $item_key => $carousel_item) {
					$output .= '<div class="jwpf-carousel-extended-item">';
					if (isset($carousel_item->url) && $carousel_item->url) $output .= '<a ' . (isset($carousel_item->url_same_window) && $carousel_item->url_same_window ? '' : 'target="_blank" rel="noopener noreferrer"') . ' rel="nofollow" href="' . $carousel_item->url . '">';
					$output .= '<img class="jwpf-img-responsive jwpf-addon-clients-image" src="' . $carousel_item->image . '" alt="' . ((isset($carousel_item->title) && $carousel_item->title) ? $carousel_item->title : '') . '" loading="lazy">';
					if (isset($carousel_item->url) && $carousel_item->url) $output .= '</a>';
					$output .= '</div>';
				}
			}
		} else {
			$output .= '<div class="jwpf-addon-content">';
			$output .= '<div class="jwpf-row">';

			foreach ($settings->jw_clients_item as $key => $value) {
				if (isset($value->image) && $value->image) {
					//Lazyload image
					$dimension = $this->get_image_dimension($value->image);
					$dimension = implode(' ', $dimension);

					if (strpos($value->image, "http://") !== false || strpos($value->image, "https://") !== false) {
						$value->image = $value->image;
					} else {
						$value->image = JURI::base(true) . '/' . $value->image;
					}
					$placeholder = $value->image == '' ? false : $this->get_image_placeholder($value->image);

					$output .= '<div class="' . $columns . '">';
					if (isset($value->url) && $value->url) $output .= '<a ' . (isset($value->url_same_window) && $value->url_same_window ? '' : 'target="_blank" rel="noopener noreferrer"') . ' rel="nofollow" href="' . $value->url . '">';
					$output .= '<img class="jwpf-img-responsive jwpf-addon-clients-image' . ($placeholder ? ' jwpf-element-lazy' : '') . '" src="' . ($placeholder ? $placeholder : $value->image) . '" alt="' . ((isset($value->title) && $value->title) ? $value->title : '') . '" ' . ($placeholder ? 'data-large="' . $value->image . '"' : '') . ' ' . ($dimension ? $dimension : '') . ' loading="lazy">';
					if (isset($value->url) && $value->url) $output .= '</a>';
					$output .= '</div>';
				}
			}

			$output .= '</div>';
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output;
	}

	public function scripts()
	{
		return array(JURI::base(true) . '/components/com_jwpagefactory/assets/js/jw_carousel.js');
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$css = '';
		$remove_filter = (isset($settings->remove_filter) && $settings->remove_filter) ? $settings->remove_filter : '';
		$scale_on_hover = (isset($settings->scale_on_hover) && $settings->scale_on_hover) ? $settings->scale_on_hover : '';
		$scale_value = (isset($settings->scale_value) && $settings->scale_value) ? $settings->scale_value : '';

		$filter_style = '';
		$filter_css = '';
		if (isset($settings->add_css_filder) && is_array($settings->add_css_filder)) {
			foreach ($settings->add_css_filder as $filter_key => $filter_value) {
				if ($filter_value == 'grayscale' && isset($settings->grayscale_value) && $settings->grayscale_value) {
					$filter_css .= 'grayscale(' . $settings->grayscale_value . '%) ';
				}

				if ($filter_value == 'opacity' && isset($settings->opacity_value) && $settings->opacity_value) {
					$filter_css .= 'opacity(' . $settings->opacity_value . '%)';
				}
				$filter_style = 'filter:' . $filter_css . ';';
			}
		}

		if ($filter_style) {
			$css .= $addon_id . ' .jwpf-addon-clients-image {';
			$css .= $filter_style;
			$css .= '}';
		}
		if ($remove_filter) {
			$css .= $addon_id . ' .jwpf-addon-clients-image:hover {';
			$css .= 'filter: none;';
			$css .= '}';
		}
		if ($scale_on_hover && $scale_value) {
			$css .= $addon_id . ' .jwpf-addon-clients-image:hover {';
			$css .= 'transform: scale(' . $scale_value . ');';
			$css .= '}';
		}
		return $css;
	}

	public static function getTemplate()
	{
		$output = '
		<style type="text/css">
			<# 
			let filter_style = "";
			let filter_css = "";
			if(!_.isEmpty(data.add_css_filder) && _.isArray(data.add_css_filder)){
				_.each(data.add_css_filder, function(filter_value){
					if(filter_value == "grayscale" && !_.isEmpty(data.grayscale_value) && data.grayscale_value){
						filter_css += `grayscale(${data.grayscale_value}%) `;
					}
					
					if(filter_value == "opacity" && !_.isEmpty(data.opacity_value) && data.opacity_value){
						filter_css += `opacity(${data.opacity_value}%)`;
					}
					filter_style = `filter:${filter_css};`;
				})
			}
			
			if(data.add_css_filder != ""){
			#>
				#jwpf-addon-{{ data.id }} .jwpf-addon-clients-image {
					{{filter_style}}
				}
			<# }
			if(data.add_css_filder != "" && data.remove_filter){
			#>
				#jwpf-addon-{{ data.id }} .jwpf-addon-clients-image:hover {
					filter: none;
				}
			<# }
			if(data.scale_on_hover && data.scale_value){
			#>
				#jwpf-addon-{{ data.id }} .jwpf-addon-clients-image:hover {
					transform: scale({{data.scale_value}});
				}
			<# } #>
		</style>
		<# 
		let carousel_item_number = 4;
        let carousel_item_number_sm = 3;
        let carousel_item_number_xs = 2;
        if(_.isObject(data.carousel_item_number)){
            carousel_item_number = data.carousel_item_number.md
            carousel_item_number_sm = data.carousel_item_number.sm
            carousel_item_number_xs = data.carousel_item_number.xs
		}
		#>
		<div class="jwpf-addon jwpf-addon-clients {{ data.class }} {{ data.alignment }} {{(data.create_carousel ? \' jwpf-carousel-extended\' : \'\')}}" data-left-arrow="fa-angle-left" data-right-arrow="fa-angle-right" data-arrow="{{data.carousel_arrow}}" data-dots="{{data.carousel_bullet}}" data-autoplay="{{data.carousel_autoplay}}" data-speed="{{data.carousel_speed || 2000}}" data-interval="{{data.carousel_interval ||3500}}" data-margin="{{data.carousel_margin}}" data-item-number="{{carousel_item_number || 4}}" data-item-number-sm="{{carousel_item_number_sm || 3}}" data-item-number-xs="{{carousel_item_number_xs || 2}}">
			<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
			<# if(data.create_carousel) {
				if(typeof data.jw_clients_item !== "undefined" && _.isArray(data.jw_clients_item)){
					_.each(data.jw_clients_item, function(carousel_item){
			#>
						<div class="jwpf-carousel-extended-item">
						<# if(carousel_item.url){ #><a {{(carousel_item.url_same_window ? "" : \'target=_blank\')}} rel="nofollow" href=\'{{carousel_item.url}}\'><# } #>
							<# if(carousel_item.image && carousel_item.image.indexOf("https://") == -1 && carousel_item.image.indexOf("http://") == -1){ #>
								<img class="jwpf-img-responsive jwpf-addon-clients-image" src=\'{{ pagefactory_base + carousel_item.image }}\' alt="{{ carousel_item.title }}">
							<# } else if(carousel_item.image){ #>
								<img class="jwpf-img-responsive jwpf-addon-clients-image" src=\'{{ carousel_item.image }}\' alt="{{ carousel_item.title }}">
							<# } #>
						<# if(carousel_item.url){ #></a><# } #>
						</div>
					<# }); 
				}
			} else { #>
			<div class="jwpf-addon-content">
				<div class="jwpf-row">
					<# _.each(data.jw_clients_item, function(clients_item, key){ #>
						<# if(clients_item.image){ #>
							<div class="{{ data.count }}">
								<# if(clients_item.url){ #><a {{(clients_item.url_same_window ? "" : \'target=_blank\')}} rel="nofollow" href=\'{{clients_item.url}}\'><# } #>
									<# if(clients_item.image && clients_item.image.indexOf("https://") == -1 && clients_item.image.indexOf("http://") == -1){ #>
										<img class="jwpf-img-responsive jwpf-addon-clients-image" src=\'{{ pagefactory_base + clients_item.image }}\' alt="{{ clients_item.title }}">
									<# } else if(clients_item.image){ #>
										<img class="jwpf-img-responsive jwpf-addon-clients-image" src=\'{{ clients_item.image }}\' alt="{{ clients_item.title }}">
									<# } #>
								<# if(clients_item.url){ #></a><# } #>
							</div>
						<# } #>
					<# }); #>
				</div>
			</div>
			<# } #>
		</div>
		';

		return $output;
	}
}
