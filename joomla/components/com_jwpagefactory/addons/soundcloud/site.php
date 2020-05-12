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

class JwpagefactoryAddonSoundcloud extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		//Options
		$embed = (isset($settings->embed) && $settings->embed) ? $settings->embed : '';

		//Output
		if ($embed) {
			$output = '<div class="jwpf-addon jwpf-addon-soundcloud ' . $class . '">';
			$output .= ($title) ? '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';
			$output .= '<div class="jwpf-addon-content">';
			$output .= '<div class="jwpf-embed-responsive jwpf-embed-responsive-16by9">';
			$output .= $embed;
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
				<div class="jwpf-addon jwpf-addon-soundcloud {{ data.class }}">
					<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title  jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{{ data.title }}}</{{ data.heading_selector }}><# } #>
					<div class="jwpf-iframe-drag-overlay"></div>
					<div class="jwpf-addon-content">
						<div class="jwpf-embed-responsive jwpf-embed-responsive-16by9">
							{{{ data.embed }}}
						</div>
					</div>
				</div>
				';

		return $output;
	}

}
