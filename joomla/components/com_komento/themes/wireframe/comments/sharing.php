<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

$permalink = urlencode($comment->getPermalink());
$title = urlencode($comment->getSharingTitle());
$summary = urlencode($comment->getSharingSummary());
?>
<li class="kt-share-wrap" data-kt-sharing data-title="<?php echo $title;?>" data-summary="<?php echo $summary;?>" data-permalink="<?php echo $permalink;?>" data-width="660" data-height="300" data-breadcrumb="·">
	<a href="javascript:void(0);">
		<span><?php echo JText::_('COM_KOMENTO_COMMENT_SHARE'); ?></span>
	</a>
	<div class="kt-share-backdrop"></div>
	<div class="kt-share-balloon">
		<div class="kt-share-url">
			<div class="o-input-group">
				<span class="o-input-group__addon"><i class="fa fa-chain"></i></span>
				<input class="o-form-control" type="text" value="<?php echo $comment->getPermalink(); ?>" />
			</div>
		</div>
		<div class="kt-share-social">
			<div>
				<?php if ($this->config->get('share_facebook')) { ?>
				<a href="javascript:void(0);" class="share-facebook" data-link="https://www.facebook.com/sharer.php?m2w&s=100&u=PERMALINK&quote=SUMMARY">
					<i class="fa fa-facebook"></i>
				</a>
				<?php } ?>

				<?php if ($this->config->get('share_twitter')) { ?>
				<a href="javascript:void(0);" class="share-twitter" data-link="https://twitter.com/intent/tweet?url=PERMALINK&text=TITLE">
					<i class="fa fa-twitter"></i>
				</a>
				<?php } ?>

				<?php if ($this->config->get('share_linkedin')) { ?>
				<a href="javascript:void(0);" class="share-linkedin" data-link="https://linkedin.com/shareArticle?mini=true&url=PERMALINK&title=TITLE&summary=SUMMARY">
					<i class="fa fa-linkedin"></i>
				</a>
				<?php } ?>

				<?php if ($this->config->get('share_tumblr')) { ?>
				<a href="javascript:void(0);" class="share-tumblr" data-link="https://www.tumblr.com/share/link?url=PERMALINK&name=TITLE&description=SUMMARY">
					<i class="fa fa-tumblr"></i>
				</a>
				<?php } ?>

				<?php if ($this->config->get('share_reddit')) { ?>
				<a href="javascript:void(0);" class="share-reddit" data-link="https://reddit.com/submit?url=PERMALINK&title=TITLE">
					<i class="fa fa-reddit"></i>
				</a>
				<?php } ?>

				<?php if ($this->config->get('share_stumbleupon')) { ?>
				<a href="javascript:void(0);" class="share-stumbleupon" data-link="https://www.stumbleupon.com/submit?url=PERMALINK&title=TITLE">
					<i class="fa fa-stumbleupon"></i>
				</a>
				<?php } ?>
			</div>
		</div>
	</div>
</li>