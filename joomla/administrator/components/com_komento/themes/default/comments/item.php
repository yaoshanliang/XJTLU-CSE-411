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
<tr id="<?php echo 'kmt-' . $comment->id; ?>" class="kmt-row" childs="<?php echo $comment->hasReplies() ? 1 : 0; ?>" depth="<?php echo $comment->depth; ?>" parentid="<?php echo $comment->parent_id; ?>">

	<td class="center">
		<?php echo $this->html('grid.id', $i, $comment->id); ?>
	</td>

	<td class="comment-cell">
		<div class="row-table cell-top">
			<div class="col-cell">
				<a href="<?php echo JRoute::_('index.php?option=com_komento&view=comments&amp;layout=form&amp;id=' . $comment->id); ?>">
				<?php if (JString::strlen($comment->getContent()) > 80) { ?>
					<?php echo JString::substr(strip_tags($comment->getContent()), 0, 80); ?>...
					<?php } else { ?> 
					<?php echo JString::substr(strip_tags($comment->getContent()), 0); ?>
					<?php } ?>
				</a>
			</div>
		</div>
	</td>

	<td class="linked-cell center">
		<?php if (!$search) { ?>
			<?php if ($comment->hasReplies()) { ?>
				<a href="<?php echo JRoute::_('index.php?option=com_komento&amp;view=comments&amp;parentid=' . $comment->id); ?>"><?php echo JText::_('COM_KOMENTO_VIEW_CHILD'); ?></a>
			<?php } else { ?>
				&mdash;
			<?php } ?>
		<?php } else { ?>
			<?php if ($comment->parent_id) { ?>
				<a href="<?php echo JRoute::_('index.php?option=com_komento&amp;view=comments&amp;controller=comment&amp;nosearch=1&amp;parentid=' . $comment->parent_id); ?>"><?php echo JText::_('COM_KOMENTO_VIEW_PARENT'); ?></a>
			<?php } else { ?>
				&mdash;
			<?php } ?>
		<?php } ?>
	</td>
	
	<td class="published-cell center">
		<?php echo $this->html('grid.published', $comment, 'comments', 'published'); ?>
	</td>

	<?php if ($layout == 'reports') { ?>
		<td class="center">
			<?php echo $comment->reports; ?>
		</td>
	<?php } ?>

	<?php if (!in_array($layout, array('reports', 'pending'))) { ?>
		<td class="sticked-cell center">
			<?php echo $this->html('grid.featured', $comment, 'comments', 'sticked', $comment->isFeatured() ? 'unfeature' : 'feature'); ?>
		</td>
	<?php } ?>

	<td class="center">
		<?php if ($comment->extension && $comment->isPublished()) { ?>
			<a href="<?php echo $comment->getPermalink(); ?>" target=_blank><?php echo $comment->contenttitle; ?></a>
		<?php } elseif ($comment->extension && !$comment->isPublished()) { ?>
			<a href="<?php echo $comment->getItemPermalink(); ?>" target=_blank><?php echo $comment->contenttitle; ?></a>
		<?php } else { ?>
			<span class="error"><?php echo $comment->contenttitle; ?></span>
		<?php } ?>
		(<?php echo $comment->componenttitle; ?>)
	</td>

	<td class="center">
		<?php echo $comment->getCreatedDate()->toSql(); ?>
	</td>

	<!-- Author -->
	<td class="center">
		<?php echo $comment->name; ?> (<?php echo $comment->ip;?>)
	</td>

	<!-- ID -->
	<td class="center">
		<?php echo $comment->id;?>
	</td>
</tr>
