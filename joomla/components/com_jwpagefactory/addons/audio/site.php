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

class JwpagefactoryAddonAudio extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$style = (isset($settings->style) && $settings->style) ? $settings->style : 'panel-default';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		// Addon options
		$mp3_link = (isset($settings->mp3_link) && $settings->mp3_link) ? $settings->mp3_link : '';
		$ogg_link = (isset($settings->ogg_link) && $settings->ogg_link) ? $settings->ogg_link : '';
		$autoplay = (isset($settings->autoplay) && $settings->autoplay) ? $settings->autoplay : 0;
		$repeat = (isset($settings->repeat) && $settings->repeat) ? $settings->repeat : 0;

		$output = '<div class="jwpf-addon jwpf-addon-audio ' . $class . '">';

		if ($title) {
			$output .= '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>';
		}

		$output .= '<div class="jwpf-addon-content">';
		$output .= '<audio controls ' . $autoplay . ' ' . $repeat . '>';
		$output .= '<source src="' . $mp3_link . '" type="audio/mp3">';
		$output .= '<source src="' . $ogg_link . '" type="audio/ogg">';
		$output .= 'Your browser does not support the audio element.';
		$output .= '</audio>';
		$output .= '</div>';

		$output .= '</div>';

		return $output;

	}

	public static function getTemplate()
	{
		$output = '
		<div class="jwpf-addon jwpf-addon-audio {{ data.class }}">
			<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="jwpf-addon-title jw-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
			<audio controls {{ data.autoplay }} {{ data.repeat }}>
				<source src=\'{{ data.mp3_link }}\' type="audio/mp3">
				<source src=\'{{ data.ogg_link }}\' type="audio/ogg">
			</audio>
		</div>';

		return $output;
	}
}
