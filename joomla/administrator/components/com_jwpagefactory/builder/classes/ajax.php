<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */

/**
 * JW Page Factory settings ajax loader
 *
 * @since version 2.0
 */
defined('_JEXEC') or die ('Restricted access');

class JwpfSettingsAjax
{

	/**
	 * Ajax Request Settings type
	 * @since 2.0
	 * @access private
	 * @var strings
	 */
	private $type = '';


	private $addon_raw;


	/**
	 * Current Row Index
	 * @since 2.0
	 * @access private
	 * @var intiger
	 */
	private $row_index;

	/**
	 * Current Column Index
	 * @since 2.0
	 * @access private
	 * @var intiger
	 */
	private $column_index;

	/**
	 * Current Addon Index
	 * @since 2.0
	 * @access private
	 * @var intiger
	 */
	private $addon_index;

	/**
	 * Current Addon Index
	 * @since 2.0
	 * @access private
	 * @var intiger
	 */
	private $addon_name;

	/**
	 * Current Addon Index
	 * @since 2.0
	 * @access private
	 * @var array
	 */
	private $settings = array();

	/**
	 * Current Addon Index
	 * @since 2.0
	 * @access private
	 * @var array
	 */
	private $options = array();

	/**
	 * Current Addon Index
	 * @since 2.0
	 * @access private
	 * @var bool
	 */
	private $error = false;
	private $addons = array();

	/**
	 * Construct
	 */

	function __construct($post = array(), $default)
	{
		if (isset($post['type'])) {
			$this->type = $post['type'];
		}

		if (isset($post['settings'])) {
			$this->settings = $post['settings'];
		}

		if (isset($post['rowIndex'])) {
			$this->row_index = $post['rowIndex'];
		}

		if (isset($post['colIndex'])) {
			$this->column_index = $post['colIndex'];
		}

		if (isset($post['addonIndex'])) {
			$this->addon_index = $post['addonIndex'];
		}

		if (isset($post['addonName'])) {
			$this->addon_name = $post['addonName'];
		}

		if ($this->type === 'row' || $this->type === 'inner_row') {
			$this->options = $default['attr'];
		} else if ($this->type === 'column' || $this->type === 'inner_column') {
			$this->options = $default['attr'];
		} else if ($this->type === 'addon' || $this->type === 'inner_addon') {
			$this->options = $default[$this->addon_name]['attr'];
			$this->addon_raw = $default[$this->addon_name];
		} else if ($this->type === 'list') {
			$this->addons = $default;
		}
	}

	/**
	 * Ajax request call back
	 * @since 2.0
	 * @return JSON
	 */
	public function get_ajax_request()
	{
		$result = new stdClass;

		if ($this->type === 'list') {
			$result->categories = $this->get_addon_categories($this->addons);
		}

		$result->type = $this->type;
		$result->rowIndex = $this->row_index;
		$result->columnIndex = $this->column_index;
		$result->addonIndex = $this->addon_index;
		$result->addonName = $this->addon_name;
		$result->data = $this->get_settings_result();
		$result->status = true;

		return json_encode($result);
	}

	/**
	 * Get settings data
	 * @since 2.0
	 * @access private
	 * @return array|string
	 */
	private function get_addon_categories($addons)
	{
		$categories = array();
		foreach ($addons as $addon) {
			if (isset($addon['category'])) {
				$categories[] = $addon['category'];
			}
		}
		$new_array = array_count_values($categories);

		$result[0]['name'] = 'All';
		$result[0]['count'] = count((array)$addons);
		if (count((array)$new_array)) {
			$i = 1;
			foreach ($new_array as $key => $row) {
				$result[$i]['name'] = $key;
				$result[$i]['count'] = $row;
				$i = $i + 1;
			}
		}

		return $result;
	}

	/**
	 * Get settings data
	 * @since 2.0
	 * @access private
	 * @return array|string
	 */
	private function get_settings_result()
	{
		switch ($this->type) {
			case 'list':
				return $this->get_addons_array_list();
				break;

			case 'row':
			case 'column':
			case 'addon':
			case 'inner_row':
			case 'inner_column':
			case 'inner_addon':
				return $this->get_inputs_output_html();
				break;

			default:
				return 'No related settings found';
				break;
		}
	}

	/**
	 * Array of addons list
	 * @since 2.0
	 * @access private
	 * @return array
	 */
	private function get_addons_array_list()
	{
		return array_values($this->addons);
	}

	/**
	 * HTML of row/column/addons settings inputs
	 * @since 2.0
	 * @access private
	 * @return string
	 */
	private function get_inputs_output_html()
	{
		$i = 0;
		$j = 0;

		$addon_title = $this->addon_raw['title'];
		$fieldsets = array();
		foreach ($this->options as $key => $option) {
			$fieldsets[$key] = $option;
		}

		$output = '';

		if (count((array)$fieldsets)) {
			$output .= '<div class="jw-pagefactory-fieldset">';
			$output .= '<ul class="jw-pagefactory-nav jw-pagefactory-nav-tabs">';
			foreach ($fieldsets as $key => $value) {
				$output .= '<li class="' . (($i === 0) ? "active" : "") . '"><a href="#jw-pagefactory-tab-' . $key . '"  aria-controls="' . $key . '" data-toggle="tab">' . ucfirst($key) . '</a></li>';
				$i++;
			}
			$output .= '</ul>';
			$output .= '<div class="tab-content">';
			foreach ($fieldsets as $key => $value) {
				$output .= '<div class="tab-pane ' . (($j === 0) ? "active" : "") . '" id="jw-pagefactory-tab-' . $key . '">';
				$output .= $this->get_input_fields($key, $addon_title);
				$output .= '</div>';

				$j++;
			}
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	/**
	 * Get input fileds
	 * @since 2.0
	 * @access private
	 * @return string
	 */
	private function get_input_fields($filedset = '', $title = '')
	{
		if (!$filedset) return;

		$options = $this->options;

		$output = '';
		foreach ($options[$filedset] as $key => $option) {
			if (isset($option['attr']) && is_array($option['attr'])) {
				$output .= $this->get_grouped_input_fields($key, $option, $title);
			} else {
				if (isset($this->settings[$key])) {
					$option['std'] = $this->settings[$key];
				}
				$output .= '<div class="jw-pagefactory-parent-input-field" data-addon_title="' . $title . '">';
				$output .= $this->get_input_field_html($key, $option);
				$output .= '</div>';
			}
		}

		return $output;
	}

	/**
	 * Input filed based on type
	 * @since 2.0
	 * @access private
	 * @return string
	 */
	private function get_input_field_html($name, $options)
	{
		$class_name = 'JwType' . ucfirst($options['type']);
		return $class_name::getInput($name, $options);
	}

	/**
	 * Grouped input filed generator
	 * @since 2.0
	 * @access private
	 * @return string
	 */
	private function get_grouped_input_fields($name, $options, $title = '')
	{
		$grouped = array();
		if (isset($this->settings[$name])) {
			$grouped = $this->settings[$name];
		} elseif (isset($options['addon_name']) && $options['addon_name']) {
			$name = $options['addon_name'];
			if (isset($this->settings[$name])) {
				$grouped = $this->settings[$name];
			}
		}
		$count = count((array)$grouped);

		if ($count <= 0) {
			$count = 1;
		}
		$output = '';

		if (isset($options['title']) && $options['title'] != '') {
			$output .= '<div class="jw-pagefactory-grouped-wrap jw-pagefactory-parent-input-field jw-pagefactory-has-group-title" data-addon_title="' . $title . '" data-field_name="' . $name . '">';
		} else {
			$output .= '<div class="jw-pagefactory-grouped-wrap jw-pagefactory-parent-input-field" data-addon_title="' . $title . '" data-field_name="' . $name . '">';
		}

		if (isset($options['title']) && $options['title'] != '') {
			$output .= '<h4 class="jw-pagefactory-group-title">' . $options['title'] . '</h4>';
		}

		$output .= '<a href="#" class="jw-pagefactory-add-grouped-item jw-pagefactory-btn jw-pagefactory-btn-warning jw-pagefactory-btn-sm"><i class="fa fa-plus"></i> Add New</a>';
		$output .= '<div class="jw-pagefactory-grouped jw-pagefactory-grouped-items grouped-' . $name . '" data-field_no="' . ($count - 1) . '">';
		for ($i = 0; $i < $count; $i++) {

			$output .= '<div class="jw-pagefactory-grouped-item">';
			$output .= '<div class="jw-pagefactory-repeatable-toggler">';
			$output .= '<span class="jw-pagefactory-move-repeatable"><i class="fa fa-ellipsis-v"></i></span>';
			$output .= '<h4 class="jw-pagefactory-repeatable-item-title">Item ' . ($i + 1) . '</h4>';
			$output .= '<span class="jw-pagefactory-remove-grouped-item"><a href="#"><i class="fa fa-times"></i></a></span>';
			$output .= '<span class="jw-pagefactory-clone-grouped-item"><a href="#"><i class="fa fa-clone"></i></a></span>';
			$output .= '</div>';

			$output .= '<div class="jw-pagefactory-grouped-item-collapse" style="display:none;">';
			foreach ($options['attr'] as $key => $option) {
				if (isset($grouped[$i][$key])) {
					$option['std'] = $grouped[$i][$key];
				}

				$key = $name . '[' . $i . '][' . $key . ']';
				$output .= '<div class="jw-pagefactory-repeatable-input-field">';
				$output .= $this->get_input_field_html($key, $option);
				$output .= '</div>';
			}
			$output .= '</div>';
			$output .= '</div>';
		}

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}
