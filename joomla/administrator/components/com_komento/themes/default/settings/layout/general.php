<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
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
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_LAYOUT_GENERAL'); ?>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'show_sort_buttons', 'COM_KOMENTO_SETTINGS_SHOW_SORT_BUTTONS'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_DEFAULT_SORT'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'default_sort', $this->config->get('default_sort'), array(
							array('value' => 'oldest', 'text' => 'COM_KOMENTO_SETTINGS_SORT_OLDEST'),
							array('value' => 'latest', 'text' => 'COM_KOMENTO_SETTINGS_SORT_LATEST'),
							array('value' => 'popular', 'text' => 'COM_KT_SORT_MOST_LIKES')
						)); ?>
					</div>
				</div>
				<?php echo $this->html('settings.toggle', 'enable_threaded', 'COM_KOMENTO_SETTINGS_THREADED_VIEW_ENABLE', '', 'data-comment-indent'); ?>

				<?php echo $this->html('settings.textbox', 'thread_indentation', 'COM_KOMENTO_SETTINGS_THREAD_INDENTATION', '', 
										array('postfix' => 'COM_KOMENTO_PIXELS', 'visible' => $this->config->get('enable_threaded'), 'wrapperAttributes' => 'data-comment-indent-level'), 
										'', 'input-mini text-center'); ?>

				<?php echo $this->html('settings.textbox', 'max_comments_per_page', 'COM_KOMENTO_SETTINGS_COMMENTS_MAX_PER_PAGE', '', array('postfix' => 'COM_KOMENTO_COMMENTS'), '', 'input-mini text-center'); ?>
				<?php echo $this->html('settings.toggle', 'reply_autohide', 'COM_KOMENTO_SETTINGS_REPLY_AUTOHIDE'); ?>
				<?php echo $this->html('settings.textbox', 'reply_autohide_threshold', 'COM_KOMENTO_SETTINGS_REPLY_AUTOHIDE_THRESHOLD', '', array('postfix' => 'Replies'), '', 'input-mini text-center'); ?>
				<?php echo $this->html('settings.toggle', 'enable_conversation_bar', 'COM_KOMENTO_SETTINGS_CONVERSATION_BAR_ENABLE'); ?>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_COMMENT_APPEARENCE'); ?>
			
			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'enable_info', 'COM_KOMENTO_SETTINGS_DISPLAY_LAST_EDITED_INFO'); ?>
				<?php echo $this->html('settings.toggle', 'auto_hyperlink', 'COM_KOMENTO_SETTINGS_LAYOUT_AUTO_HYPERLINK'); ?>
				<?php echo $this->html('settings.toggle', 'links_nofollow', 'COM_KOMENTO_SETTINGS_LAYOUT_LINKS_NOFOLLOW'); ?>
				<?php echo $this->html('settings.toggle', 'comment_enable_truncation', 'COM_KOMENTO_SETTINGS_COMMENTS_ENABLE_AUTOMATIC_TRUNCATE'); ?>
				<?php echo $this->html('settings.textbox', 'comment_truncation_length', 'COM_KOMENTO_SETTINGS_COMMENTS_ENABLE_AUTOMATIC_TRUNCATE_LENGTH', '', array('postfix' => 'COM_KOMENTO_CHARACTERS'), '', 'input-mini text-center'); ?>

				<?php echo $this->html('settings.toggle', 'enable_lapsed_time', 'COM_KOMENTO_SETTINGS_COMMENTS_USE_LAPSED_TIME', '', 'data-comment-date-lapsed'); ?>
				<?php echo $this->html('settings.textbox', 'date_format', 'COM_KOMENTO_SETTINGS_LAYOUT_DATE_FORMAT', '', array('visible' => $this->config->get('enable_lapsed_time'), 'wrapperAttributes' => 'data-comment-date-format'), 
										'<p><a href="http://php.net/manual/en/function.date.php" target="_blank">' . JText::_('COM_KOMENTO_SETTINGS_DATE_FORMAT_REFERENCE') . '</a></p>'); ?>

				<?php echo $this->html('settings.toggle', 'enable_media_max_width', 'COM_KT_SETTINGS_LAYOUT_MEDIA_MAXWIDTH', '', 'data-comment-media-maxwidth'); ?>
				<?php echo $this->html('settings.textbox', 'max_image_width', 'COM_KOMENTO_SETTINGS_LAYOUT_IMAGE_WIDTH', '', array('postfix' => 'COM_KOMENTO_PIXELS', 'wrapperAttributes' => 'data-comment-media-size', 'visible' => !$this->config->get('enable_media_max_width')), '', 'input-mini text-center'); ?>
				<?php echo $this->html('settings.textbox', 'max_image_height', 'COM_KOMENTO_SETTINGS_LAYOUT_IMAGE_HEIGHT', '', array('postfix' => 'COM_KOMENTO_PIXELS', 'wrapperAttributes' => 'data-comment-media-size', 'visible' => !$this->config->get('enable_media_max_width')), '', 'input-mini text-center'); ?>
				<?php echo $this->html('settings.textbox', 'bbcode_video_width', 'COM_KOMENTO_SETTINGS_LAYOUT_VIDEO_WIDTH', '', array('postfix' => 'COM_KOMENTO_PIXELS', 'wrapperAttributes' => 'data-comment-media-size', 'visible' => !$this->config->get('enable_media_max_width')), '', 'input-mini text-center'); ?>
			</div>
		</div>
	</div>
</div>

