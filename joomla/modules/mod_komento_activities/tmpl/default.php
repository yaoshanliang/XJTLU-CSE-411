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
defined('_JEXEC') or die('Restricted access'); ?>

<div id="kt" class="mod-kt mod-kt-activities<?php echo $params->get('moduleclass_sfx'); ?> <?php echo JFactory::getDocument()->getDirection() == 'rtl' ? 'is-rtl' : '';?>
">
<?php if ($activities) { ?>
	<div class="mod-kt-list--vertical">
	<?php foreach ($activities as $activity) { ?>
		<div class="mod-kt-item <?php echo 'kmt-' . $activity->id; ?>">

			<div class="o-flag">
				<i class="stream-type"></i>
					<a href="<?php echo $activity->author->getProfileLink(); ?>"><?php echo $activity->author->getName(); ?></a> -
					
					<?php switch($activity->type) {
						case 'comment': ?>
							<?php echo JText::_('COM_KOMENTO_ACTIVITY_COMMENTED_ON'); ?>
							<a href="<?php echo $activity->comment->getPermalink(); ?>"><?php echo $activity->comment->itemTitle; ?></a>
						<?php break;

						case 'reply': ?>
							<?php echo JText::_('COM_KOMENTO_ACTIVITY_REPLIED_TO'); ?>
							<a href="<?php echo $activity->comment->getPermalink(); ?>" class="parentLink" parentid="<?php echo $activity->comment->parent_id; ?>"># <?php echo $activity->comment->parent_id; ?></a>
							<?php echo JText::_('COM_KOMENTO_ACTIVITY_REPLIED_ON'); ?>
							<a href="<?php echo $activity->comment->getPermalink(); ?>"><?php echo $activity->comment->itemTitle; ?></a>
						<?php break;

						case 'like': ?>
							<?php echo JText::_('COM_KOMENTO_ACTIVITY_LIKED_ON'); ?>
							<a href="<?php echo $activity->comment->getPermalink(); ?>"><?php echo $activity->comment->itemTitle; ?></a>
						<?php break; ?>
					<?php } ?>
			</div>
			
			<?php if ($params->get('showcomment')) { ?>
				<div class="o-flag__body">
					<div class="mod-kt-text"><?php echo $activity->comment->getContent($params->get('maxcommentlength')); ?></div>
				</div>
			<?php } ?>

			<div class="mod-kt-meta">
				<div class="mod-kt-time">
					<a class="mod-kt-permalink" href="<?php echo $activity->comment->getPermalink(); ?>"><?php echo $activity->comment->getCreatedDate()->toFormat(JText::_('DATE_FORMAT_LC2')); ?></a>
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
<?php } else { ?>
	<div class="empty"><?php echo JText::_('COM_KOMENTO_NO_ACTIVITIES_FOUND');?></div>
<?php } ?>
</div>
