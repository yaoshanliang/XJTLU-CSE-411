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
<div class="row">
	<div class="col-md-7">
		<div class="dash-activity">
			<div class="dash-activity-head">
				<b><?php echo JText::_('COM_KOMENTO_ACTIVITIES');?></b>
			</div>
			<ul class="dash-activity-filter list-unstyled">
				<li>
					<b><?php echo JText::_('COM_KOMENTO_FILTERS');?>:</b>
				</li>
				<li class="active">
					<a href="#comments" id="posts-comments" data-bs-toggle="tab"><?php echo JText::_('COM_KOMENTO_LATEST');?></a>
				</li>
				<li>
					<a href="#pending" id="pending-tab" data-bs-toggle="tab"><?php echo JText::_('COM_KOMENTO_PENDING');?></a>
				</li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane in active" id="comments">
					<?php if ($comments) { ?>
						<?php foreach ($comments as $comment) { ?>
						<div class="dash-stream">
							<div class="o-flag">
								<div class="o-flag__image o-flag--top">
									<?php echo $comment->getAuthor()->getAvatarHtml('','', '', 'bg'); ?>
								</div>
								<div class="o-flag__body">
									<div class="dash-stream-content" data-comment-wrapper>
										<div class="dash-stream-headline">
											<?php echo JText::sprintf('COM_KOMENTO_DASHBOARD_POST_COMMENT', $comment->getAuthor()->getName(), '<a href="' . $comment->getPermalink() . '" target=_blank>' . $comment->contenttitle . '</a>');?>
										</div>
										<div class="dash-stream-clip t-hidden" data-comment-content>
											<i class="dash-stream-icon fa fa-comment"></i>
											<?php echo $comment->getContent();?>
										</div>
										<div class="dash-stream-time t-lg-mt--md">
											<span class="t-lg-mr--md">
												<a href="javascript:void(0);" data-preview-comment>View Comment</a>
											</span>
											<span class="t-lg-mr--md">
												<i class="fa fa-cube"></i> <?php echo $comment->componenttitle;?>
											</span>
											<span>
												<i class="fa fa-clock-o"></i> <?php echo $comment->getCreatedDate()->toFormat(JText::_('DATE_FORMAT_LC2'));?>
											</span>
										</div>
									</div>
								</div>
							</div>

						</div>
						<?php } ?>
					<?php } else { ?>
						<div class="dash-stream empty"><?php echo JText::_('COM_KOMENTO_COMMENTS_NO_COMMENT');?></div>
					<?php } ?>
				</div>

				<div class="tab-pane" id="pending">
					<?php if ($pendings) { ?>
					<?php } else { ?>
						<div class="dash-stream empty"><?php echo JText::_('COM_KOMENTO_COMMENTS_NO_PENDING_COMMENTS');?></div>
					<?php } ?>
				</div>

			</div>
		</div>
	</div>

	<div class="col-md-5">
		<div class="panel">
			<div class="panel-body dash-summary">
				<section class="dash-version is-loading" data-version-status>
					<div class="row-table">
						<div class="col-cell cell-icon cell-tight">
							<i class="fa fa-thumbs-down"></i>
							<i class="fa fa-thumbs-up"></i>
							<b class="o-loader"></b>
						</div>

						<div class="col-cell">
							<h4 class="heading-outdated text-danger"><?php echo JText::_('COM_KOMENTO_DASHBOARD_VERSION_OUTDATED');?></h4>
							<h4 class="heading-updated"><?php echo JText::_('COM_KOMENTO_DASHBOARD_VERSION_LATEST');?></h4>
							<h4 class="heading-loading"><?php echo JText::_('COM_KOMENTO_DASHBOARD_VERSION_CHECKING');?></h4>
							<div class="version-installed hide" data-version-installed>
								<?php echo JText::_('COM_KOMENTO_DASHBOARD_VERSION_INSTALLED');?>: <span data-current-version></span>
								<span class="version-latest text-success">&nbsp; <?php echo JText::_('COM_KOMENTO_DASHBOARD_LATEST_VERSION');?>: <span data-latest-version></span></span>
							</div>
						</div>

						<div class="col-cell cell-btn cell-tight">
							<a href="<?php echo JRoute::_('index.php?option=com_komento&controller=system&task=upgrade');?>" class="btn"><?php echo JText::_('COM_KOMENTO_DASHBOARD_UPDATE_NOW');?></a>
						</div>
					</div>
				</section>

				<section class="dash-stat">
					<div class="text-center clearfix">
						<div class="dash-stat-item">
							<a href="<?php echo JRoute::_('index.php?option=com_komento&view=comments');?>">
								<b><?php echo $totalComments;?></b>
								<div><?php echo JText::_('COM_KOMENTO_DASHBOARD_COMMENTS');?></div>
							</a>
						</div>

						<div class="dash-stat-item">
							<a href="<?php echo JRoute::_('index.php?option=com_komento&view=comments&published=2');?>">
								<b><?php echo $totalPending;?></b>
								<div><?php echo JText::_('COM_KOMENTO_DASHBOARD_PENDING');?></div>
							</a>
						</div>

						<div class="dash-stat-item">
							<a href="<?php echo JRoute::_('index.php?option=com_komento&view=comments&layout=reports');?>">
								<b><?php echo $totalReports;?></b>
								<div><?php echo JText::_('COM_KOMENTO_DASHBOARD_REPORTS');?></div>
							</a>
						</div>

						<div class="dash-stat-item">
							<a href="<?php echo JRoute::_('index.php?option=com_komento&view=subscribers');?>">
								<b><?php echo $totalSubscribers; ?></b>
								<div><?php echo JText::_('COM_KOMENTO_DASHBOARD_SUBSCRIBERS');?></div>
							</a>
						</div>
					</div>
				</section>

				<section class="dash-social">
					<strong><?php echo JText::_('COM_KOMENTO_DASHBOARD_STAY_UPDATED');?></strong>
					<div>
						<i class="fa fa-facebook-square"></i>
						<span>
							<a href="https://facebook.com/StackIdeas" target="_blank" class="text-inherit"><?php echo JText::_('COM_KOMENTO_DASHBOARD_LIKE_FACEBOOK');?></a>
						</span>
						</div>
					<div>
						<i class="fa fa-twitter-square"></i>
						<span>
							<a href="https://twitter.com/StackIdeas" target="_blank" class="text-inherit"><?php echo JText::_('COM_KOMENTO_DASHBOARD_FOLLOW_INSTAGRAM');?></a>
						</span>
					</div>
					<div>
						<i class="fa fa-book"></i>
						<span>
							<a href="https://stackideas.com/docs/komento/" target="_blank" class="text-inherit"><?php echo JText::_('COM_KOMENTO_DASHBOARD_VIEW_DOCUMENTATION');?></a>
						</span>
				</div>
				</section>
			</div>
		</div>
	</div>
</div>


