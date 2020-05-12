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

class JwpagefactoryAddonOpenstreetmap extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;

		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';

		//Options
		$map_style = (isset($settings->map_style) && $settings->map_style) ? $settings->map_style : 'Wikimedia';
		$zoom = (isset($settings->zoom) && $settings->zoom) ? $settings->zoom : 0;
		$mousescroll = (isset($settings->mousescroll) && $settings->mousescroll) ? $settings->mousescroll : 0;
		$dragging = (isset($settings->dragging) && $settings->dragging) ? $settings->dragging : 0;
		$zoomcontrol = (isset($settings->zoomcontrol) && $settings->zoomcontrol) ? $settings->zoomcontrol : 0;
		$attribution = (isset($settings->attribution) && $settings->attribution) ? $settings->attribution : 0;

		$total_location = [];
		if ((array)$settings->multi_location_items) {
			foreach ($settings->multi_location_items as $key => $item) {
				$lat_long = explode(',', $item->location_item);
				$address_text = str_replace("'", "&#39;", $item->location_popup_text);
				$custom_icon = '';
				if (isset($item->custom_icon) && $item->custom_icon) {
					if (strpos($item->custom_icon, "http://") !== false || strpos($item->custom_icon, "https://") !== false) {
						$custom_icon = $item->custom_icon;
					} else {
						$custom_icon = JURI::base(true) . '/' . $item->custom_icon;
					}
				}
				$total_location[] = array(
					'address' => $address_text,
					'latitude' => $lat_long[0],
					'longitude' => $lat_long[1],
					'custom_icon' => $custom_icon
				);
			}
		}
		$location_json = json_encode($total_location);

		$output = '';
		if ($location_json) {
			$output .= '<div class="jwpf-addon-openstreetmap-wrapper">';
			$output .= '<div class="jwpf-addon-content">';
			$output .= '<div id="jwpf-addon-osm-' . $this->addon->id . '" class="jwpf-addon-openstreetmap ' . $class . '" data-location=\'' . $location_json . '\' data-mapstyle="' . $map_style . '" data-mapzoom="' . $zoom . '" data-mousescroll="' . $mousescroll . '" data-dragging="' . $dragging . '" data-zoomcontrol="' . $zoomcontrol . '" data-attribution="' . $attribution . '"></div>';
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	public function scripts()
	{
		return array(
			JURI::base(true) . '/components/com_jwpagefactory/assets/js/leaflet.js',
			JURI::base(true) . '/components/com_jwpagefactory/assets/js/leaflet.provider.js',
		);
	}

	public function stylesheets()
	{
		return array(JURI::base(true) . '/components/com_jwpagefactory/assets/css/leaflet.css');
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;

		$height = (isset($settings->height) && $settings->height) ? $settings->height : 0;
		$height_sm = (isset($settings->height_sm) && $settings->height_sm) ? $settings->height_sm : 0;
		$height_xs = (isset($settings->height_xs) && $settings->height_xs) ? $settings->height_xs : 0;

		$css = '';
		if ($height) {
			$css .= $addon_id . ' .jwpf-addon-openstreetmap {';
			$css .= 'height:' . (int)$height . 'px;';
			$css .= '}';
		}

		if ($height_sm) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			$css .= $addon_id . ' .jwpf-addon-openstreetmap {';
			$css .= 'height:' . (int)$height_sm . 'px;';
			$css .= '}';
			$css .= '}';
		}

		if ($height_xs) {
			$css .= '@media (max-width: 767px) {';
			$css .= $addon_id . ' .jwpf-addon-openstreetmap {';
			$css .= 'height:' . (int)$height_xs . 'px;';
			$css .= '}';
			$css .= '}';
		}

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
		<#

			let location_addr = [];
			if(_.isObject(data.multi_location_items) && data.multi_location_items){
				_.each(data.multi_location_items, function(item){
					let latLong = _.split(item.location_item, ",");
					let custom_icon = "";
					if(!_.isEmpty(item.custom_icon) && item.custom_icon){
						if(item.custom_icon.indexOf("http://") == 0 || item.custom_icon.indexOf("https://") == 0){
							custom_icon = item.custom_icon;
						} else {
							custom_icon = pagefactory_base + "/" + item.custom_icon;
						}
					}
					let mainObj = [{
						address: item.location_popup_text,
						latitude: latLong[0],
						longitude: latLong[1],
						custom_icon: custom_icon
					}];
					location_addr = _.concat(location_addr, mainObj);
				})
			}

			let location_json = JSON.stringify(location_addr);
			
		#>
		<style type="text/css">
		#jwpf-addon-{{ data.id }} .jwpf-addon-openstreetmap {
			<# if(_.isObject(data.height)){ #>
				height: {{ data.height.md }}px;
			<# } else { #>
				height: {{ data.height }}px;
			<# } #>
		}
		@media (min-width: 768px) and (max-width: 991px) {
			#jwpf-addon-{{ data.id }} .jwpf-addon-openstreetmap {
				<# if(_.isObject(data.height)){ #>
					height: {{ data.height.sm }}px;
				<# } #>
			}
		}
		@media (max-width: 767px) {
			#jwpf-addon-{{ data.id }} .jwpf-addon-openstreetmap {
				<# if(_.isObject(data.height)){ #>
					height: {{ data.height.xs }}px;
				<# } #>
			}
		}
		</style>
		<div class="jwpf-addon-openstreetmap-wrapper edit-view">
			<div class="jwpf-addon-content">
				<div id="jwpf-addon-osm-{{ data.id }}" class="jwpf-addon-openstreetmap {{data.class}}" data-location=\'{{location_json}}\' data-mapstyle="{{data.map_style}}" data-mapzoom="{{data.zoom}}" data-mousescroll="{{data.mousescroll}}" data-dragging="{{data.dragging}}" data-zoomcontrol="{{data.zoomcontrol}}" data-attribution="{{data.attribution}}"></div>
			</div>
		</div>
		';

		return $output;
	}
}
