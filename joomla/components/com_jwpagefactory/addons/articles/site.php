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
defined('_JEXEC') or die ('resticted access');

class JwpagefactoryAddonArticles extends JwpagefactoryAddons
{

	public function render()
	{
		$page_view_name = isset($_GET['view']);

		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$style = (isset($settings->style) && $settings->style) ? $settings->style : 'panel-default';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		// Addon options
		$resource = (isset($settings->resource) && $settings->resource) ? $settings->resource : 'article';
		$catid = (isset($settings->catid) && $settings->catid) ? $settings->catid : 0;
		$tagids = (isset($settings->tagids) && $settings->tagids) ? $settings->tagids : array();
		$k2catid = (isset($settings->k2catid) && $settings->k2catid) ? $settings->k2catid : 0;
		$include_subcat = (isset($settings->include_subcat)) ? $settings->include_subcat : 1;
		$post_type = (isset($settings->post_type) && $settings->post_type) ? $settings->post_type : '';
		$ordering = (isset($settings->ordering) && $settings->ordering) ? $settings->ordering : 'latest';
		$limit = (isset($settings->limit) && $settings->limit) ? $settings->limit : 3;
		$columns = (isset($settings->columns) && $settings->columns) ? $settings->columns : 3;
		$show_intro = (isset($settings->show_intro)) ? $settings->show_intro : 1;
		$intro_limit = (isset($settings->intro_limit) && $settings->intro_limit) ? $settings->intro_limit : 200;
		$hide_thumbnail = (isset($settings->hide_thumbnail)) ? $settings->hide_thumbnail : 0;
		$show_author = (isset($settings->show_author)) ? $settings->show_author : 1;
		$show_category = (isset($settings->show_category)) ? $settings->show_category : 1;
		$show_date = (isset($settings->show_date)) ? $settings->show_date : 1;
		$show_readmore = (isset($settings->show_readmore)) ? $settings->show_readmore : 1;
		$readmore_text = (isset($settings->readmore_text) && $settings->readmore_text) ? $settings->readmore_text : 'Read More';
		$link_articles = (isset($settings->link_articles)) ? $settings->link_articles : 0;
		$link_catid = (isset($settings->link_catid)) ? $settings->link_catid : 0;
		$link_k2catid = (isset($settings->link_k2catid)) ? $settings->link_k2catid : 0;

		$all_articles_btn_text = (isset($settings->all_articles_btn_text) && $settings->all_articles_btn_text) ? $settings->all_articles_btn_text : 'See all posts';
		$all_articles_btn_class = (isset($settings->all_articles_btn_size) && $settings->all_articles_btn_size) ? ' jwpf-btn-' . $settings->all_articles_btn_size : '';
		$all_articles_btn_class .= (isset($settings->all_articles_btn_type) && $settings->all_articles_btn_type) ? ' jwpf-btn-' . $settings->all_articles_btn_type : ' jwpf-btn-default';
		$all_articles_btn_class .= (isset($settings->all_articles_btn_shape) && $settings->all_articles_btn_shape) ? ' jwpf-btn-' . $settings->all_articles_btn_shape : ' jwpf-btn-rounded';
		$all_articles_btn_class .= (isset($settings->all_articles_btn_appearance) && $settings->all_articles_btn_appearance) ? ' jwpf-btn-' . $settings->all_articles_btn_appearance : '';
		$all_articles_btn_class .= (isset($settings->all_articles_btn_block) && $settings->all_articles_btn_block) ? ' ' . $settings->all_articles_btn_block : '';
		$all_articles_btn_icon = (isset($settings->all_articles_btn_icon) && $settings->all_articles_btn_icon) ? $settings->all_articles_btn_icon : '';
		$all_articles_btn_icon_position = (isset($settings->all_articles_btn_icon_position) && $settings->all_articles_btn_icon_position) ? $settings->all_articles_btn_icon_position : 'left';

		$output = '';
		//include k2 helper
		$k2helper = JPATH_ROOT . '/components/com_jwpagefactory/helpers/k2.php';
		$article_helper = JPATH_ROOT . '/components/com_jwpagefactory/helpers/articles.php';
		$isk2installed = self::isComponentInstalled('com_k2');

		if ($resource == 'k2') {
			if ($isk2installed == 0) {
				$output .= '<p class="alert alert-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_ARTICLE_ERORR_K2_NOTINSTALLED') . '</p>';
				return $output;
			} elseif (!file_exists($k2helper)) {
				$output .= '<p class="alert alert-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_K2_HELPER_FILE_MISSING') . '</p>';
				return $output;
			} else {
				require_once $k2helper;
			}
			$items = JwpagefactoryHelperK2::getItems($limit, $ordering, $k2catid, $include_subcat);
		} else {
			require_once $article_helper;
			$items = JwpagefactoryHelperArticles::getArticles($limit, $ordering, $catid, $include_subcat, $post_type, $tagids);
		}

		if (!count($items)) {
			$output .= '<p class="alert alert-warning">' . JText::_('COM_JWPAGEFACTORY_NO_ITEMS_FOUND') . '</p>';
			return $output;
		}

		if (count((array)$items)) {
			$output .= '<div class="jwpf-addon jwpf-addon-articles ' . $class . '">';

			if ($title) {
				$output .= '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>';
			}

			$output .= '<div class="jwpf-addon-content">';
			$output .= '<div class="jwpf-row">';

			foreach ($items as $key => $item) {
				$output .= '<div class="jwpf-col-sm-' . round(12 / $columns) . '">';
				$output .= '<div class="jwpf-addon-article">';

				if (!$hide_thumbnail) {
					$image = '';
					if ($resource == 'k2') {
						if (isset($item->image_medium) && $item->image_medium) {
							$image = $item->image_medium;
						} elseif (isset($item->image_large) && $item->image_large) {
							$image = $item->image_medium;
						}
					} else {
						$image = $item->image_thumbnail;
					}

					if ($resource != 'k2' && $item->post_format == 'gallery') {
						if (count((array)$item->imagegallery->images)) {
							$output .= '<div class="jwpf-carousel jwpf-slide" data-jwpf-ride="jwpf-carousel">';
							$output .= '<div class="jwpf-carousel-inner">';
							foreach ($item->imagegallery->images as $key => $gallery_item) {
								$active_class = '';
								if ($key == 0) {
									$active_class = ' active';
								}
								if (isset($gallery_item['thumbnail']) && $gallery_item['thumbnail']) {
									$output .= '<div class="jwpf-item' . $active_class . '">';
									$output .= '<img src="' . $gallery_item['thumbnail'] . '" alt="">';
									$output .= '</div>';
								} elseif (isset($gallery_item['full']) && $gallery_item['full']) {
									$output .= '<div class="jwpf-item' . $active_class . '">';
									$output .= '<img src="' . $gallery_item['full'] . '" alt="">';
									$output .= '</div>';
								}
							}
							$output .= '</div>';

							$output .= '<a class="left jwpf-carousel-control" role="button" data-slide="prev" aria-label="' . JText::_('COM_JWPAGEFACTORY_ARIA_PREVIOUS') . '"><i class="fa fa-angle-left"></i></a>';
							$output .= '<a class="right jwpf-carousel-control" role="button" data-slide="next" aria-label="' . JText::_('COM_JWPAGEFACTORY_ARIA_NEXT') . '"><i class="fa fa-angle-right"></i></a>';

							$output .= '</div>';

						} elseif (isset($item->image_thumbnail) && $item->image_thumbnail) {
							//Lazyload image
							$placeholder = $item->image_thumbnail == '' ? false : $this->get_image_placeholder($item->image_thumbnail);

							$output .= '<a href="' . $item->link . '" itemprop="url"><img class="jwpf-img-responsive' . ($placeholder && $page_view_name != 'form' ? ' jwpf-element-lazy' : '') . '" src="' . ($placeholder && $page_view_name != 'form' ? $placeholder : $item->image_thumbnail) . '" alt="' . $item->title . '" itemprop="thumbnailUrl" ' . ($placeholder && $page_view_name != 'form' ? 'data-large="' . $image . '"' : '') . '  loading="lazy"></a>';
						}
					} elseif ($resource != 'k2' && $item->post_format == 'video' && isset($item->video_src) && $item->video_src) {
						$output .= '<div class="entry-video embed-responsive embed-responsive-16by9">';
						$output .= '<object class="embed-responsive-item" style="width:100%;height:100%;" data="' . $item->video_src . '">';
						$output .= '<param name="movie" value="' . $item->video_src . '">';
						$output .= '<param name="wmode" value="transparent" />';
						$output .= '<param name="allowFullScreen" value="true">';
						$output .= '<param name="allowScriptAccess" value="always"></param>';
						$output .= '<embed src="' . $item->video_src . '" type="application/x-shockwave-flash" allowscriptaccess="always"></embed>';
						$output .= '</object>';
						$output .= '</div>';
					} elseif ($resource != 'k2' && $item->post_format == 'audio' && isset($item->audio_embed) && $item->audio_embed) {
						$output .= '<div class="entry-audio embed-responsive embed-responsive-16by9">';
						$output .= $item->audio_embed;
						$output .= '</div>';
					} elseif ($resource != 'k2' && $item->post_format == 'link' && isset($item->link_url) && $item->link_url) {
						$output .= '<div class="entry-link">';
						$output .= '<a target="_blank" rel="noopener noreferrer" href="' . $item->link_url . '"><h4>' . $item->link_title . '</h4></a>';
						$output .= '</div>';
					} else {
						if (isset($image) && $image) {
							//Lazyload image
							$default_placeholder = $image == '' ? false : $this->get_image_placeholder($image);

							$output .= '<a class="jwpf-article-img-wrap" href="' . $item->link . '" itemprop="url"><img class="jwpf-img-responsive' . ($default_placeholder && $page_view_name != 'form' ? ' jwpf-element-lazy' : '') . '" src="' . ($default_placeholder && $page_view_name != 'form' ? $default_placeholder : $image) . '" alt="' . $item->title . '" itemprop="thumbnailUrl" ' . ($default_placeholder && $page_view_name != 'form' ? 'data-large="' . $image . '"' : '') . ' loading="lazy"></a>';
						}
					}
				}

				$output .= '<div class="jwpf-article-info-wrap">';
				$output .= '<h3><a href="' . $item->link . '" itemprop="url">' . $item->title . '</a></h3>';

				if ($show_author || $show_category || $show_date) {
					$output .= '<div class="jwpf-article-meta">';

					if ($show_date) {
						$output .= '<span class="jwpf-meta-date" itemprop="datePublished">' . Jhtml::_('date', $item->publish_up, 'DATE_FORMAT_LC3') . '</span>';
					}

					if ($show_category) {
						if ($resource == 'k2') {
							$item->catUrl = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($item->catid . ':' . urlencode($item->category_alias))));
						} else {
							$item->catUrl = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug));
						}
						$output .= '<span class="jwpf-meta-category"><a href="' . $item->catUrl . '" itemprop="genre">' . $item->category . '</a></span>';
					}

					if ($show_author) {
						$author = ($item->created_by_alias ? $item->created_by_alias : $item->username);
						$output .= '<span class="jwpf-meta-author" itemprop="name">' . $author . '</span>';
					}

					$output .= '</div>';
				}

				if ($show_intro) {
					$output .= '<div class="jwpf-article-introtext">' . mb_substr(strip_tags($item->introtext), 0, $intro_limit, 'UTF-8') . '...</div>';
				}

				if ($show_readmore) {
					$output .= '<a class="jwpf-readmore" href="' . $item->link . '" itemprop="url">' . $readmore_text . '</a>';
				}
				$output .= '</div>'; //.jwpf-article-info-wrap

				$output .= '</div>';
				$output .= '</div>';
			}

			$output .= '</div>';

			// See all link
			if ($link_articles) {

				$icon_arr = array_filter(explode(' ', $all_articles_btn_icon));
				if (count($icon_arr) === 1) {
					$all_articles_btn_icon = 'fa ' . $all_articles_btn_icon;
				}

				if ($all_articles_btn_icon_position == 'left') {
					$all_articles_btn_text = ($all_articles_btn_icon) ? '<i class="' . $all_articles_btn_icon . '" aria-hidden="true"></i> ' . $all_articles_btn_text : $all_articles_btn_text;
				} else {
					$all_articles_btn_text = ($all_articles_btn_icon) ? $all_articles_btn_text . ' <i class="' . $all_articles_btn_icon . '" aria-hidden="true"></i>' : $all_articles_btn_text;
				}

				if ($resource == 'k2') {
					if (!empty($link_k2catid)) {
						$output .= '<a href="' . urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($link_k2catid))) . '" " id="btn-' . $this->addon->id . '" class="jwpf-btn' . $all_articles_btn_class . '">' . $all_articles_btn_text . '</a>';
					}
				} else {
					if (!empty($link_catid)) {
						$output .= '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($link_catid)) . '" id="btn-' . $this->addon->id . '" class="jwpf-btn' . $all_articles_btn_class . '">' . $all_articles_btn_text . '</a>';
					}
				}

			}

			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	public function css()
	{
		$addon_id = '#jwpf-addon-' . $this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_jwpagefactory/layouts';
		$css_path = new JLayoutFile('addon.css.button', $layout_path);
		$settings = $this->addon->settings;

		$options = new stdClass;
		$options->button_type = (isset($settings->all_articles_btn_type) && $settings->all_articles_btn_type) ? $settings->all_articles_btn_type : '';
		$options->button_appearance = (isset($settings->all_articles_btn_appearance) && $settings->all_articles_btn_appearance) ? $settings->all_articles_btn_appearance : '';
		$options->button_color = (isset($settings->all_articles_btn_color) && $settings->all_articles_btn_color) ? $settings->all_articles_btn_color : '';
		$options->button_color_hover = (isset($settings->all_articles_btn_color_hover) && $settings->all_articles_btn_color_hover) ? $settings->all_articles_btn_color_hover : '';
		$options->button_background_color = (isset($settings->all_articles_btn_background_color) && $settings->all_articles_btn_background_color) ? $settings->all_articles_btn_background_color : '';
		$options->button_background_color_hover = (isset($settings->all_articles_btn_background_color_hover) && $settings->all_articles_btn_background_color_hover) ? $settings->all_articles_btn_background_color_hover : '';
		$options->button_fontstyle = (isset($settings->all_articles_btn_font_style) && $settings->all_articles_btn_font_style) ? $settings->all_articles_btn_font_style : '';
		$options->button_font_style = (isset($settings->all_articles_btn_font_style) && $settings->all_articles_btn_font_style) ? $settings->all_articles_btn_font_style : '';
		$options->button_letterspace = (isset($settings->all_articles_btn_letterspace) && $settings->all_articles_btn_letterspace) ? $settings->all_articles_btn_letterspace : '';

		return $css_path->render(array('addon_id' => $addon_id, 'options' => $options, 'id' => 'btn-' . $this->addon->id));
	}

	static function isComponentInstalled($component_name)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.enabled');
		$query->from($db->quoteName('#__extensions', 'a'));
		$query->where($db->quoteName('a.name') . " = " . $db->quote($component_name));
		$db->setQuery($query);
		$is_enabled = $db->loadResult();
		return $is_enabled;
	}

}