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

$report = array();
$report['items'] = $this->items;
$report['filters'] = $this->filters;

if($this->total > ($this->limit + $this->start)) {
	$report['pageNav'] 	= 'true';
} else {
	$report['pageNav'] 	= 'false';
}

echo json_encode($report); die;
