<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php KT::trigger('onBeforeKomentoBar', array('component' => $component, 'cid' => $cid, 'commentCount', &$commentCount)); ?>

<?php if ($showReadmore || $this->config->get('layout_frontpage_comment') || $this->config->get('layout_frontpage_hits') || $this->config->get('layout_frontpage_preview')) { ?>
<div id="kt">
	<?php if ($showReadmore || $this->config->get('layout_frontpage_comment') || $this->config->get('layout_frontpage_hits')) { ?>
	<ol class="g-list-inline g-list-inline--delimited kt-listing-tools t-text--<?php echo $this->config->get('layout_frontpage_alignment'); ?>">
		
		<?php if ($this->config->get('layout_frontpage_alignment') == 'right' && $this->config->get('layout_frontpage_ratings') && $this->config->get('enable_ratings')) { ?>
		<li class="kt-listing-ratings t-lg-pull-left">
			<div class="kt-ratings-stars" data-kt-ratings="<?php echo $cid;?>" data-score="<?php echo $totalRating / 2;?>"></div>
			<span class="kt-ratings-title"><?php echo $totalRating / 2;?> / 5</span>
		</li>
		<?php } ?>

		<?php if ($this->config->get('layout_frontpage_comment')) { ?>
		<li class="kt-listing-comment">
			<a href="<?php echo $componentHelper->getContentPermalink() . '#comments'; ?>">
				<i class="fa fa-comments-o"></i>&nbsp; <?php echo $commentCount;?> <?php echo JText::_('COM_KOMENTO_FRONTPAGE_COMMENT');?>
			</a>
		</li>
		<?php } ?>

		<?php if ($this->config->get('layout_frontpage_hits')) { ?>
		<li class="kt-listing-views">
			<a href="<?php echo $componentHelper->getContentPermalink();?>" title="<?php echo $this->escape($componentHelper->getContentTitle());?>">
				<i class="fa fa-bar-chart-o"></i>&nbsp; <?php echo JText::sprintf('COM_KOMENTO_FRONTPAGE_VIEWS', $componentHelper->getContentHits());?>
			</a>
		</li>
		<?php } ?>

		<?php if ($showReadmore) { ?>
		<li class="kt-listing-readmore">
			<a href="<?php echo $componentHelper->getContentPermalink();?>" title="<?php echo $this->escape($componentHelper->getContentTitle());?>">
				<i class="fa fa-file-text-o"></i>&nbsp; <?php echo JText::_('COM_KOMENTO_FRONTPAGE_READMORE');?>
			</a>
		</li>
		<?php } ?>

		<?php if ($this->config->get('layout_frontpage_alignment') == 'left' && $this->config->get('layout_frontpage_ratings') && $this->config->get('enable_ratings')) { ?>
		<li class="kt-listing-ratings t-lg-pull-right">
			<div class="kt-ratings-stars" data-kt-ratings="<?php echo $cid;?>" style="display: inline-block;" data-score="<?php echo $totalRating / 2;?>"></div>
			<span class="kt-ratings-title"><?php echo $totalRating / 2;?> / 5</span>
		</li>
		<?php } ?>
	</ol>
	<?php } ?>

	<?php if ($this->config->get('layout_frontpage_preview') && $this->my->allow('read_comment') && $comments) { ?>
	<div class="kt-comments-listing">
		<?php foreach ($comments as $comment) { ?>
		<div class="kt-comments-listing__item">
			<i class="fa fa-comment-o"></i>&nbsp; 
			<a href="<?php echo $comment->getPermalink();?>"><?php echo $comment->getAuthorName();?></a> &mdash; 
			<?php echo $comment->getContent($this->config->get('preview_comment_length')); ?><?php echo JText::_('COM_KOMENTO_ELLIPSES');?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>
<?php } ?>

<?php KT::trigger('onAfterKomentoBar', array('component' => $component, 'cid' => $cid, 'commentCount', &$commentCount)); ?>
