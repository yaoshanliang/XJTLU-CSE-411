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

class JwpagefactoryHelperSite
{

	public static function loadLanguage()
	{
		$lang = JFactory::getLanguage();

		$app = JFactory::getApplication();
		$template = $app->getTemplate();

		$com_option = $app->input->get('option', '', 'STR');
		$com_view = $app->input->get('view', '', 'STR');
		$com_id = $app->input->get('id', 0, 'INT');

		if ($com_option == 'com_jwpagefactory' && $com_view == 'form' && $com_id) {
			$lang->load('com_jwpagefactory', JPATH_ADMINISTRATOR, null, true);
		}

		// Load template language file
		$lang->load('tpl_' . $template, JPATH_SITE, null, true);

		require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/helpers/language.php';
	}

	public static function getPaddingMargin($main_value, $type)
	{
		$css = '';
		$pos = array('top', 'right', 'bottom', 'left');
		if (is_string($main_value) && trim($main_value) != "") {
			$values = explode(' ', $main_value);
			foreach ($values as $key => $value) {
				if (trim($value) != "") {
					$css .= $type . '-' . $pos[$key] . ': ' . $value . ';';
				}
			}
		}

		return $css;
	}

	public static function getSvgShapes()
	{
		$shape_path = JPATH_ROOT . '/components/com_jwpagefactory/assets/shapes';
		$shapes = JFolder::files($shape_path, '.svg');

		$shapeArray = array();

		if (count((array)$shapes)) {
			foreach ($shapes as $shape) {
				$shapeArray[str_replace('.svg', '', $shape)] = base64_encode(file_get_contents($shape_path . '/' . $shape));
			}
		}

		return $shapeArray;
	}

	public static function getSvgShapeCode($shapeName, $invert)
	{
		if ($invert) {
			$shape_path = JPATH_ROOT . '/components/com_jwpagefactory/assets/shapes/' . $shapeName . '-invert.svg';
		} else {
			$shape_path = JPATH_ROOT . '/components/com_jwpagefactory/assets/shapes/' . $shapeName . '.svg';
		}

		$shapeCode = '';

		if (file_exists($shape_path)) {
			$shapeCode = file_get_contents($shape_path);
		}

		return $shapeCode;
	}

	// Convert json code to plain text
	public static function getPrettyText($sections)
	{
		$sections = json_decode($sections);
		$output = '';
		foreach ($sections as $section) {
			if (isset($section->title) && $section->title) {
				$output .= $section->title . "\n";
			}
			if (isset($section->subtitle) && $section->subtitle) {
				$output .= $section->subtitle . "\n";
			}
			$output .= self::prettyAddonText($section->columns);
		}

		return $output;
	}

	public static function prettyAddonText($columns)
	{
		$output = '';
		foreach ($columns as $column) {
			if (isset($column->addons) && $column->addons) {
				foreach ($column->addons as $addon) {
					if (isset($addon->settings->title) && $addon->settings->title) {
						$output .= $addon->settings->title . "\n";
					}
					if (isset($addon->settings->text) && $addon->settings->text) {
						$output .= $addon->settings->text . "\n";
					}
					if (isset($addon->settings->html) && $addon->settings->html) {
						$output .= $addon->settings->html . "\n";
					}
					if (isset($addon->settings->pricing_content) && $addon->settings->pricing_content) {
						$output .= $addon->settings->pricing_content . "\n";
					}
					if (isset($addon->settings->modal_content_text) && $addon->settings->modal_content_text) {
						$output .= $addon->settings->modal_content_text . "\n";
					}
					// Flip Box
					if (isset($addon->settings->front_text) && $addon->settings->front_text) {
						$output .= $addon->settings->front_text . "\n";
					}
					if (isset($addon->settings->flip_text) && $addon->settings->flip_text) {
						$output .= $addon->settings->flip_text . "\n";
					}

					// Person
					if (isset($addon->settings->name) && $addon->settings->name) {
						$output .= $addon->settings->name . "\n";
					}
					if (isset($addon->settings->designation) && $addon->settings->designation) {
						$output .= $addon->settings->designation . "\n";
					}
					if (isset($addon->settings->introtext) && $addon->settings->introtext) {
						$output .= $addon->settings->introtext . "\n";
					}

					// Animated heading
					if (isset($addon->settings->heading_before_part) && $addon->settings->heading_before_part) {
						$output .= $addon->settings->heading_before_part . "\n";
					}
					if (isset($addon->settings->highlighted_text) && $addon->settings->highlighted_text) {
						$output .= $addon->settings->highlighted_text . "\n";
					}
					if (isset($addon->settings->animated_text) && $addon->settings->animated_text) {
						$output .= $addon->settings->animated_text . "\n";
					}
					if (isset($addon->settings->heading_after_part) && $addon->settings->heading_after_part) {
						$output .= $addon->settings->heading_after_part . "\n";
					}
					if (isset($addon->settings->heading_after_part) && $addon->settings->heading_after_part) {
						$output .= $addon->settings->heading_after_part . "\n";
					}

					// testimonial
					if (isset($addon->settings->review) && $addon->settings->review) {
						$output .= $addon->settings->review . "\n";
					}

					// Repeatable addon content
					if (isset($addon->name)) {
						$name = str_replace('jw_', '', $addon->name);
						$repeatable = 'jw_' . $name . '_item';
						if (isset($addon->settings->$repeatable) && is_array($addon->settings->$repeatable) && count($addon->settings->$repeatable)) {
							foreach ($addon->settings->$repeatable as $ritem) {

								if (isset($ritem->title) && $ritem->title) {
									$output .= $ritem->title . "\n";
								}

								if (isset($ritem->content)) {
									if (is_array($ritem->content) && count($ritem->content)) {
										foreach ($ritem->content as $rrcontent) {
											if (isset($rrcontent->settings->text) && $rrcontent->settings->text) {
												$output .= $rrcontent->settings->text . "\n";
											}
										}
									} else {
										$output .= $ritem->content . "\n";
									}
								}

								// Testimonial Pro
								if (isset($ritem->message) && $ritem->message) {
									$output .= $ritem->message . "\n";
								}

								if (isset($ritem->content) && is_array($ritem->content) && count($ritem->content)) {
									foreach ($ritem->content as $rcontent) {
										if (isset($rcontent->settings->text) && $rcontent->settings->text) {
											$output .= $rcontent->settings->text . "\n";
										}
									}
								}
							}
						}

						// Slideshow addon
						if ($addon->name == 'js_slideshow') {
							if (isset($addon->settings->slideshow_items) && $addon->settings->slideshow_items) {
								foreach ($addon->settings->slideshow_items as $slideshow_items) {
									if (isset($slideshow_items->slideshow_inner_items) && count($slideshow_items->slideshow_inner_items)) {
										foreach ($slideshow_items->slideshow_inner_items as $slide) {
											if (isset($slide->title) && $slide->title) {
												$output .= $slide->title;
											}
											if (isset($slide->content_text) && $slide->content_text) {
												$output .= $slide->content_text;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return trim(strip_tags($output, ''));
	}
}
