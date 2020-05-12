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

class JwpagefactoryAddonEmpty_space extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';

		return '<div class="jwpf-empty-space ' . $class . ' clearfix"></div>';
	}

	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$gap = (isset($settings->gap) && $settings->gap) ? 'height: ' . (int)$settings->gap . 'px;' : '';

		$css = '';
		if ($gap) {
			$css .= $addon_id . ' .jwpf-empty-space {';
			$css .= $gap;
			$css .= '}';
		}

		$gap_sm = (isset($settings->gap_sm) && $settings->gap_sm) ? 'height: ' . (int)$settings->gap_sm . 'px;' : '';
		if (!empty($gap_sm)) {
			$css .= '@media (min-width: 768px) and (max-width: 991px) {';
			$css .= $addon_id . ' .jwpf-empty-space {';
			$css .= $gap_sm;
			$css .= '}';
			$css .= '}';
		}

		$gap_xs = (isset($settings->gap_xs) && $settings->gap_xs) ? 'height: ' . (int)$settings->gap_xs . 'px;' : '';
		if (!empty($gap_xs)) {
			$css .= '@media (max-width: 767px) {';
			$css .= $addon_id . ' .jwpf-empty-space {';
			$css .= $gap_xs;
			$css .= '}';
			$css .= '}';
		}

		return $css;
	}

	public static function getTemplate()
	{
		$output = '
		<style type="text/css">
			#jwpf-addon-{{ data.id }} .jwpf-empty-space {
				<# if(_.isObject(data.gap)){ #>
					height: {{ data.gap.md }}px;
				<# } else { #>
					height: {{ data.gap }}px;
				<# } #>
			}

			@media (min-width: 768px) and (max-width: 991px) {
				#jwpf-addon-{{ data.id }} .jwpf-empty-space {
					<# if(_.isObject(data.gap)){ #>
						height: {{ data.gap.sm }}px;
					<# } #>
				}
			}
			@media (max-width: 767px) {
				#jwpf-addon-{{ data.id }} .jwpf-empty-space {
					<# if(_.isObject(data.gap)){ #>
						height: {{ data.gap.xs }}px;
					<# } #>
				}
			}
		</style>
		<div class="jwpf-empty-space jwpf-empty-space-edit {{ data.class }} clearfix"></div>
		';

		return $output;
	}

}
