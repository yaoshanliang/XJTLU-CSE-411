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
defined('_JEXEC') or die('Unauthorized Access');
?>
<form name="adminForm" id="adminForm" class="esForm" action="index.php" method="post" data-kt-table>

	<div class="panel-table">
        <table class="app-table table">
			<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="toggle" class="checkAll" data-kt-table-checkall />
					</th>
					<th>
						<?php echo JText::_('COM_KOMENTO_TABLE_COLUMN_FILENAME'); ?>
					</th>
					<th width="35%">
						<?php echo JText::_('COM_KOMENTO_TABLE_COLUMN_FILE_DESCRIPTION'); ?>
					</th>
					<th width="40%">
						<?php echo JText::_('COM_KOMENTO_TABLE_COLUMN_LOCATION'); ?>
					</th>
					<th width="5%" class="center">
						<?php echo JText::_('COM_KOMENTO_TABLE_COLUMN_MODIFIED'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($files) { ?>
					<?php $i = 0; ?>
					<?php foreach ($files as $file) { ?>
					<tr>
						<td class="center">
							<?php echo $this->html('grid.id', $i, base64_encode($file->relative)); ?>
						</td>
						<td>
							<a href="index.php?option=com_komento&view=mailq&layout=editfile&file=<?php echo urlencode($file->relative);?>"><?php echo $file->name; ?></a>
						</td>
						<td>
							<?php echo $file->desc;?>
						</td>
						<td>
							<?php echo str_ireplace(JPATH_ROOT, '', $file->path);?>
						</td>
						<td class="center">
							<?php echo $this->html('grid.published', $file, 'files', 'override'); ?>
						</td>
					</tr>
					<?php $i++; ?>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="boxchecked" value="0" data-kt-table-boxchecked />
	<input type="hidden" name="task" value="" data-kt-table-task />
	<input type="hidden" name="option" value="com_komento" />
	<input type="hidden" name="view" value="mailq" />
	<input type="hidden" name="controller" value="mailq" />
</form>