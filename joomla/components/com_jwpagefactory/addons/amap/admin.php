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

$params = JComponentHelper::getParams('com_jwpagefactory');
$amap_api = $params->get('amap_api', '');

$amap_config = array(
	'admin_label' => array(
		'type' => 'text',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_ADMIN_LABEL'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_ADMIN_LABEL_DESC'),
		'category' => 'General',
		'std' => ''
	),

	// Title
	'title' => array(
		'type' => 'text',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_DESC'),
		'std' => ''
	),

	'heading_selector' => array(
		'type' => 'select',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_HEADINGS'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_HEADINGS_DESC'),
		'values' => array(
			'h1' => JText::_('COM_JWPAGEFACTORY_ADDON_HEADINGS_H1'),
			'h2' => JText::_('COM_JWPAGEFACTORY_ADDON_HEADINGS_H2'),
			'h3' => JText::_('COM_JWPAGEFACTORY_ADDON_HEADINGS_H3'),
			'h4' => JText::_('COM_JWPAGEFACTORY_ADDON_HEADINGS_H4'),
			'h5' => JText::_('COM_JWPAGEFACTORY_ADDON_HEADINGS_H5'),
			'h6' => JText::_('COM_JWPAGEFACTORY_ADDON_HEADINGS_H6'),
		),
		'std' => 'h3',
		'depends' => array(array('title', '!=', '')),
	),

	'title_font_family' => array(
		'type' => 'fonts',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_FONT_FAMILY'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_FONT_FAMILY_DESC'),
		'depends' => array(array('title', '!=', '')),
		'selector' => array(
			'type' => 'font',
			'font' => '{{ VALUE }}',
			'css' => '.jwpf-addon-title { font-family: {{ VALUE }}; }'
		)
	),

	'title_fontsize' => array(
		'type' => 'slider',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_FONT_SIZE'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_FONT_SIZE_DESC'),
		'std' => '',
		'max' => 400,
		'responsive' => true,
		'depends' => array(array('title', '!=', '')),
	),

	'title_lineheight' => array(
		'type' => 'slider',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_LINE_HEIGHT'),
		'std' => '',
		'max' => 400,
		'responsive' => true,
		'depends' => array(array('title', '!=', '')),
	),

	'title_font_style' => array(
		'type' => 'fontstyle',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_FONT_STYLE'),
		'depends' => array(array('title', '!=', '')),
	),

	'title_letterspace' => array(
		'type' => 'select',
		'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_LETTER_SPACING'),
		'values' => array(
			'0' => 'Default',
			'1px' => '1px',
			'2px' => '2px',
			'3px' => '3px',
			'4px' => '4px',
			'5px' => '5px',
			'6px' => '6px',
			'7px' => '7px',
			'8px' => '8px',
			'9px' => '9px',
			'10px' => '10px'
		),
		'std' => '0',
		'depends' => array(array('title', '!=', '')),
	),

	'title_text_color' => array(
		'type' => 'color',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_TEXT_COLOR'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_TEXT_COLOR_DESC'),
		'depends' => array(array('title', '!=', '')),
	),

	'title_margin_top' => array(
		'type' => 'slider',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_MARGIN_TOP'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_MARGIN_TOP_DESC'),
		'placeholder' => '10',
		'max' => 400,
		'responsive' => true,
		'depends' => array(array('title', '!=', '')),
	),

	'title_margin_bottom' => array(
		'type' => 'slider',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_MARGIN_BOTTOM'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_TITLE_MARGIN_BOTTOM_DESC'),
		'placeholder' => '10',
		'max' => 400,
		'responsive' => true,
		'depends' => array(array('title', '!=', '')),
	),

	// Map
	'separator_addon_options' => array(
		'type' => 'separator',
		'title' => JText::_('COM_JWPAGEFACTORY_GLOBAL_ADDON_OPTIONS')
	)

);

if (empty($amap_api)) {
	$amap_config['message'] = array(
		'type' => 'message',
		'alert' => 'warning',
		'message' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_APIKEY_MISSING'),
	);
}

$amap_config['map'] = array(
	'type' => 'amap',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_LOCATION'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_LOCATION_DESC'),
	'std' => '116.397464,39.908696'
);

$amap_config['marker'] = array(
	'type' => 'media',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_MARKER'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_MARKER_DESC'),
	'placeholder' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_MARKER_HINT'),
	'show_input' => true,
	'std' => ''
);

$amap_config['infowindow'] = array(
	'type' => 'textarea',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_INFOWINDOW'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_INFOWINDOW_DESC'),
);

$amap_config['multi_location'] = array(
	'type' => 'checkbox',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_MULTI_LOCATION'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_MULTI_LOCATION_DESC'),
	'values' => array(
		1 => JText::_('YES'),
		0 => JText::_('NO'),
	),
	'std' => 0
);

$amap_config['multi_location_items'] = array(
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_MULTI_LOCATION_ITEMS'),
	'attr' => array(
		'title' => array(
			'type' => 'text',
			'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_TITLE'),
			'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_TITLE_DESC'),
			'std' => JText::_('COM_JWPAGEFACTORY_ITEM') . ' 1'
		),

		'location_item' => array(
			'type' => 'amap',
			'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_LOCATION'),
			'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_LOCATION_DESC'),
			'std' => '',
		),
		'location_popup_text' => array(
			'type' => 'textarea',
			'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_INFOWINDOW'),
			'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_INFOWINDOW_DESC'),
			'std' => '',
		),
		'location_marker' => array(
			'type' => 'media',
			'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_MARKER'),
			'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_MARKER_DESC'),
			'placeholder' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_MARKER_HINT'),
			'show_input' => true,
			'std' => '',
		),
	),
	'depends' => array(
		array('multi_location', '!=', 0),
	)
);

$amap_config['height'] = array(
	'type' => 'slider',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_HEIGHT'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_HEIGHT_DESC'),
	'placeholder' => '300',
	'std' => array('md' => 300),
	'max' => 2000,
	'responsive' => true,
	'depends' => array(array('map', '!=', '')),
);

$amap_config['lang'] = array(
	'type' => 'select',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_LANG'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_LANG_DESC'),
	'values' => array(
		'zh_cn' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_LANG_ZH_CN'),
		'en' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_LANG_EN'),
		'zh_en' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_LANG_ZH_EN'),
	),
	'std' => 'zh_cn',
	'depends' => array(array('map', '!=', '')),
);

$amap_config['style'] = array(
	'type' => 'select',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_DESC'),
	'values' => array(
		'normal' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_NORMAL'),
		'dark' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_DARK'),
		'light' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_LIGHT'),
		'whitesmoke' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_WHITESMOKE'),
		'fresh' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_FRESH'),
		'grey' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_GREY'),
		'graffiti' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_GRAFFITI'),
		'macaron' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_MACARON'),
		'blue' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_BLUE'),
		'darkblue' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_DARKBLUE'),
		'wine' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_STYLE_WINE'),
	),
	'std' => 'normal',
	'depends' => array(
		array('map', '!=', ''),
		array('lang', '=', 'zh_cn')
	),
);

$amap_config['type'] = array(
	'type' => 'select',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_TYPE'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_TYPE_DESC'),
	'values' => array(
		'default' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_TYPE_DEFAULT'),
		'roadnet' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_TYPE_ROADNET'),
		'satellite' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_TYPE_SATELLITE'),
		'hybrid' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_TYPE_HYBRID'),
		'traffic' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_TYPE_TRAFFIC'),
	),
	'std' => 'default',
	'depends' => array(array('map', '!=', '')),
);

$amap_config['zoom'] = array(
	'type' => 'slider',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_ZOOM'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_ZOOM_DESC'),
	'placeholder' => '5',
	'std' => '5',
	'max' => 25,
	'depends' => array(array('map', '!=', '')),
);

$amap_config['mousescroll'] = array(
	'type' => 'select',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_DISABLE_MOUSE_SCROLL'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_DISABLE_MOUSE_SCROLL_DESC'),
	'values' => array(
		'false' => JText::_('JYES'),
		'true' => JText::_('JNO'),
	),
	'std' => 'true',
	'depends' => array(array('map', '!=', '')),
);

$amap_config['class'] = array(
	'type' => 'text',
	'title' => JText::_('COM_JWPAGEFACTORY_ADDON_CLASS'),
	'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_CLASS_DESC'),
	'std' => ''
);

JwAddonsConfig::addonConfig(
	array(
		'type' => 'content',
		'addon_name' => 'jw_amap',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_AMAP_DESC'),
		'attr' => array(
			'general' => $amap_config
		),
	)
);
