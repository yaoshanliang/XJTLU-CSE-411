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

class JwpagefactoryAddonAlert extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$type = (isset($settings->alrt_type) && $settings->alrt_type) ? ' jwpf-alert-' . $settings->alrt_type : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : '';
		$close = (isset($settings->close) && $settings->close) ? $settings->close : 0;
		$text = (isset($settings->text) && $settings->text) ? $settings->text : '';

		if ($text) {

			$output = '<div class="jwpf-addon jwpf-addon-alert ' . $class . '">';
			$output .= (!empty($title)) ? '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';
			$output .= '<div class="jwpf-addon-content">';
			$output .= '<div class="jwpf-alert' . $type . ' jwpf-fade in">';
			$output .= ($close) ? '<button type="button" class="jwpf-close" data-dismiss="jwpf-alert" aria-label="alert dismiss"><span aria-hidden="true">&times;</span></button>' : '';
			$output .= $text;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}

		return;
	}

	public static function getTemplate()
	{
		$output = '
		<div class="jwpf-addon jwpf-addon-alert {{ data.class }}">
			<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{{ data.title }}}</{{ data.heading_selector }}><# } #>
			<div class="jwpf-addon-content">
				<div class="jwpf-alert jwpf-alert-{{ data.alrt_type }} jwpf-fade in">
					<# if( data.close ){ #>
						<button type="button" class="jwpf-close"><span aria-hidden="true">&times;</span></button>
					<# } #>
					<div id="addon-text-{{data.id}}" class="jw-editable-content" data-id={{data.id}} data-fieldName="text">{{{ data.text }}}</div>
				</div>
			</div>
		</div>';
		return $output;
	}
}
