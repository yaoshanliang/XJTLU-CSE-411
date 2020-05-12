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

$input = JFactory::getApplication()->input;
$m_source = $input->get('source', '', 'STRING');

if ($m_source == 'page') {
	$report = array();
	$report['items'] = $this->items;
	$report['filters'] = $this->filters;

	if ($this->total > ($this->limit + $this->start)) {
		$report['pageNav'] = 'true';
	} else {
		$report['pageNav'] = 'false';
	}

	echo json_encode($report);
	die;
} else {
	$layout_path = JPATH_ROOT . '/administrator/components/com_jwpagefactory/layouts';

	$categories_layout = new JLayoutFile('media.categories', $layout_path);
	$report['media_categories'] = $categories_layout->render(array('categories' => $this->categories));

	$report['output'] = '';
	$report['count'] = 0;

	// Date Filter
	if (count((array)$this->filters)) {
		$report['date_filter'] = '<select class="jw-pagefactory-date-filter">';
		$report['date_filter'] .= '<option value="">' . JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_MEDIA_ALL') . '</option>';

		foreach ($this->filters as $key => $this->filter) {
			$report['date_filter'] .= '<option value="' . $this->filter->year . '-' . $this->filter->month . '">' . JHtml::_('date', $this->filter->year . '-' . $this->filter->month, 'F Y') . '</option>';
		}

		$report['date_filter'] .= '</select>';
	} else {
		$report['date_filter'] = '<select class="date-filter">';
		$report['date_filter'] .= '<option value="">' . JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_MEDIA_ALL') . '</option>';
		$report['date_filter'] .= '</select>';
	}

	// Load More
	if ($this->total > ($this->limit + $this->start)) {
		$report['loadmore'] = true;
	} else {
		$report['loadmore'] = false;
	}


	// Media Items
	if (!$this->start) $report['output'] .= '<ul class="jw-pagefactory-media clearfix">';

	if (count((array)$this->items)) {
		foreach ($this->items as $key => $this->item) {
			$format_layout = new JLayoutFile('media.format', $layout_path);
			$report['output'] .= $format_layout->render(array('media' => $this->item));
		}
	}

	if (!$this->start) $report['output'] .= '</ul>';

	// Get Media count
	$report['count'] += (isset($this->items) && count((array)$this->items)) ? count((array)$this->items) : 0;

	echo json_encode($report);

	die;
}
