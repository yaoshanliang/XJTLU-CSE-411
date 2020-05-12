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

$row_settings = array(
	'type' => 'content',
	'title' => 'Section',
	'attr' => array(
		'general' => array(
			'admin_label' => array(
				'type' => 'text',
				'title' => JText::_('COM_JWPAGEFACTORY_ADMIN_LABEL'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ADMIN_LABEL_DESC'),
				'std' => ''
			),

			'separator1' => array(
				'type' => 'separator',
				'title' => JText::_('Title Options')
			),

			'title' => array(
				'type' => 'textarea',
				'title' => JText::_('COM_JWPAGEFACTORY_SECTION_TITLE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_SECTION_TITLE_DESC'),
				'css' => 'min-height: 80px;',
				'std' => ''
			),

			'heading_selector' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HEADINGS'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HEADINGS_DESC'),
				'values' => array(
					'h1' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HEADINGS_H1'),
					'h2' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HEADINGS_H2'),
					'h3' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HEADINGS_H3'),
					'h4' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HEADINGS_H4'),
					'h5' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HEADINGS_H5'),
					'h6' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HEADINGS_H6'),
				),
				'std' => 'h3',
				'depends' => array(
					array('title', '!=', ''),
				),
			),

			'title_fontsize' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_TITLE_FONT_SIZE'),
				'std' => '',
				'depends' => array(
					array('title', '!=', ''),
				),
				'responsive' => true,
				'max' => 500
			),

			'title_fontweight' => array(
				'type' => 'text',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_TITLE_FONT_WEIGHT'),
				'std' => '',
				'depends' => array(
					array('title', '!=', ''),
				),
			),

			'title_text_color' => array(
				'type' => 'color',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_TITLE_TEXT_COLOR'),
				'depends' => array(
					array('title', '!=', ''),
				),
			),

			'title_margin_top' => array(
				'type' => 'number',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MARGIN_TOP'),
				'placeholder' => '10',
				'depends' => array(
					array('title', '!=', ''),
				),
				'responsive' => true
			),

			'title_margin_bottom' => array(
				'type' => 'number',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MARGIN_BOTTOM'),
				'placeholder' => '10',
				'depends' => array(
					array('title', '!=', ''),
				),
				'responsive' => true
			),

			'separator2' => array(
				'type' => 'separator',
				'title' => JText::_('Subtitle Options')
			),

			'subtitle' => array(
				'type' => 'textarea',
				'title' => JText::_('COM_JWPAGEFACTORY_SECTION_SUBTITLE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_SECTION_SUBTITLE_DESC'),
				'css' => 'min-height: 120px;',
			),

			'subtitle_fontsize' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_SUB_TITLE_FONT_SIZE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_SUB_TITLE_FONT_SIZE_DESC'),
				'responsive' => true,
				'depends' => array(
					array('subtitle', '!=', ''),
				),
			),

			'title_position' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_TITLE_SUBTITLE_POSITION'),
				'desc' => JText::_('COM_JWPAGEFACTORY_TITLE_SUBTITLE_POSITION_DESC'),
				'values' => array(
					'jwpf-text-left' => JText::_('COM_JWPAGEFACTORY_LEFT'),
					'jwpf-text-center' => JText::_('COM_JWPAGEFACTORY_CENTER'),
					'jwpf-text-right' => JText::_('COM_JWPAGEFACTORY_RIGHT')
				),
				'std' => 'jwpf-text-center',
			),

			'columns_align_center' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_COLUMNS_ALIGN_CENTER'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_COLUMNS_ALIGN_CENTER_DESC'),
				'std' => 0
			),

			'columns_content_alignment' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_ADDON_GLOBAL_CONTENT_ALIGNMENT'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_GLOBAL_CONTENT_ALIGNMENT_DESC'),
				'values' => array(
					'top' => JText::_('COM_JWPAGEFACTORY_ADDON_OPTIN_POSITION_TOP'),
					'center' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CENTER'),
					'bottom' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BOTTOM'),
				),
				'std' => 'center',
				'depends' => array(
					array('columns_align_center', '!=', 0)
				),
			),

			'fullscreen' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_FULLSCREEN'),
				'desc' => JText::_('COM_JWPAGEFACTORY_FULLSCREEN_DESC'),
				'std' => 0,
			),

			'no_gutter' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_NO_GUTTER'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_NO_GUTTER_DESC'),
				'std' => 0,
			),

			'container_width' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CONTAINER_WIDTH'),
				'unit' => true,
				'max' => 3000,
				'min' => 0,
				'responsive' => true,
				'std' => array('unit' => 'px'),
				'depends' => array(
					array('fullscreen', '=', 0),
				),
			),

			'container_max_width' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MAX_CONTAINER_WIDTH'),
				'unit' => true,
				'max' => 3000,
				'min' => 0,
				'responsive' => true,
				'std' => array('unit' => 'px'),
				'depends' => array(
					array('fullscreen', '=', 0),
				),
			),

			'container_min_width' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MIN_CONTAINER_WIDTH'),
				'unit' => true,
				'max' => 3000,
				'min' => 0,
				'responsive' => true,
				'std' => array('unit' => 'px'),
				'depends' => array(
					array('fullscreen', '=', 0),
				),
			),

			'id' => array(
				'type' => 'text',
				'title' => JText::_('COM_JWPAGEFACTORY_SECTION_ID'),
				'desc' => JText::_('COM_JWPAGEFACTORY_SECTION_ID_DESC')
			),

			'class' => array(
				'type' => 'text',
				'title' => JText::_('COM_JWPAGEFACTORY_CSS_CLASS'),
				'desc' => JText::_('COM_JWPAGEFACTORY_CSS_CLASS_DESC')
			),

		),

		'style' => array(

			'height_separator' => array(
				'type' => 'separator',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_HEIGHT_SETTIINGS'),
			),

			'section_height_option' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_HEIGHT_SELECTOR'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_HEIGHT_SELECTOR_DESC'),
				'values' => array(
					'win-height' => JText::_('COM_JWPAGEFACTORY_ROW_WIN_HEIGHT'),
					'height' => JText::_('COM_JWPAGEFACTORY_ROW_HEIGHT'),
				),
			),

			'section_height' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_HEIGHT_OPTION'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_HEIGHT_OPTION_DESC'),
				'depends' => array(
					array('section_height_option', '=', 'height'),
				),
				'max' => 3000,
				'responsive' => true,
			),

			'section_min_height' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_MIN_HEIGHT_OPTION'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_MIN_HEIGHT_OPTION_DESC'),
				'max' => 3000,
				'responsive' => true,
			),

			'section_max_height' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_MAX_HEIGHT_OPTION'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_MAX_HEIGHT_OPTION_DESC'),
				'max' => 3000,
				'responsive' => true,
			),

			'section_overflow_x' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_OVERFLOW_X'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_OVERFLOW_X_DESC'),
				'depends' => array(
					array('section_height_option', '=', 'height'),
				),
				'values' => array(
					'auto' => 'Auto',
					'hidden' => 'Hidden',
					'initial' => 'Initial',
					'scroll' => 'Scroll',
				),
			),

			'section_overflow_y' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_OVERFLOW_Y'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_OVERFLOW_Y_DESC'),
				'depends' => array(
					array('section_height_option', '=', 'height'),
				),
				'values' => array(
					'auto' => 'Auto',
					'hidden' => 'Hidden',
					'initial' => 'Initial',
					'scroll' => 'Scroll',
				),
			),

			'width_separator' => array(
				'type' => 'separator',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_WIDTH_SETTINGS'),
			),

			'row_width' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_WIDTH'),
				'unit' => true,
				'max' => 3000,
				'min' => 0,
				'responsive' => true,
				'std' => array('unit' => 'px')
			),

			'row_max_width' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MAX_WIDTH'),
				'unit' => true,
				'max' => 3000,
				'min' => 0,
				'responsive' => true,
				'std' => array('unit' => 'px')
			),

			'row_min_width' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MIN_WIDTH'),
				'unit' => true,
				'max' => 3000,
				'min' => 0,
				'responsive' => true,
				'std' => array('unit' => 'px')
			),

			'other_separator' => array(
				'type' => 'separator',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_OTHER_SETTINGS'),
			),

			'padding' => array(
				'type' => 'padding',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_PADDING'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_PADDING_DESC'),
				'std' => '50px 0px 50px 0px',
				'placeholder' => '10px 10px 10px 10px',
				'responsive' => true
			),

			'margin' => array(
				'type' => 'margin',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MARGIN'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_MARGIN_DESC'),
				'std' => '0px 0px 0px 0px',
				'placeholder' => '10px 10px 10px 10px',
				'responsive' => true
			),

			'color' => array(
				'type' => 'color',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_TEXT_COLOR'),
			),

			'background_type' => array(
				'type' => 'buttons',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_ENABLE_BACKGROUND_OPTIONS'),
				'std' => 'none',
				'values' => array(
					array(
						'label' => 'None',
						'value' => 'none'
					),
					array(
						'label' => 'Color',
						'value' => 'color'
					),
					array(
						'label' => 'Image',
						'value' => 'image'
					),
					array(
						'label' => 'Gradient',
						'value' => 'gradient'
					),
					array(
						'label' => 'Video',
						'value' => 'video'
					),
				)
			),

			'background_color' => array(
				'type' => 'color',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_COLOR'),
				'depends' => array(
					array('background_type', '!=', 'none'),
					array('background_type', '!=', 'video'),
					array('background_type', '!=', 'gradient')
				)
			),

			'background_gradient' => array(
				'type' => 'gradient',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_GRADIENT'),
				'std' => array(
					"color" => "#00c6fb",
					"color2" => "#005bea",
					"deg" => "45",
					"type" => "linear"
				),
				'depends' => array(
					array('background_type', '=', 'gradient')
				)
			),

			'background_image' => array(
				'type' => 'media',
				'format' => 'image',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_IMAGE'),
				'std' => '',
				'show_input' => true,
				'depends' => array(
					array('background_type', '=', 'image')
				)
			),

			'background_parallax' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_BACKGROUND_PARALLAX_ENABLE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_BACKGROUND_PARALLAX_ENABLE_DESC'),
				'std' => '0',
				'depends' => array(
					array('background_type', '=', 'image')
				)
			),

			'background_repeat' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_REPEAT'),
				'values' => array(
					'no-repeat' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_NO_REPEAT'),
					'repeat' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_REPEAT_ALL'),
					'repeat-x' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_REPEAT_HORIZONTALLY'),
					'repeat-y' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_REPEAT_VERTICALLY'),
					'inherit' => JText::_('COM_JWPAGEFACTORY_GLOBAL_INHERIT'),
				),
				'std' => 'no-repeat',
				'depends' => array(
					array('background_type', '=', 'image'),
					array('background_image', '!=', '')
				)
			),

			'background_size' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_SIZE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_SIZE_DESC'),
				'values' => array(
					'cover' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_SIZE_COVER'),
					'contain' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_SIZE_CONTAIN'),
					'inherit' => JText::_('COM_JWPAGEFACTORY_GLOBAL_INHERIT'),
					'custom' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CUSTOM'),
				),
				'std' => 'cover',
				'depends' => array(
					array('background_type', '=', 'image'),
					array('background_image', '!=', '')
				)
			),

			'background_size_custom' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_BACKROUND_CUSTOM_SIZE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_BACKROUND_CUSTOM_SIZE_DESC'),
				'unit' => true,
				'max' => 3000,
				'min' => 0,
				'depends' => array(
					array('background_size', '=', 'custom'),
					array('background_image', '!=', '')
				),
				'responsive' => true,
				'std' => array('unit' => 'px')
			),

			'background_attachment' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_ATTACHMENT'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_ATTACHMENT_DESC'),
				'values' => array(
					'fixed' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_ATTACHMENT_FIXED'),
					'scroll' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_ATTACHMENT_SCROLL'),
					'inherit' => JText::_('COM_JWPAGEFACTORY_GLOBAL_INHERIT'),
				),
				'std' => 'fixed',
				'depends' => array(
					array('background_type', '=', 'image'),
					array('background_image', '!=', '')
				)
			),

			'background_position' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_POSITION'),
				'values' => array(
					'0 0' => JText::_('COM_JWPAGEFACTORY_LEFT_TOP'),
					'0 50%' => JText::_('COM_JWPAGEFACTORY_LEFT_CENTER'),
					'0 100%' => JText::_('COM_JWPAGEFACTORY_LEFT_BOTTOM'),
					'50% 0' => JText::_('COM_JWPAGEFACTORY_CENTER_TOP'),
					'50% 50%' => JText::_('COM_JWPAGEFACTORY_CENTER_CENTER'),
					'50% 100%' => JText::_('COM_JWPAGEFACTORY_CENTER_BOTTOM'),
					'100% 0' => JText::_('COM_JWPAGEFACTORY_RIGHT_TOP'),
					'100% 50%' => JText::_('COM_JWPAGEFACTORY_RIGHT_CENTER'),
					'100% 100%' => JText::_('COM_JWPAGEFACTORY_RIGHT_BOTTOM'),
					'custom' => JText::_('COM_JWPAGEFACTORY_GLOBAL_CUSTOM'),
				),
				'std' => '0 0',
				'depends' => array(
					array('background_type', '=', 'image'),
					array('background_image', '!=', '')
				)
			),

			'background_position_custom_x' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_BACKGROUND_CUSTOM_POSITION_X'),
				'desc' => JText::_('COM_JWPAGEFACTORY_BACKGROUND_CUSTOM_POSITION_X_DESC'),
				'unit' => true,
				'max' => 1000,
				'min' => -1000,
				'depends' => array(
					array('background_position', '=', 'custom'),
					array('background_image', '!=', '')
				),
				'responsive' => true,
				'std' => array('unit' => 'px')
			),

			'background_position_custom_y' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_BACKGROUND_CUSTOM_POSITION_Y'),
				'desc' => JText::_('COM_JWPAGEFACTORY_BACKGROUND_CUSTOM_POSITION_Y_DESC'),
				'unit' => true,
				'depends' => array(
					array('background_position', '=', 'custom'),
					array('background_image', '!=', '')
				),
				'max' => 1000,
				'min' => -1000,
				'responsive' => true,
				'std' => array('unit' => 'px')
			),

			'external_background_video' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_BACKGROUND_EXTERNAL_VIDEO_ENABLE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_BACKGROUND_EXTERNAL_VIDEO_ENABLE_DESC'),
				'std' => '0',
				'depends' => array(
					array('background_type', '=', 'video'),
				)
			),

			'background_video_mp4' => array(
				'type' => 'media',
				'format' => 'video',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_BACKGROUND_VIDEO_MP4'),
				'depends' => array(
					array('background_type', '=', 'video'),
					array('external_background_video', '=', 0)
				)
			),

			'background_video_ogv' => array(
				'type' => 'media',
				'format' => 'video',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_BACKGROUND_VIDEO_OGV'),
				'depends' => array(
					array('background_type', '=', 'video'),
					array('external_background_video', '=', 0)
				)
			),

			'background_external_video' => array(
				'type' => 'text',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_BACKGROUND_VIDEO_YOUTUBE_VIMEO'),
				'depends' => array(
					array('background_type', '=', 'video'),
					array('external_background_video', '=', 1)
				)
			),

			'video_loop' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_VIDEO_LOOP'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_VIDEO_LOOP_DESC'),
				'std' => 1,
				'depends' => array(
					array('background_type', '=', 'video'),
					array('external_background_video', '!=', 1),
				)
			),

			'overlay_separator' => array(
				'type' => 'separator',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_OVERLAY_OPTIONS'),
				'depends' => array(
					array('background_type', '!=', 'none'),
					array('background_type', '!=', 'color'),
					array('background_type', '!=', 'gradient'),
				),
			),

			'overlay_type' => array(
				'type' => 'buttons',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_OVERLAY_CHOOSE'),
				'std' => 'overlay_none',
				'values' => array(
					array(
						'label' => 'None',
						'value' => 'overlay_none'
					),
					array(
						'label' => 'Color',
						'value' => 'overlay_color'
					),
					array(
						'label' => 'Gradient',
						'value' => 'overlay_gradient'
					),
					array(
						'label' => 'Pattern',
						'value' => 'overlay_pattern'
					)
				),
				'depends' => array(
					array('background_type', '!=', 'none'),
					array('background_type', '!=', 'color'),
					array('background_type', '!=', 'gradient'),
				),
			),

			'overlay' => array(
				'type' => 'color',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_OVERLAY'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_OVERLAY_DESC'),
				'depends' => array(
					array('background_type', '!=', 'none'),
					array('background_type', '!=', 'color'),
					array('background_type', '!=', 'gradient'),
					array('overlay_type', '=', 'overlay_color'),
				)
			),

			'gradient_overlay' => array(
				'type' => 'gradient',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_OVERLAY_GRADIENT'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_OVERLAY_GRADIENT_DESC'),
				'std' => array(
					"color" => "rgba(127, 0, 255, 0.8)",
					"color2" => "rgba(225, 0, 255, 0.7)",
					"deg" => "45",
					"type" => "linear"
				),
				'depends' => array(
					array('background_type', '!=', 'none'),
					array('background_type', '!=', 'color'),
					array('background_type', '!=', 'gradient'),
					array('overlay_type', '=', 'overlay_gradient'),
				)
			),

			'pattern_overlay' => array(
				'type' => 'media',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_OVERLAY_PATTERN'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_OVERLAY_PATTERN_DESC'),
				'std' => '',
				'depends' => array(
					array('background_type', '!=', 'none'),
					array('background_type', '!=', 'color'),
					array('background_type', '!=', 'gradient'),
					array('overlay_type', '=', 'overlay_pattern'),
				)
			),

			'overlay_pattern_color' => array(
				'type' => 'color',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_OVERLAY_PATTERN_COLOR'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BACKGROUND_OVERLAY_PATTERN_COLOR_DESC'),
				'std' => '',
				'depends' => array(
					array('background_type', '!=', 'none'),
					array('background_type', '!=', 'color'),
					array('background_type', '!=', 'gradient'),
					array('overlay_type', '=', 'overlay_pattern'),
					array('pattern_overlay', '!=', ''),
				)
			),

			'blend_mode' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BLEND_MODE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BLEND_MODE_DESC'),
				'values' => array(
					'normal' => 'Normal',
					'color' => 'Color',
					'color-burn' => 'Color Burn',
					'color-dodge' => 'Color Dodge',
					'darken' => 'Darken',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hard-light' => 'Hard Light',
					'hue' => 'Hue',
					'lighten' => 'Lighten',
					'luminosity' => 'Luminosity',
					'multiply' => 'Multiply',
					'overlay' => 'Overlay',
					'saturation' => 'Saturation',
					'screen' => 'Screen',
					'soft-light' => 'Soft Light',
				),
				'std' => 'normal',
				'depends' => array(
					array('background_type', '!=', 'none'),
					array('background_type', '!=', 'color'),
					array('background_type', '!=', 'gradient'),
					array('background_type', '!=', 'video'),
					array('overlay_type', '!=', 'overlay_none'),
				)
			),

			'row_border' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_USE_BORDER'),
				'std' => 0
			),

			'row_border_width' => array(
				'type' => 'margin',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_WIDTH'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_WIDTH_DESC'),
				'depends' => array('row_border' => 1),
				'responsive' => true
			),

			'row_border_color' => array(
				'type' => 'color',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_COLOR'),
				'depends' => array('row_border' => 1)
			),

			'row_border_style' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_STYLE'),
				'values' => array(
					'none' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_STYLE_NONE'),
					'solid' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_STYLE_SOLID'),
					'double' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_STYLE_DOUBLE'),
					'dotted' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_STYLE_DOTTED'),
					'dashed' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_STYLE_DASHED'),
				),
				'depends' => array('row_border' => 1),
				'std' => 'solid'
			),

			'row_border_radius' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_RADIUS'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BORDER_RADIUS_ROW_DESC'),
				'std' => 0,
				'max' => 500,
				'responsive' => true
			),

			'row_boxshadow' => array(
				'type' => 'boxshadow',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_BOXSHADOW'),
				'std' => '0 0 0 0 #ffffff'
			),

			'separator_shape_top' => array(
				'type' => 'separator',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_TOP_SHAPE')
			),

			'show_top_shape' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHOW_SHAPE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_SHOW_TOP_SHAPE_DESC'),
				'std' => '',
			),

			'shape_name' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE'),
				'values' => array(
					'bell' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_BELL'),
					'brushed' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_BRUSHED'),
					'clouds-flat' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_CLOUDS_FLAT'),
					'clouds-opacity' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_CLOUDS_OPACITY'),
					'drip' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_DRIP'),
					'hill' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_HILL'),
					'hill-wave' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_HILL_WAVE'),
					'line-wave' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_LINE_WAVE'),
					'paper-torn' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_PAPER_TORN'),
					'pointy-wave' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_POINTY_WAVE'),
					'rocky-mountain' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_ROCKY_MOUNTAIN'),
					'shaggy' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SHAGGY'),
					'single-wave' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SINGLE_WAVE'),
					'slope-opacity' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SLOPE_OPACITY'),
					'slope' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SLOPE'),
					'swirl' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SWIRL'),
					'wavy-opacity' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_WAVY_OPACITY'),
					'waves3-opacity' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_WAVES3_OPACITY'),
					'turning-slope' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_TURNING_SLOPE'),
					'zigzag-sharp' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_ZIGZAG_SHARP'),
				),
				'std' => 'clouds-flat',
				'depends' => array(
					array('show_top_shape', '=', 1)
				)
			),

			'shape_color' => array(
				'type' => 'color',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_COLOR'),
				'std' => '#e5e5e5',
				'depends' => array(
					array('show_top_shape', '=', 1)
				)
			),

			'shape_width' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_WIDTH'),
				'std' => array(
					'md' => 100,
					'sm' => 100,
					'xs' => 100
				),
				'max' => 600,
				'min' => 100,
				'responsive' => true,
				'depends' => array(
					array('show_top_shape', '=', 1)
				)
			),

			'shape_height' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_HEIGHT'),
				'std' => '',
				'max' => 600,
				'responsive' => true,
				'depends' => array(
					array('show_top_shape', '=', 1)
				)
			),
			'shape_flip' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_FLIP'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_FLIP_DESC'),
				'std' => false,
				'depends' => array(
					array('show_top_shape', '=', 1),
					array('shape_name', '!=', 'bell'),
					array('shape_name', '!=', 'zigzag-sharp')
				)
			),
			'shape_invert' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_INVERT'),
				'std' => false,
				'depends' => array(
					array('show_top_shape', '=', 1),
					array('shape_name', '!=', 'clouds-opacity'),
					array('shape_name', '!=', 'slope-opacity'),
					array('shape_name', '!=', 'waves3-opacity'),
					array('shape_name', '!=', 'paper-torn'),
					array('shape_name', '!=', 'hill-wave'),
					array('shape_name', '!=', 'line-wave'),
					array('shape_name', '!=', 'swirl'),
					array('shape_name', '!=', 'wavy-opacity'),
					array('shape_name', '!=', 'zigzag-sharp'),
					array('shape_name', '!=', 'brushed'),
				)
			),
			'shape_to_front' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_TO_FRONT'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_TO_FRONT_DESC'),
				'std' => false,
				'depends' => array(
					array('show_top_shape', '=', 1)
				)
			),

			'separator_shape_bottom' => array(
				'type' => 'separator',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_BOTTOM_SHAPE')
			),

			'show_bottom_shape' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHOW_SHAPE'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_SHOW_BOTTOM_SHAPE_DESC'),
				'std' => '',
			),

			'bottom_shape_name' => array(
				'type' => 'select',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE'),
				'values' => array(
					'bell' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_BELL'),
					'brushed' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_BRUSHED'),
					'clouds-flat' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_CLOUDS_FLAT'),
					'clouds-opacity' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_CLOUDS_OPACITY'),
					'drip' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_DRIP'),
					'hill' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_HILL'),
					'hill-wave' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_HILL_WAVE'),
					'line-wave' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_LINE_WAVE'),
					'paper-torn' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_PAPER_TORN'),
					'pointy-wave' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_POINTY_WAVE'),
					'rocky-mountain' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_ROCKY_MOUNTAIN'),
					'shaggy' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SHAGGY'),
					'single-wave' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SINGLE_WAVE'),
					'slope-opacity' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SLOPE_OPACITY'),
					'slope' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SLOPE'),
					'swirl' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_SWIRL'),
					'wavy-opacity' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_WAVY_OPACITY'),
					'waves3-opacity' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_WAVES3_OPACITY'),
					'turning-slope' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_TURNING_SLOPE'),
					'zigzag-sharp' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_ZIGZAG_SHARP'),
				),
				'std' => 'clouds-opacity',
				'depends' => array(
					array('show_bottom_shape', '=', 1)
				)
			),

			'bottom_shape_color' => array(
				'type' => 'color',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_COLOR'),
				'std' => '#e5e5e5',
				'depends' => array(
					array('show_bottom_shape', '=', 1)
				)
			),

			'bottom_shape_width' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_WIDTH'),
				'std' => array(
					'md' => 100,
					'sm' => 100,
					'xs' => 100
				),
				'max' => 600,
				'min' => 100,
				'responsive' => true,
				'depends' => array(
					array('show_bottom_shape', '=', 1)
				)
			),

			'bottom_shape_height' => array(
				'type' => 'slider',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_HEIGHT'),
				'std' => '',
				'max' => 600,
				'responsive' => true,
				'depends' => array(
					array('show_bottom_shape', '=', 1)
				)
			),
			'bottom_shape_flip' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_FLIP'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_FLIP_DESC'),
				'std' => false,
				'depends' => array(
					array('show_bottom_shape', '=', 1),
					array('shape_name', '!=', 'bell'),
					array('shape_name', '!=', 'zigzag-sharp')
				)
			),
			'bottom_shape_invert' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_INVERT'),
				'std' => false,
				'depends' => array(
					array('show_bottom_shape', '=', 1),
					array('bottom_shape_name', '!=', 'clouds-opacity'),
					array('bottom_shape_name', '!=', 'slope-opacity'),
					array('bottom_shape_name', '!=', 'waves3-opacity'),
					array('bottom_shape_name', '!=', 'paper-torn'),
					array('bottom_shape_name', '!=', 'hill-wave'),
					array('bottom_shape_name', '!=', 'line-wave'),
					array('bottom_shape_name', '!=', 'swirl'),
					array('shape_name', '!=', 'wavy-opacity'),
					array('shape_name', '!=', 'zigzag-sharp'),
					array('shape_name', '!=', 'brushed'),
				)
			),
			'bottom_shape_to_front' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_TO_FRONT'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ROW_SHAPE_TO_FRONT_DESC'),
				'std' => false,
				'depends' => array(
					array('show_bottom_shape', '=', 1)
				)
			),

		),

		'responsive' => array(

			'hidden_xs' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HIDDEN_XS'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HIDDEN_XS_DESC'),
				'std' => '',
			),
			'hidden_sm' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HIDDEN_SM'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HIDDEN_SM_DESC'),
				'std' => '',
			),
			'hidden_md' => array(
				'type' => 'checkbox',
				'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HIDDEN_MD'),
				'desc' => JText::_('COM_JWPAGEFACTORY_GLOBAL_HIDDEN_MD_DESC'),
				'std' => '',
			),
		),

		'animation' => array(
			'animation' => array(
				'type' => 'animation',
				'title' => JText::_('COM_JWPAGEFACTORY_ANIMATION'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ANIMATION_DESC')
			),

			'animationduration' => array(
				'type' => 'number',
				'title' => JText::_('COM_JWPAGEFACTORY_ANIMATION_DURATION'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ANIMATION_DURATION_DESC'),
				'std' => '300',
				'placeholder' => '300',
			),

			'animationdelay' => array(
				'type' => 'number',
				'title' => JText::_('COM_JWPAGEFACTORY_ANIMATION_DELAY'),
				'desc' => JText::_('COM_JWPAGEFACTORY_ANIMATION_DELAY_DESC'),
				'std' => '0',
				'placeholder' => '300',
			),
		),
	)
);
