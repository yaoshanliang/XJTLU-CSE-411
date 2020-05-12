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
<div class="kt-edit-wrapper" data-kt-edit-form>
	<div class="kt-form-editor <?php echo $this->config->get('bbcode_hide_buttons') ? 'kt-buttons-hidden' : '';?>" data-editor-wrapper>
		<textarea class="o-form-control kt-form-editor__textarea kt-form-bg--<?php echo $comment->getParams()->get('preset'); ?>" cols="50" rows="8" placeholder="<?php echo JText::_('COM_KOMENTO_FORM_WRITE_YOUR_COMMENTS'); ?>" data-kt-editor><?php echo $comment->comment;?></textarea>

		<?php if ($this->config->get('enable_smileys')) { ?>
		<div class="kt-form-editor__smiley">
			<a href="javascript:void(0);" data-comment-smileys class="kt-form-editor__smiley-toggle"><i class=" fa fa-smile-o" ></i></a>
			<div class="kt-form-editor-smileys-container">
				<?php echo KT::smileys()->html();?>
			</div>
		</div>
		<?php } ?>
	</div>

	<div class="kt-edit-actions t-lg-mt--md t-text--right">
		<a href="javascript:void(0);" class="btn btn-kt-default-o btn-sm t-lg-mr--sm" data-kt-edit-cancel><?php echo JText::_('COM_KOMENTO_FORM_CANCEL');?></a>
		<a href="javascript:void(0);" class="btn btn-kt-primary btn-sm" data-kt-edit-save><?php echo JText::_('COM_KOMENTO_FORM_SAVE');?></a>
	</div>
</div>

<?php if ($this->config->get('enable_backgrounds')) { ?>
<div data-background-selection class="t-hidden">
	<div class="markItUpHeader__bg-select" data-backgrounds-list-wrapper>
		<a href="javascript:void(0)" data-toggle-selection class="markItUpHeader__bg-select-link">
			<span class="markItUpHeader-bg-select-preview kt-form-bg--<?php echo $comment->getParams()->get('preset'); ?>" data-editor-preview></span>
			<span class="markItUpHeader__bg-select-txt"><?php echo JText::_('COM_KT_BACKGROUND');?></span>
		</a>

		<div class="markItUpHeader__bg-dropdown t-hidden" data-backgrounds-list>
			<div class="markItUpHeader__bg-menu">
				<?php foreach ($presets as $preset) { ?>
					<div class="markItUpHeader__bg-menu-item">
						<a href="javascript:void(0);" class="markItUpHeader-bg-select-preview kt-form-bg--<?php echo $preset->id; ?>" data-select-background data-preset="<?php echo $preset->id; ?>"></a>
					</div>
				<?php } ?>

				<div class="markItUpHeader__bg-menu-item">
					<a href="javascript:void(0);" class="markItUpHeader-bg-select-preview kt-form-bg--remove"  data-select-background data-preset="0"></a>  
				</div>

			</div>
		</div>
	</div>
</div>
<?php } ?>