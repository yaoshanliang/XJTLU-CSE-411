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
$pinned = isset($pinned) ? $pinned : false;
$indentStyling = $pinned ? '' : $comment->getIndentStyling('margin', $pinned);
?>
<div class="kt-comments__item <?php echo $comment->getCustomCss();?> 
		<?php echo $comment->isFeatured() ? 'is-featured' : '';?> 
		<?php echo $comment->isEdited() ? 'is-edited' : '';?> 
		<?php echo $comment->isParent() ? 'is-parent' : 'is-child';?> 
		<?php echo $comment->isPending() ? 'is-pending' : '';?>
		<?php if ($this->config->get('enable_minimize')) { ?>
			<?php echo $comment->isMinimized() ? 'is-minimized' : '';?>
			<?php echo !$comment->isMinimized() ? 'can-minimize' : 'can-expand';?>
		<?php } ?>
		"
		data-kt-comment-item
		data-pinned="<?php echo $pinned; ?>"
		data-id="<?php echo $comment->id;?>" 
		data-parentid="kmt-<?php echo $comment->parent_id; ?>"
		data-depth="<?php echo $comment->depth; ?>"
		itemscope itemtype="http://schema.org/Comment"
		style="<?php echo $indentStyling;?>">
	<div class="kt-comment">
		<div class="kt-comment__hd">
			<?php if (!$pinned) { ?>
			<a id="comment-<?php echo $comment->id;?>"></a>
			<?php } ?>
			<div class="kt-comment__hd-col">
				<div class="o-media o-media--top">
					<div class="o-media__image ">
						<?php echo $this->html('html.avatar', $comment->created_by, $comment->getAuthorName(), $comment->getAuthorEmail(), $comment->url); ?>
					</div>
					<div class="o-media__body">
						<div class="kt-reply-to" <?php if ($comment->parent_id != 0) { ?>
								data-kt-provide="tooltip" 
								data-title="<?php echo JText::sprintf('COM_KOMENTO_REPLYING_TO', $comment->getParent()->getAuthorName());?>"
								<?php } ?>>

							<?php echo $this->html('html.name', $comment->created_by, $comment->getAuthorName(), $comment->getAuthorEmail(), $comment->url, $application); ?>

							<?php if ($comment->parent_id != 0) { ?>
							&nbsp;<i class="fa fa-caret-right"></i>&nbsp; 

							<a href="<?php echo $this->escape($comment->getParentAuthorLink()); ?>">
								<?php echo $comment->getParent()->getAuthorName();?>
							</a>
							<?php } ?>
						</div>
						<ol class="g-list-inline g-list-inline--delimited kt-comment-meta">
							<li class="kt-comment-date" data-breadcrumb="·">
								<time itemprop="dateCreated" datetime="<?php echo $this->formatDate('c', $comment->created); ?>">
									<?php echo $this->html('html.date', $comment->created);?>
								</time>
								<time class="hidden" itemprop="datePublished" datetime="<?php echo $this->formatDate('c', $comment->publish_up); ?>"></time>
							</li>
							<li class="kt-comment-permalink" data-breadcrumb="·">
								<a href="<?php echo $comment->getPermalink();?>" title="<?php echo JText::_('COM_KOMENTO_COMMENT_PERMALINK');?>" data-kt-permalink>
									#<?php echo $comment->id;?>
								</a>
							</li>
						</ol>
						<div class="kt-comment-minimize">
							<span><?php echo JText::_('COM_KT_COMMENT_MINIMIZED_BY_MODERATOR'); ?></span>
							
						</div>
					</div>
				</div>
			</div>
			<div class="kt-comment__hd-col-last">

				<?php if ($comment->isFeatured()) { ?>
					<div class="kt-pinned-label-wrap" data-kt-provide="tooltip" data-title="<?php echo JText::_('COM_KOMENTO_FEATURED_COMMENT');?>">
						<i class="fa fa-thumb-tack"></i>
					</div>
				<?php } ?>

				<a href="javascript:void(0);" class="kt-expand-label-wrap" data-kt-user-expand-comment data-kt-provide="tooltip" data-title="<?php echo JText::_('COM_KT_EXPAND_COMMENT');?>">
					<i class="fa fa-angle-double-down"></i>
				</a>

				<?php if ($comment->canManage()) { ?>
					<?php echo $this->output('site/comments/admin', array('comment' => $comment)); ?>
				<?php } ?>
			</div>
		</div>

		<div class="kt-comment__bd">
			<div class="kt-comment-content kt-form-bg--<?php echo $comment->getParams()->get('preset'); ?>">
				<div class="kt-comment-content__bd" itemprop="text">
					<div class="kt-comment-message" data-kt-comment-content>
						<?php echo $this->html('string.truncate', $comment->getContent(), $this->config->get('comment_truncation_length')); ?>
					</div>

					<?php if ($this->config->get('enable_info')) { ?>
					<span class="kt-edited-info">
						<span data-kt-comment-edited>
							<?php if ($comment->isEdited()) { ?>
								<?php echo JText::sprintf('COM_KOMENTO_COMMENT_EDITTED_BY', $comment->getModifiedDate()->toLapsed(), $this->html('html.name', $comment->modified_by, $comment->getAuthorName(), $comment->getAuthorEmail(), $comment->url, $application)); ?>
							<?php } ?>
						</span>
					</span>

					<span class="t-hidden" itemprop="creator" itemscope itemtype="https://schema.org/Person">
						<span itemprop="name"><?php echo $comment->getAuthorName(); ?></span>
					</span>

					<time class="t-hidden" itemprop="dateModified" datetime="<?php echo $this->formatDate('c', $comment->modified); ?>"></time>
					<?php } ?>

					<?php if ($this->config->get('upload_enable')) { ?>
						<?php echo $this->output('site/comments/attachments', array('comment' => $comment, 'files' => $comment->getAttachments('all'))); ?>
					<?php } ?>

					<?php if ($this->config->get('enable_location') && $comment->hasLocation()) { ?>
					<div class="kt-location">
						<?php echo $this->output('site/comments/location', array('comment' => $comment)); ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="kt-comment__ft" data-comment-footer>
			<div class="kt-comment-content-action">
				<ol class="g-list-inline g-list-inline--delimited kt-comment-meta">

					<?php if ($this->isMobile()) { ?>
						<?php if ($this->config->get('enable_share') && $this->my->allow('share_comment')) { ?>
							<?php echo $this->output('site/comments/sharing', array('comment' => $comment)); ?>
						<?php } ?>

						<?php if (KT::likes()->showLikeCount()) { ?>
							<li class="kt-like-wrap" data-breadcrumb="·">
								<div class="btn-group">
									<a class="dropdown-toggle_" data-kt-toggle="dropdown" data-kt-likes-browser>
										<i class="fa fa-heart"></i> <span data-kt-likes-counter><?php echo $comment->likes; ?></span>
									</a>

									<?php if (!JFactory::getUser()->guest) { ?>
										<div class="dropdown-menu dropdown-menu-left dropdown-menu--avatar-list">
											<div data-kt-likes-browser-contents>
											</div>
										</div>
									<?php } ?>
								</div>
							</li>
						<?php } ?>
					<?php } ?>

					<?php if (KT::likes()->isEnabled()) { ?>
						<li class="kt-likes-wrapper <?php echo $comment->liked ? 'is-liked' : '';?>" data-kt-likes-wrapper data-breadcrumb="·">
							<a href="javascript:void(0);" class="unlike-comment" data-kt-likes-action data-type="unlike"><?php echo JText::_('COM_KOMENTO_COMMENT_UNLIKE'); ?></a>
							<a href="javascript:void(0);" class="like-comment" data-kt-likes-action data-type="like"><?php echo JText::_('COM_KOMENTO_COMMENT_LIKE'); ?></a>
						</li>
					<?php } ?>
					<?php if ($comment->canReplyTo() && !$pinned) { ?>
						<li class="kt-reply-wrap" data-breadcrumb="·">
							<a href="javascript:void(0);" data-kt-reply><?php echo JText::_('COM_KOMENTO_COMMENT_REPLY'); ?></a>
						</li>
					<?php } ?>
					<?php if (KT::reports()->isEnabled() && !$pinned) { ?>
						<li class="kt-report-wrap" data-breadcrumb="·">
							<a href="javascript:void(0);" data-kt-report><?php echo JText::_('COM_KOMENTO_COMMENT_REPORT'); ?></a>
						</li>
					<?php } ?>


					<?php if (!$this->isMobile()) { ?>
						<?php if ($this->config->get('enable_share') && $this->my->allow('share_comment')) { ?>
							<?php echo $this->output('site/comments/sharing', array('comment' => $comment)); ?>
						<?php } ?>

						<?php if (KT::likes()->showLikeCount()) { ?>
							<li class="kt-like-wrap" data-breadcrumb="·">
								<div class="btn-group">
									<a class="dropdown-toggle_" data-kt-toggle="dropdown" data-kt-likes-browser>
										<i class="fa fa-heart"></i> <span data-kt-likes-counter><?php echo $comment->likes; ?></span>
									</a>

									<?php if (!JFactory::getUser()->guest) { ?>
										<div class="dropdown-menu dropdown-menu-left dropdown-menu--avatar-list">
											<div data-kt-likes-browser-contents>
											</div>
										</div>
									<?php } ?>
								</div>
							</li>
						<?php } ?>
					<?php } ?>
					
					<?php if ($this->config->get('enable_ratings') && $comment->ratings) { ?>
						<li class="kt-ratings-wrap t-lg-pull-right" data-breadcrumb="">
							<?php echo $this->output('site/comments/ratings', array('comment' => $comment)); ?>
						</li>
					<?php }?>

				</ol>
			</div>

			<?php if ($comment->childs > 0) { ?>
			<?php $replies = $comment->getReplies(); ?>
				<?php if ($replies) { ?>
					<?php if ($comment->childs > count($replies)) { ?>
						<div class="" style="<?php echo $this->config->get('enable_threaded') ? 'margin-left:' . $this->config->get('thread_indentation'). 'px;' : ''; ?>" data-kt-comment-item data-kt-view-reply data-id="<?php echo $comment->id; ?>" data-rownumber="<?php echo $comment->rownumber; ?>">
							<a href="javascript:void(0);" class="kt-comment-view-all">
								<?php echo JText::sprintf('COM_KOMENTO_VIEW_OTHER_REPLIES', $comment->childs - count($replies)); ?>
								<div class="o-loader o-loader--sm o-loader--inline"></div>
							</a>
						</div>
					<?php } ?>

					<div class="kt-comments">
						<?php foreach ($replies as $reply) { ?>
							<?php echo $this->output('site/comments/item', array('comment' => $reply, 'application' => $application, 'pinned' => $pinned)); ?>
						<?php } ?>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
