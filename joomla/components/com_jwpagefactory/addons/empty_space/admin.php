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

JwAddonsConfig::addonConfig(
	array(
		'type' => 'content',
		'addon_name' => 'empty_space',
		'title' => JText::_('COM_JWPAGEFACTORY_ADDON_EMPTY_SPACE'),
		'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_EMPTY_SPACE_DESC'),
		'category' => 'General',
		'attr' => array(
			'general' => array(

				'admin_label' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_ADMIN_LABEL'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_ADMIN_LABEL_DESC'),
					'std' => ''
				),

				'gap' => array(
					'type' => 'slider',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_EMPTY_SPACE_GAP'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_EMPTY_SPACE_GAP_DESC'),
					'min' => 5,
					'max' => 400,
					'std' => array('md' => 40, 'sm' => 30, 'xs' => 20),
					'responsive' => true
				),

				'class' => array(
					'type' => 'text',
					'title' => JText::_('COM_JWPAGEFACTORY_ADDON_CLASS'),
					'desc' => JText::_('COM_JWPAGEFACTORY_ADDON_CLASS_DESC'),
					'std' => ''
				),

			),
		),
	)
);
