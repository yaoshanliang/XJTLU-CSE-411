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
<form action="index.php" method="post" name="adminForm" id="adminForm" data-kt-table>
	<div class="panel-table">
		<table class="app-table table">
			<thead>
				<tr>
					<th class="center" width="1%"><input type="checkbox" name="toggle" data-kt-table-checkall/></th>

					<th style="text-align:left;">
						<?php echo JText::_('COM_KOMENTO_THEMES_TITLE'); ?>
					</th>
					<th width="15%" class="center">
						<?php echo JText::_('COM_KT_PRESET_COLOR'); ?>
					</th>
					<th width="5%" class="center">
						<?php echo JText::_('COM_KOMENTO_COLUMN_STATUS');?>
					</th>
				</tr>
			</thead>
			<tbody>
				
				<?php $i = 0; ?>
				<?php foreach ($presets as $preset) { ?>
					<tr class="kmt-row">
						<td class="center">
							<?php echo $this->html('grid.id', $i, $preset->id); ?>
						</td>
						
						<td>
							<a href="<?php echo JRoute::_('index.php?option=com_komento&view=backgrounds&layout=form&amp;id=' . $preset->id); ?>">	<?php echo JText::_($preset->title);?>
							</a>
						</td>
						<td class="center">
							<span style="width: 100px;display: 
								inline-block;border: 1px dashed #ccc;
								padding:4px 10px; 
								color: <?php echo $preset->getParams()->get('fontcolor'); ?>; 
								background: linear-gradient(to <?php echo $preset->getGradientDirection(); ?>, <?php echo $preset->getParams()->get('color1');?> 0%, <?php echo $preset->getParams()->get('color2');?> 100%);">
								Text
							</span>
						</td>
						<td class="center">
							<?php echo $this->html('grid.published', $preset, 'backgrounds', 'published'); ?>
						</td>
					</tr>
				<?php $i++; ?>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="task" value="" data-kt-table-task />
	<input type="hidden" name="option" value="com_komento" />
	<input type="hidden" name="controller" value="backgrounds" />
	<?php echo JHTML::_('form.token'); ?>
	
</form>