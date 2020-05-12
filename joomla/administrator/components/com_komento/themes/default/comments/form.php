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
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="row">
	<div class="col-md-8">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_EDITING_COMMENT'); ?>

			<div class="panel-body">

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="name"><?php echo JText::_('COM_KOMENTO_COMMENT_NAME');?></label>
					</div>
					<div class="col-md-9">
						<input type="text" maxlength="255" size="100" id="name" name="name" class="o-form-control" value="<?php echo $this->escape($comment->name);?>" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="email"><?php echo JText::_('COM_KOMENTO_COMMENT_EMAIL');?></label>
					</div>
					<div class="col-md-9">
						<input type="text" maxlength="255" size="100" id="email" name="email" class="o-form-control" value="<?php echo $this->escape($comment->email);?>" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="url"><?php echo JText::_('COM_KOMENTO_COMMENT_WEBSITE');?></label>
					</div>
					<div class="col-md-9">
						<input type="text" maxlength="255" size="100" id="url" name="url" class="o-form-control" value="<?php echo $this->escape($comment->url);?>" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="cid"><?php echo JText::_('COM_KOMENTO_COMMENT_ARTICLEID');?></label>
					</div>
					<div class="col-md-9">
						<input type="text" maxlength="255" size="100" id="cid" name="cid" class="o-form-control" value="<?php echo $this->escape($comment->cid);?>" />
					</div>
				</div>


				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="comment"><?php echo JText::_('COM_KOMENTO_COMMENT_TEXT');?></label>
					</div>

					<div class="col-md-9">
						<?php echo $this->html('grid.textarea', 'comment', $comment->comment); ?>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="ip"><?php echo JText::_('COM_KOMENTO_COMMENT_IP');?></label>
					</div>

					<div class="col-md-9">
						<input type="text" maxlength="255" size="100" id="ip" name="ip" class="o-form-control" value="<?php echo $this->escape($comment->ip);?>" />
					</div>
				</div>

				<div class="form-group">

					<div class="col-md-3 control-label">
						<label for="latitude"><?php echo JText::_('COM_KOMENTO_COMMENT_LATITUDE');?></label>
					</div>

					<div class="col-md-9">
						<input type="text" maxlength="255" size="100" id="latitude" name="latitude" class="o-form-control" value="<?php echo $this->escape($comment->latitude);?>" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="longitude"><?php echo JText::_('COM_KOMENTO_COMMENT_LONGITUDE');?></label>
					</div>

					<div class="col-md-9">
						<input type="text" maxlength="255" size="100" id="longitude" name="longitude" class="o-form-control" value="<?php echo $this->escape($comment->longitude);?>" />
					</div>
				</div>

				<div class="form-group">

					<div class="col-md-3 control-label">
						<label for="address"><?php echo JText::_('COM_KOMENTO_COMMENT_ADDRESS');?></label>
					</div>

					<div class="col-md-9">
						<input type="text" maxlength="255" size="100" id="address" name="address" class="o-form-control" value="<?php echo $this->escape($comment->address);?>" />
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-4">

		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_COMMENT_PUBLISHING_OPTIONS'); ?>
			<div class="panel-body">
				<div class="form-group">

					<div class="col-md-3 control-label">
						<label for="created_by"><?php echo JText::_('COM_KOMENTO_COMMENT_AUTHOR');?></label>
					</div>

					<div class="col-md-9">
						<input disabled="disabled" type="text" maxlength="255" size="100" id="created_by" name="created_by" class="o-form-control" value="<?php echo $comment->getAuthorName();?>" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="published"><?php echo JText::_('COM_KOMENTO_COMMENT_CREATED_DATE');?></label>
					</div>

					<div class="col-md-9">
						<div class="o-input-group date" data-date-picker>
							<input type="text" class="o-form-control" name="created" id="created" value="<?php echo $comment->getCreatedDate()->toFormat('m/d/Y h:i A'); ?>" />
							<span class="o-input-group__btn">
								<button class="btn btn-kt-default-o" type="button"><i class="fa fa-calendar"></i></button>
							</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="published"><?php echo JText::_('COM_KOMENTO_COMMENT_PUBLISHED');?></label>
					</div>

					<div class="col-md-9">
						<?php echo $this->html('form.toggler', 'published', $comment->published); ?>
					</div>
				</div>

				<div class="form-group">

					<div class="col-md-3 control-label">
						<label for="sticked"><?php echo JText::_('COM_KOMENTO_COMMENT_FEATURED');?></label>
					</div>

					<div class="col-md-9">
						<?php echo $this->html('form.toggler', 'sticked', $comment->sticked); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_komento" />
<input type="hidden" name="controller" value="comments" />
<input type="hidden" name="task" value="" data-kt-table-task />
<input type="hidden" name="id" value="<?php echo $this->escape($comment->id);?>" />
<input type="hidden" name="return" value="<?php echo $return; ?>" />
</form>
