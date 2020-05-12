<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access'); ?>

<div id="kt" class="mod-kt mod-kt-comments<?php echo $params->get('moduleclass_sfx'); ?> <?php echo JFactory::getDocument()->getDirection() == 'rtl' ? 'is-rtl' : '';?>
">
<?php if ($tmpComments) { ?>
	<div class="mod-kt-list--vertical">
	<?php foreach ($tmpComments as $comment) { ?>
		<div class="mod-kt-item <?php echo 'kmt-' . $comment->id; ?> <?php echo $comment->getCustomCss();?>">

			<div class="o-flag">

				<?php if ($params->get('showavatar')) { ?>
					<div class="o-flag__img t-lg-mr--md">
						<?php echo $comment->getAuthor()->getAvatarHtml($comment->getAuthorName(), $comment->getAuthorEmail(), $comment->url);?>
					</div>
				<?php } ?>

				<div class="o-flag__body">
					<?php if ($params->get('showauthor')) { ?>
						<div class="mod-kt-author">
							<?php echo JText::sprintf('COM_KOMENTO_POSTED_COMMENT_IN', $comment->getAuthor()->getName($comment->name), $comment->getPermalink(), $comment->getItemTitle());?>
						</div>
					<?php } ?>

					<div class="mod-kt-comment-content">
						<?php echo $comment->getContent($maxCommentLength); ?>
					</div>

					<div class="mod-kt-meta">
						<?php if ($params->get('showcomponent')) { ?>
						<span class="mod-kt-page">
							<i class="fa fa-cube"></i>
							<?php echo JText::sprintf('COM_KOMENTO_TITLE_IN_COMPONENT', $comment->getComponentTitle()); ?>
						</span>
						<?php } ?>
						<span class="mod-kt-time">
							<a class="mod-kt-permalink" href="<?php echo $comment->getPermalink(); ?>" alt="<?php echo JText::_('COM_KOMENTO_COMMENT_PERMANENT_LINK'); ?>" title="<?php echo JText::_('COM_KOMENTO_COMMENT_PERMANENT_LINK'); ?>">
							<i class="fa fa-clock-o"></i>
							<?php echo $comment->getCreatedDate()->toFormat(JText::_('DATE_FORMAT_LC2')); ?></a>
						</span>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
<?php } else { ?>
	<div class="empty"><?php echo JText::_('COM_KOMENTO_COMMENTS_NO_COMMENT');?></div>
<?php } ?>
</div>
