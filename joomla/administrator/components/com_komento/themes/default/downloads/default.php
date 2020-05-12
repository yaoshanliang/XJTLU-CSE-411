<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
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
						<?php echo JText::_('COM_KT_TABLE_COLUMN_NAME'); ?>
					</th>
					<th width="15%" class="center">
						<?php echo JText::_('COM_KT_TABLE_COLUMN_DOWNLOAD'); ?>
					</th>
					<th width="15%" class="center">
						<?php echo JText::_('COM_KOMENTO_COLUMN_STATUS'); ?>
					</th>
					<th width="15%" class="center">
						<?php echo JText::_('COM_KOMENTO_TABLE_COLUMN_CREATED'); ?>
					</th>
					<th width="1%" class="center">
						<?php echo  Jtext::_('COM_KOMENTO_TABLE_COLUMN_ID'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($requests) { ?>

					<?php $i = 0; ?>
					<?php foreach ($requests as $request) { ?>
					<tr id="<?php echo 'kmt-' . $request->id; ?>" class="kmt-row">
						<td class="center">
							<?php echo $this->html('grid.id' , $i , $request->id); ?>
						</td>
						<td>
							<?php echo $request->getRequester()->getName();?>
						</td>
						<td class="center">
							<?php if ($request->isReady()) { ?>
								<a href="index.php?option=com_komento&view=downloads&layout=downloaddata&id=<?php echo $request->id;?>"><?php echo JText::_('COM_KT_DOWNLOAD');?></a>
							<?php } else { ?>
								&mdash;
							<?php } ?>
						</td>
						<td class="center">
							<?php echo $request->getStateLabel(); ?>
						</td>
						<td class="center">
						   <?php echo $request->created;?>
						</td>
						<td class="center">
							<?php echo $request->id; ?>
						</td>
					</tr>
					<?php $i++; ?>
					<?php } ?>

				<?php } else { ?>
				<tr class="is-empty">
					<td colspan="8" class="empty">
						<?php echo JText::_('COM_KT_USER_DOWNLOAD_NO_ITEMS'); ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
 
			<tfoot>
				<tr>
					<td colspan="8">
						<div class="footer-pagination">
						<?php echo $pagination->getListFooter(); ?>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>


<?php echo JHTML::_('form.token'); ?>
<input type="hidden" name="boxchecked" value="0" data-kt-table-boxchecked />
<input type="hidden" name="task" value="" data-kt-table-task />
<input type="hidden" name="option" value="com_komento" />
<input type="hidden" name="view" value="downloads" />
<input type="hidden" name="controller" value="downloads" />
</form>
