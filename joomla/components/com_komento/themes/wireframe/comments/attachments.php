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
<div class="kt-editor-attachments <?php echo $files ? 'has-attachments' : '';?>" data-kt-attachment-wrapper>
	<?php if ($this->my->allow('download_attachment')) { ?>
			<?php if ($files) { ?>
				<div class="kt-editor-attachments__title">
					<?php echo JText::_('COM_KOMENTO_COMMENT_ATTACHMENTS'); ?> (<span class="fileCounter"><?php echo count($files); ?></span> / <?php echo $this->config->get('upload_max_file'); ?>)
				</div>
				<div class="kt-attachments-list">
					<?php foreach ($files as $file) { ?>
						<div class="kt-attachments-list__item" data-kt-attachments-item>
							<div class="kt-attachments-item">
								 <div class="kt-attachments-item__preview ">
									<div class="kt-attachments-preview is-<?php echo $file->isImage() ? 'image' : 'icon is-icon--' . $file->getIconType() ?>">

										<?php if ($file->isImage()) { ?>
											<a href="<?php echo $file->getLink();?>" class="kt-attachments-preview__content" style="background-image: url(<?php echo $file->getLink();?>);" data-lightbox="<?php echo $file->id;?>" data-title="<?php echo $this->escape($file->filename);?>">
											</a>
										<?php } else { ?>
											<div class="kt-attachments-preview__content"></div>
										<?php } ?>

									</div>
								</div>
								<div class="kt-attachments-item__content">
									<a href="<?php echo $file->getLink();?>" class="kt-attachments-item__name" target="_blank">
										<?php echo $file->filename; ?>
									</a>
									<div class="kt-attachments-item__size">
										<?php echo $file->getSize(); ?> kb
									</div>
								</div>

								<div class="kt-attachments-item__actions">
									<a href="<?php echo $file->getLink();?>" target="_blank"><i class="fa fa-download"></i></a>
									<?php if ($this->access->allow('delete_attachment', $comment)) { ?>
										<a href="javascript:void(0);" data-kt-attachment-item-delete data-id="<?php echo $file->id;?>"><i class="fa fa-trash-o"></i></a>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
	<?php } else { ?>
		<div class="">
			<?php echo JText::_('COM_KOMENTO_COMMENT_ATTACHMENTS_NO_PERMISSION_TO_VIEW'); ?>
		</div>
	<?php } ?>
</div>