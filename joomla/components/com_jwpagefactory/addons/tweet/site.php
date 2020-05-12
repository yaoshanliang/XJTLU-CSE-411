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
defined('_JEXEC') or die ('restricted access');

class JwpagefactoryAddonTweet extends JwpagefactoryAddons
{

	public function render()
	{
		$settings = $this->addon->settings;

		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		//Options
		$autoplay = (isset($settings->autoplay) && $settings->autoplay) ? ' data-jwpf-ride="jwpf-carousel"' : '';
		$username = (isset($settings->username) && $settings->username) ? $settings->username : '';
		$consumerkey = (isset($settings->consumerkey) && $settings->consumerkey) ? $settings->consumerkey : '';
		$consumersecret = (isset($settings->consumersecret) && $settings->consumersecret) ? $settings->consumersecret : '';
		$accesstoken = (isset($settings->accesstoken) && $settings->accesstoken) ? $settings->accesstoken : '';
		$accesstokensecret = (isset($settings->accesstokensecret) && $settings->accesstokensecret) ? $settings->accesstokensecret : '';
		$include_rts = (isset($settings->include_rts) && $settings->include_rts) ? $settings->include_rts : '';
		$ignore_replies = (isset($settings->ignore_replies) && $settings->ignore_replies) ? $settings->ignore_replies : '';
		$show_image = (isset($settings->show_image)) ? $settings->show_image : 1;
		$show_username = (isset($settings->show_username) && $settings->show_username) ? $settings->show_username : '';
		$show_avatar = (isset($settings->show_avatar) && $settings->show_avatar) ? $settings->show_avatar : '';
		$count = (isset($settings->count) && $settings->count) ? $settings->count : '';

		//Warning
		if ($consumerkey == '') return '<div class="jwpf-alert jwpf-alert-danger"><strong>Error</strong><br>Insert consumer key for twitter feed slider addon</div>';
		if ($consumersecret == '') return '<div class="jwpf-alert jwpf-alert-danger"><strong>Error</strong><br>Insert consumer secrete key for twitter feed slider addon</div>';
		if ($accesstoken == '') return '<div class="jwpf-alert jwpf-alert-danger"><strong>Error</strong><br>Insert access token for twitter feed slider addon</div>';
		if ($accesstokensecret == '') return '<div class="jwpf-alert jwpf-alert-danger"><strong>Error</strong><br>Insert access token secrete key for twitter feed slider addon</div>';

		//include tweet helper
		$tweet_helper = JPATH_ROOT . '/components/com_jwpagefactory/helpers/tweet/helper.php';
		if (!file_exists($tweet_helper)) {
			return '<p class="alert alert-danger">' . JText::_('COM_JWPAGEFACTORY_ADDON_TWEET_HELPER_FILE_MISSING') . '</p>';
		} else {
			require_once $tweet_helper;
		}

		//Get Tweets
		$tweets = jwpfAddonHelperTweet::getTweets($username, $consumerkey, $consumersecret, $accesstoken, $accesstokensecret, $count, $ignore_replies, $include_rts);

		if (isset($tweets->error) && $tweets->error) {
			return '<p class="jwpf-alert jwpf-alert-warning">' . $tweets->error . '</p>';
		}

		//Output
		if (count((array)$tweets) > 0) {
			$output = '<div class="jwpf-addon jwpf-addon-tweet jwpf-text-center ' . $class . '">';
			$output .= ($title) ? '<' . $heading_selector . ' class="jwpf-addon-title">' . $title . '</' . $heading_selector . '>' : '';
			$output .= ($show_avatar) ? '<a target="_blank" rel="noopener noreferrer" href="https://twitter.com/' . $tweets[0]->user->screen_name . '"><img class="jwpf-img-circle jwpf-tweet-avatar" src="' . $tweets[0]->user->profile_image_url_https . '" alt="' . $tweets[0]->user->name . '" loading="lazy"></a>' : '';
			$output .= ($show_username) ? '<span class="jwpf-tweet-username"><a target="_blank" rel="noopener noreferrer" href="https://twitter.com/' . $tweets[0]->user->screen_name . '">' . $tweets[0]->user->name . '</a></span>' : '';
			$output .= '<div id="jwpf-carousel-' . $this->addon->id . '" class="jwpf-carousel jwpf-tweet-slider jwpf-slide" ' . $autoplay . '>';
			$output .= '<div class="jwpf-carousel-inner">';

			foreach ($tweets as $key => $tweet) {
				$output .= '<div class="jwpf-item' . (($key == 0) ? ' active' : '') . '">';
				$tweet->text = preg_replace("/((http)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $tweet->text);
				$tweet->text = preg_replace("/[@]+([A-Za-z0-9-_]+)/", "<a href=\"https://twitter.com/\\1\" target=\"_blank\">\\0</a>", $tweet->text);
				$tweet->text = preg_replace("/[#]+([A-Za-z0-9-_]+)/", "<a href=\"https://twitter.com/search?q=%23\\1\" target=\"_blank\">\\0</a>", $tweet->text);
				$output .= '<small class="jwpf-tweet-created">' . jwpfAddonHelperTweet::timeago($tweet->created_at) . '</small>';
				if ((isset($tweet->entities) && $tweet->entities) && $show_image) {
					if (isset($tweet->entities->media) && $tweet->entities->media) {
						foreach ($tweet->entities->media as $media) {
							if ($media->type == 'photo') {
								$img_src = (isset($media->sizes->small) && $media->sizes->small) ? $media->media_url . ':thumb' : $media->media_url;
								$output .= '<div class="jwpf-item-image">';
								$output .= ($media->url) ? '<a href="' . $media->url . '" target="_blank" rel="noopener noreferrer">' : '';
								$output .= '<img class="jwpf-tweet-image" src="' . $img_src . '" alt="' . preg_replace('/<\/?a[^>]*>/', '', $tweet->text) . '" loading="lazy">';
								$output .= ($media->url) ? '</a>' : '';
								$output .= '</div>';
							}
						}
					}
				}
				$output .= '<div class="jwpf-tweet-text">' . $tweet->text . '</div>';
				$output .= '</div>';

			}

			$output .= '</div>';
			$output .= '<a href="#jwpf-carousel-' . $this->addon->id . '" class="left jwpf-carousel-control" role="button" data-slide="prev" aria-label="' . JText::_('COM_JWPAGEFACTORY_ARIA_PREVIOUS') . '"><i class="fa fa-angle-left" aria-hidden="true"></i></a>';
			$output .= '<a href="#jwpf-carousel-' . $this->addon->id . '" class="right jwpf-carousel-control" role="button" data-slide="next" aria-label="' . JText::_('COM_JWPAGEFACTORY_ARIA_NEXT') . '"><i class="fa fa-angle-right" aria-hidden="true"></i></a>';
			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}

		return;

	}
}
