<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php KT::trigger('onBeforeKomentoBox', array('component' => $component, 'cid' => $cid, 'system' => &$system, 'comments' => &$comments)); ?>

<div id="kt" class="kt-frontend theme-<?php echo $this->config->get('layout_theme'); ?>
	<?php echo $this->isMobile() ? ' is-mobile' : '';?>
	<?php echo $this->isTablet() ? ' is-tablet' : '';?>" 
	data-kt-wrapper
	data-component="<?php echo $component;?>" 
	data-cid="<?php echo $cid;?>" 
	data-url="<?php echo base64_encode(JRequest::getUri());?>"
	data-live="<?php echo $this->config->get('enable_live_notification') ? 1 : 0;?>" 
	data-live-interval="<?php echo $this->config->get('live_notification_interval');?>"
>
	<a id="comments"></a>
	
	<?php if ($componentHelper->getCommentAnchorId()) { ?>
		<a id="<?php echo $componentHelper->getCommentAnchorId(); ?>"></a>
	<?php } ?>

	<?php if ($this->config->get('enable_conversation_bar') && $authors) { ?>
	<div class="t-lg-mb--lg">
		<div class="kt-title-bar">
			<h3 class="kt-title-bar__title"><?php echo JText::_('COM_KOMENTO_COMMENT_CONVERSATION_BAR_TITLE'); ?></h3>
		</div>

		<ul class="g-list-inline">
			<?php foreach ($authors->registered as $item) { ?>
			<li class="t-lg-mb--md t-lg-mr--lg">
				<?php echo $this->html('html.avatar', $item->created_by); ?>
			</li>
			<?php } ?>

			<?php foreach ($authors->guest as $item) { ?>
			<li class="t-lg-mb--md t-lg-mr--lg">
				<?php echo $this->html('html.avatar', $item->created_by); ?>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php } ?>
	
	<div class="o-box o-box--elegant-bar">
		<div class="t-text--center">
			<ol class="g-list-inline g-list-inline--delimited">
				<li data-breadcrumb="·">
					<h3 class="kt-comments-title">
						<?php echo JText::_('COM_KOMENTO_COMMENTS'); ?> (<span class="commentCounter" data-kt-counter><?php echo $commentCount; ?></span>)
					</h3>
				</li>
				<li data-breadcrumb="·">
					<a href="#commentform" class="kt-comments-reply-link"><?php echo JText::_('COM_KOMENTO_COMMENTS_ADD_YOURS');?></a>
				</li>
			</ol>
		</div>
		<?php if ($this->config->get('show_sort_buttons') || $this->config->get('enable_rss') || $showSubscribe) { ?>
			<div class="o-box--border">
				<div class="kt-comment-tools o-grid t-text--center">
					<div class="o-grid__cell o-grid__cell--center t-text--center">
						<ol class="g-list-inline g-list-inline--delimited kt-comment-sorting">
							<?php if ($this->config->get('show_sort_buttons')) { ?>
								<li data-breadcrumb="·">
									<a href="javascript:void(0);" class="<?php echo $activeSort == 'oldest' ? 'is-active' : '';?>" data-kt-sorting data-type="oldest">
										<?php echo JText::_('COM_KOMENTO_SORT_OLDEST_FIRST');?>
									</a>
								</li>
								<li data-breadcrumb="·">
									<a href="javascript:void(0);" class="<?php echo $activeSort == 'latest' ? 'is-active' : '';?>" data-kt-sorting data-type="latest">
										<?php echo JText::_('COM_KOMENTO_SORT_NEWEST_FIRST');?>
									</a>
								</li>
								<li data-breadcrumb="·">
									<a href="javascript:void(0);" class="<?php echo $activeSort == 'popular' ? 'is-active' : '';?>" data-kt-sorting data-type="popular">
										<?php echo JText::_('COM_KT_SORT_MOST_LIKES');?>
									</a>
								</li>
							<?php } ?>
							<?php if ($this->config->get('enable_rss')) { ?>
								<li data-breadcrumb="·">
									<a href="<?php echo KT::router()->getFeedUrl($component, $cid); ?>" class="kt-rss-btn" target="_blank" data-kt-provide="tooltip" data-title="<?php echo JText::_('COM_KOMENTO_SUBSCRIBE_VIA_RSS'); ?>">
										<i class="fa fa-rss-square"></i>
									</a>
								</li>
							<?php } ?>

							<?php if ($showSubscribe) { ?>
								<?php if (is_null($subscription)) { ?>
									<li data-breadcrumb="·">
										<a href="javascript:void(0);" class="kt-email-btn" target="_blank" data-kt-subscribe data-kt-provide="tooltip" data-title="<?php echo JText::_('COM_KOMENTO_SUBSCRIBE_EMAIL'); ?>">
											<i class="fa fa-envelope"></i>
										</a>
									</li>
								<?php } else { ?>
									<li data-breadcrumb="·">
										<a href="javascript:void(0);" class="kt-email-btn" target="_blank" data-kt-unsubscribe data-kt-provide="tooltip" data-title="<?php echo JText::_('COM_KOMENTO_FORM_UNSUBSCRIBE'); ?>">
											<i class="fa fa-envelope"></i>
										</a>
									</li>
								<?php } ?>
							<?php } ?>
						</ol>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>

	<?php if (!$this->my->allow('read_comment') && !$this->my->allow('add_comment')) { ?>

		<div class="kt-comments-view-disallowed">
			<i class="fa fa-lock"></i>&nbsp;
			<?php echo JText::_('COM_KOMENTO_NOT_ALLOWED_TO_VIEW_COMMENTS'); ?>
		</div>

		<?php if ($this->my->guest && $this->config->get('enable_login_form')) { ?>
			<?php echo KT::login()->getLoginForm();?>
		<?php } ?>

	<?php } else { ?>

		<?php if (!$this->my->allow('read_comment')) { ?>
			<div class="commentList kmt-list-wrap">
				<div class="kmt-not-allowed"><?php echo JText::_('COM_KOMENTO_COMMENT_NOT_ALLOWED'); ?></div>
			</div>
		<?php }?>

		<?php if ($this->my->allow('read_comment')) { ?>
			<div class="kt-title-bar">
				<div class="o-grid">
					<div class="o-grid__cell-auto-size o-grid__cell--center">
						<?php if ($this->config->get('enable_ratings')) { ?>
							<?php echo $this->output('site/ratings/overall'); ?>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="kt-comments-container" data-kt-comments-container>
				<?php if ($pinnedComments) { ?>
				<div class="kt-comments" data-kt-comments-pinned>
					<?php foreach ($pinnedComments as $pinnedComment) { ?>
						<?php echo $this->output('site/comments/item', array('comment' => $pinnedComment, 'pinned' => true, 'application' => $application)); ?>
					<?php } ?>
				</div>
				<hr class="kt-divider">
				<?php } ?>

				<div class="kt-comments <?php echo !$comments ? 'is-empty' : '';?>" data-kt-comments>
					<?php if ($comments) { ?>
						<?php foreach ($comments as $comment) { ?>
							<?php echo $this->output('site/comments/item', array('comment' => $comment, 'application' => $application)); ?>
						<?php } ?>
					<?php } ?>

					<div class="o-empty">
						<div class="o-empty__content">
							<i class="o-empty__icon fa fa-comments-o"></i>
							<div class="o-empty__text t-lg-mt--md"><?php echo JText::_('COM_KOMENTO_NO_COMMENTS_POSTED_YET'); ?></div>
						</div>
					</div>
				</div>

				<?php if ($showMoreButton) { ?>
					<a href="#!kmt-start=<?php echo $moreStartCount; ?>" class="btn btn-kt-default-o btn-block t-lg-mt--xl t-lg-mb--xl" data-kt-loadmore data-nextstart="<?php echo $moreStartCount ?>">
						<div class="o-loader o-loader--sm"></div>
						<b><?php echo JText::_('COM_KOMENTO_LIST_LOAD_MORE'); ?></b>
					</a>
				<?php } ?>
			</div>
		<?php } ?>
		<?php echo $this->output('site/form/default'); ?>
	<?php } ?>

	<?php if (KT::push()->isEnabled()) { ?>
		<?php echo KT::push()->generateScripts();?>
	<?php } ?>

	<span id="komento-token" style="display:none;"><input type="hidden" name="<?php echo KT::token();?>" value="1" /></span>
</div>
