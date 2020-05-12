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

<div class="kt-comment-control btn-group">
	<a href="javascript:void(0);" class="btn-control" data-kt-toggle="dropdown">
		<i class="fa fa-ellipsis-h"></i>
	</a>
	<ul class="dropdown-menu dropdown-menu-right">
		<?php if ($comment->canEdit()) { ?>
			<li>
				<a href="javascript:void(0);" data-kt-manage-edit><?php echo JText::_('COM_KOMENTO_COMMENT_EDIT'); ?></a>
			</li>
		<?php } ?>
		
		<?php if ($comment->canMinimize()) { ?>
			<li class="kt-admin-minimize">
				<a href="javascript:void(0);" data-kt-manage-minimize><?php echo JText::_('COM_KT_MINIMIZE_COMMENT'); ?></a>
			</li>
			<li class="kt-admin-expand">
				<a href="javascript:void(0);" data-kt-manage-expand><?php echo JText::_('COM_KT_EXPAND_COMMENT'); ?></a>
			</li>
		<?php } ?>

		<?php if ($comment->canFeature()) { ?>
			<li class="divider"></li>
			<li class="kt-unpin-comment">
				<a href="javascript:void(0);" class="" data-kt-manage-unpin><?php echo JText::_('COM_KOMENTO_UNFEATURE_COMMENT'); ?></a>
			</li>
			<li class="kt-pin-comment">
				<a href="javascript:void(0);" class="" data-kt-manage-pin><?php echo JText::_('COM_KOMENTO_FEATURE_COMMENT'); ?></a>
			</li>
		<?php } ?>
		
		<?php if ($comment->canUnpublish() && $comment->isPublished()) { ?>
		<li class="divider"></li>
		<li>
			<a href="javascript:void(0);" data-kt-manage-unpublish><?php echo JText::_('COM_KOMENTO_COMMENT_UNPUBLISH'); ?></a>
		</li>
		<?php } ?>


		<?php if ($comment->canPublish() && $comment->isUnpublished()) { ?>
		<li class="divider"></li>
		<li>
			<a href="javascript:void(0);"><?php echo JText::_('COM_KOMENTO_COMMENT_PUBLISH'); ?></a>
		</li>
		<?php } ?>

		<?php if ($comment->canSubmitSpam()) { ?>
		<li class="divider"></li>
		<li>
			<a href="javascript:void(0);" data-kt-submit-spam><?php echo JText::_('COM_KOMENTO_MARK_SPAM'); ?></a>
		</li>
		<?php } ?>

		<?php if ($comment->canDelete()) { ?>
		<li class="divider"></li>
		<li>
			<a href="javascript:void(0);" class="t-text--danger" data-kt-manage-delete><?php echo JText::_('COM_KOMENTO_COMMENT_DELETE'); ?></a>
		</li>
		<?php } ?>
	</ul>
</div>

