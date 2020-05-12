<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<dialog>
	<width>500</width>
	<height>350</height>
	<selectors type="json">
	{
		"{closeButton}": "[data-close-button]",
		"{url}": "[data-kt-bbcode-video]",
		"{submit}": "[data-insert-button]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{url} keypress": function(input, event) {
			if (event.keyCode == 13) {
				event.preventDefault();
				this.submit().click();
			}
		},
		
		"{submit} click": function() {
			var url = this.url().val();

			if (url == '') {
				this.url().parents('.o-form-group').addClass('has-error');
				return;
			}

			// Find the textarea to insert the item now
			var tag = '[video]' + url + '[/video]';

			var formController = $('[data-kt-form]').controller();
			formController.insertText(tag, <?php echo $position;?>);

			// Hide the window
			this.closeButton().click();
		},

		"{closeButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_KOMENTO_INSERT_VIDEO'); ?></title>
	<content>
		<p><?php echo JText::_('COM_KOMENTO_INSERT_VIDEO_DESC');?></p>
		<ul class="kt-video-providers">
			<li class="video-youtube"><?php echo JText::_('COM_KOMENTO_VIDEO_YOUTUBE');?></li>
			<li class="video-vimeo"><?php echo JText::_('COM_KOMENTO_VIDEO_VIMEO');?></li>
			<li class="video-dailymotion"><?php echo JText::_('COM_KOMENTO_VIDEO_DAILYMOTION');?></li>
			<li class="video-google"><?php echo JText::_('COM_KOMENTO_VIDEO_GOOGLE');?></li>
			<li class="video-liveleak"><?php echo JText::_('COM_KOMENTO_VIDEO_LIVELEAK');?></li>
			<li class="video-metacafe"><?php echo JText::_('COM_KOMENTO_VIDEO_METACAFE');?></li>
			<li class="video-nicovideo"><?php echo JText::_('COM_KOMENTO_VIDEO_NICOVIDEO');?></li>
			<li class="video-yahoo"><?php echo JText::_('COM_KOMENTO_VIDEO_YAHOO');?></li>
		</ul>

		<div class="o-form-horizontal">
			<div class="o-form-group">
				<label for="video-url" class="o-control-label">
					<?php echo JText::_('COM_KOMENTO_INSERT_VIDEO_URL');?>
				</label>
				<div class="o-control-input">
					<input type="text" name="video-url" id="video-url" class="o-form-control" data-kt-bbcode-video placeholder="<?php echo JText::_('COM_KOMENTO_INSERT_VIDEO_URL_HERE');?>" />
				</div>
			</div>
		</div>
	</content>
	<buttons>
		<button type="button" class="btn btn-kt-default btn-sm" data-close-button><?php echo JText::_('COM_KOMENTO_CLOSE_BUTTON'); ?></button>
		<button type="button" class="btn btn-kt-primary btn-sm" data-insert-button><?php echo JText::_('COM_KOMENTO_INSERT_VIDEO'); ?></button>
	</buttons>
</dialog>