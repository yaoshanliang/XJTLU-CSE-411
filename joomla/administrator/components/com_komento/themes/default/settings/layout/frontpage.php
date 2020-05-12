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
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE'); ?>
			<div class="panel-body">
				
				<?php echo $this->html('settings.toggle', 'layout_frontpage_comment', 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_SHOW_COMMENTS'); ?>
				<?php echo $this->html('settings.toggle', 'layout_frontpage_hits', 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_SHOW_HITS'); ?>
				<?php echo $this->html('settings.toggle', 'layout_frontpage_ratings', 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_SHOW_RATINGS'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_ALIGNMENT'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'layout_frontpage_alignment', $this->config->get('layout_frontpage_alignment'), array(
							array('value' => 'left', 'text' => 'COM_KOMENTO_ALIGNMENT_LEFT'),
							array('value' => 'right', 'text' => 'COM_KOMENTO_ALIGNMENT_RIGHT')
						)); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_PREVIEW'); ?>
			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'layout_frontpage_preview', 'COM_KOMENTO_SETTINGS_LAYOUT_FRONTPAGE_SHOW_PREVIEW'); ?>
				<?php echo $this->html('settings.textbox', 'preview_count', 'COM_KOMENTO_SETTINGS_PREVIEW_COUNT', '', array('postfix' => 'COM_KOMENTO_COMMENTS'), '', 'input-mini text-center'); ?>

				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_PREVIEW_SORT'); ?>

					<div class="col-md-7">
						<?php echo $this->html('grid.selectlist', 'preview_sort', $this->config->get('preview_sort'), array(
							array('value' => 'oldest', 'text' => 'COM_KOMENTO_SETTINGS_SORT_OLDEST'),
							array('value' => 'latest', 'text' => 'COM_KOMENTO_SETTINGS_SORT_LATEST'),
							array('value' => 'popular', 'text' => 'COM_KOMENTO_SETTINGS_SORT_POPULAR')
						)); ?>
					</div>
				</div>

				<?php echo $this->html('settings.toggle', 'preview_sticked_only', 'COM_KOMENTO_SETTINGS_PREVIEW_PINNED_ONLY'); ?>
				<?php echo $this->html('settings.toggle', 'preview_parent_only', 'COM_KOMENTO_SETTINGS_PREVIEW_PARENT_ONLY'); ?>
				<?php echo $this->html('settings.textbox', 'preview_comment_length', 'COM_KOMENTO_SETTINGS_PREVIEW_COMMENT_LENGTH', '', array('postfix' => 'COM_KOMENTO_CHARACTERS'), '', 'input-mini text-center'); ?>
			</div>
		</div>
	</div>
</div>

