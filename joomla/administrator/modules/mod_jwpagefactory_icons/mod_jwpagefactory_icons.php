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
defined ('_JEXEC') or die ('restricted access');

$mod_name = 'mod_jwpagefactory_icons';

$document 	= JFactory::getDocument();
$input 		= JFactory::getApplication()->input;

$document->addStyleSheet(JURI::base(true).'/modules/'.$mod_name.'/tmpl/css/pagefactory-style.css');

require JModuleHelper::getLayoutPath($mod_name,$params->get('layout','default'));