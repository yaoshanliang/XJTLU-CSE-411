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
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="row">
	<div class="col-md-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KT_EDITING_PRESET'); ?>

			<div class="panel-body">

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="title"><?php echo JText::_('COM_KT_PRESET_TITLE');?></label>
					</div>
					<div class="col-md-9">
						<input type="text" maxlength="255" size="100" id="title" name="title" class="o-form-control" value="<?php echo $this->escape($preset->title);?>" />
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="email"><?php echo JText::_('COM_KT_PRESET_FIRST_COLOR');?></label>
					</div>
					<div class="col-md-9">
						<?php echo $this->html('form.colorpicker', 'color1', $params->get('color1', '#F3E7E8')); ?>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="email"><?php echo JText::_('COM_KT_PRESET_SECOND_COLOR');?></label>
					</div>
					<div class="col-md-9">
						<?php echo $this->html('form.colorpicker', 'color2', $params->get('color2', '#E3EEFF')); ?>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="email"><?php echo JText::_('COM_KT_PRESET_FONT_COLOR');?></label>
					</div>
					<div class="col-md-9">
						<?php echo $this->html('form.colorpicker', 'fontcolor', $params->get('fontcolor', '#444444')); ?>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="url"><?php echo JText::_('COM_KT_PRESET_GRADIENT_TYPE');?></label>
					</div>
					<div class="col-md-9">
						<?php echo $this->html('grid.selectlist', 'type', $preset->type, array(
							array('value' => 'horizontal', 'text' => 'COM_KT_PRESET_HORIZONTAL'),
							array('value' => 'vertical', 'text' => 'COM_KT_PRESET_VERTICAL')
						)); ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-3 control-label">
						<label for="url"><?php echo JText::_('COM_KOMENTO_PUBLISHED');?></label>
					</div>
					<div class="col-md-9">
						<?php echo $this->html('form.toggler', 'published', $preset->published); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo $this->html('form.action', 'backgrounds.save'); ?>
<input type="hidden" name="id" value="<?php echo $this->escape($preset->id);?>" />
<input type="hidden" name="return" value="<?php echo $return;?>" />
</form>
