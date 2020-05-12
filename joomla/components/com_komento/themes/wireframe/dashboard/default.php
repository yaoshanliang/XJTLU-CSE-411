<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div id="kt">
	<div class="kt-dashboard" data-kt-dashboard>
		<?php if ($this->config->get('enable_gdpr_download', false)) { ?>
			<?php echo $this->output('site/dashboard/toolbar', array('layout' => 'dashboard')); ?>
		<?php } ?>
		
		<div class="kt-dashboard-header">
			<div class="kt-dashboard-header__title">
				<?php echo JText::_('COM_KOMENTO_DASHBOARD_HEADING');?>
			</div>
			<div class="kt-dashboard-header__desc">
				<?php echo JText::_('COM_KOMENTO_DASHBOARD_HEADING_INFO');?>
			</div>
		</div>

		<?php if ($isModerator) { ?>
			<div class="kt-dashboard-filter">
				<div class="">
					<div class="kt-dashboard-tab">

						<div class="kt-dashboard-tab__item <?php echo $filter == 'all' ? 'is-active' : '';?>">
							<a href="<?php echo JRoute::_('index.php?option=com_komento&view=dashboard');?>" class="kt-dashboard-tab__link">
								<?php echo JText::_('COM_KOMENTO_ALL'); ?>
							</a>
						</div>

						<div class="kt-dashboard-tab__item <?php echo $filter == 'pending' ? 'is-active' : '';?>">
							<a href="<?php echo JRoute::_('index.php?option=com_komento&view=dashboard&filter=pending');?>" class="kt-dashboard-tab__link">
								<?php echo JText::sprintf('COM_KOMENTO_DASHBOARD_PENDING_COUNT', $totalPending);?>
							</a>
						</div>

						<div class="kt-dashboard-tab__item <?php echo $filter == 'spam' ? 'is-active' : '';?>">
							<a href="<?php echo JRoute::_('index.php?option=com_komento&view=dashboard&filter=spam');?>" class="kt-dashboard-tab__link">
								<?php echo JText::sprintf('COM_KOMENTO_DASHBOARD_SPAM_COUNT', $totalSpams); ?>
							</a>
						</div>

						<div class="kt-dashboard-tab__item <?php echo $filter == 'reports' ? 'is-active' : '';?>">
							<a href="<?php echo JRoute::_('index.php?option=com_komento&view=dashboard&filter=reports');?>" class="kt-dashboard-tab__link">
								<?php echo JText::sprintf('COM_KOMENTO_DASHBOARD_REPORT_COUNT', $totalReports);?>
							</a>
						</div>
						
					</div>
				</div>
			</div>
		<?php } ?>
			
		<div class="o-alert o-alert--success t-hidden" data-kt-dashboard-notice></div>

		<?php if ($comments) { ?>
			<div class="kt-dashboard-action-group <?php echo $showActionBar ? '' : 'hidden'; ?>" data-kt-dashboard-actions>
				<?php if ($showActionBar) { ?>
					<div class="o-checkbox">
						<input id="kt-dashboard-check-all" name="kt-all-checked" type="checkbox" data-kt-dashbaord-checkall />
						<label for="kt-dashboard-check-all">Select All</label>
					</div>

				<?php } ?>	

				<div class="kt-dashboard-action-group__nav">
					<?php if ($isModerator) { ?>
						<?php if ($filter == 'pending') { ?>
						<div>
							<a href="javascript:void(0);" data-kt-dashboard-moderate data-action="approve"><?php echo JText::_('COM_KOMENTO_COMMENT_APPROVE');?></a>
						</div>
						<div>
							<a href="javascript:void(0);" data-kt-dashboard-moderate data-action="reject"><?php echo JText::_('COM_KOMENTO_COMMENT_REJECT');?></a>
						</div>
						<?php } ?>

						<?php if ($filter == 'reports') { ?>
						<div>
							<a href="javascript:void(0);" data-kt-dashboard-reports-clear><?php echo JText::_('COM_KOMENTO_CLEAR_REPORTS');?></a>
						</div>
						<?php } ?>

						<?php if ($filter == 'spam') { ?>
						<div>
							<a href="javascript:void(0);" data-kt-dashboard-notspam><?php echo JText::_('COM_KOMENTO_MARK_NOT_SPAM');?></a>
						</div>
						<?php } else { ?>
						<div>
							<a href="javascript:void(0);" data-kt-dashboard-spam><?php echo JText::_('COM_KOMENTO_MARK_SPAM');?></a>
						</div>
						<?php } ?>
					<?php } ?>

					<?php if ($filter != 'pending' && $filter != 'spam') { ?>
						<?php if ($isModerator) { ?>
							<div>
								<a href="javascript:void(0);" data-kt-dashboard-publish><?php echo JText::_('COM_KOMENTO_COMMENT_PUBLISH');?></a>
							</div>
							<div>
								<a href="javascript:void(0);" data-kt-dashboard-unpublish><?php echo JText::_('COM_KOMENTO_COMMENT_UNPUBLISH');?></a>
							</div>
						<?php } ?>

						<?php if ($canDeleteComment) { ?>
						<div>
							<a href="javascript:void(0);" class="t-text--danger" data-kt-dashboard-delete><?php echo JText::_('COM_KOMENTO_COMMENT_DELETE');?></a>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		<div class="kt-db-comments <?php echo empty($comments) ? 'is-empty' : ''; ?>">
			<?php if ($comments) { ?>
				<?php foreach ($comments as $comment) { ?>
				<div class="kt-db-comments__item 
				<?php echo $comment->isPublished() ? 'is-published' : '';?> 
				<?php echo $comment->isUnpublished() ? 'is-unpublished' : '';?> 
				<?php echo $comment->isPending() ? 'is-pending' : '';?> 
				<?php echo $comment->isSpam() ? 'is-spam' : '';?>" data-kt-dashboard-item>
					<div class="kt-db-comment has-checkbox">
						<div>
							<div class="o-checkbox">
								<input type="checkbox" name="kt-all-checked" id="kt-comment-<?php echo $comment->id;?>" value="<?php echo $comment->id;?>" data-kt-dashboard-item-checkbox />
								<label for="kt-comment-<?php echo $comment->id;?>"></label>
							</div>		
						</div>

						<div>
							<div class="o-flag">
								<div class="o-flag__image o-flag--top">
									<?php echo $this->html('html.avatar', $comment->getAuthor(), $comment->getAuthorName()); ?>
								</div>
								<div class="o-flag__body">
									<div class="kt-db-comment-content">
										<div class="kt-db-comment-content__hd">
											<ol class="g-list-inline g-list-inline--delimited">
												<li>
													<?php echo $this->html('html.name', $comment->created_by, $comment->getAuthorName(), $comment->getAuthorEmail(), $comment->url); ?>
												</li>
												
												<?php if ($this->config->get('enable_ratings') && $comment->ratings) { ?>
												<li class="t-text--muted" data-breadcrumb="·">
													<div class="kt-ratings-stars" style="display: inline-block;" data-kt-ratings-item data-score="<?php echo $comment->ratings / 2;?>"></div>
												</li>
												<?php } ?>

												<li class="t-text--muted" data-breadcrumb="·">
													<span><?php echo $comment->getCreatedDate()->toLapsed();?></span>
												</li>
											</ol>
										</div>
										<div class="kt-db-comment-content__bd">
											<p><?php echo $comment->getContent();?></p>
										</div>
										<div class="kt-db-comment-content__ft">
											<div class="kt-db-comment-content-action">
												<ol class="g-list-inline g-list-inline--delimited">
													<li class="t-text--muted">
														<a href="<?php echo $comment->getPermalink();?>" target="_blank">#<?php echo $comment->id;?></a>
													</li>

													<li class="t-text--success kt-comment-published" data-breadcrumb="·">
														<?php echo JText::_('COM_KOMENTO_PUBLISHED');?>
													</li>

													<li class="t-text--danger kt-comment-unpublished" data-breadcrumb="·">
														<?php echo JText::_('COM_KOMENTO_UNPUBLISHED');?>
													</li>

													<li class="t-text--warning kt-comment-pending" data-breadcrumb="·">
														<?php echo JText::_('COM_KOMENTO_MODERATE');?>
													</li>

													<li class="t-text--danger kt-comment-spam" data-breadcrumb="·">
														<?php echo JText::_('COM_KOMENTO_SPAM');?>
													</li>

													<li class="t-text--muted" data-breadcrumb="·">
														<?php echo JText::sprintf('COM_KOMENTO_DASHBOARD_IN_RESPONSE_TO', '<a href="'. $comment->getItemPermalink() .'">'. $comment->getItemTitle() .'</a>'); ?>
													</li>
												</ol>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
			<?php } ?>
			<div class="o-empty">
					<div class="o-empty__content">
						<i class="o-empty__icon fa fa-comments"></i>
						<div class="o-empty__text"><?php echo JText::_('COM_KT_DASHBOARD_NO_COMMENTS_POSTED_YET'); ?></div>
					</div>
			</div>
		</div>

		<?php if ($pagination) {?>
			<?php echo $pagination->getListFooter('site');?>
		<?php } ?>
	</div>
</div>

