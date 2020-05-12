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
<form action="index.php?option=com_komento&view=comments" name="adminForm" id="adminForm" method="post" data-kt-table>
	<div class="app-filter-bar">
		<div class="app-filter-bar__cell">
			<?php echo $this->html('table.search' , $search); ?>
		</div>

		<?php if ($layout != 'pending') { ?> 
		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left">
			<div class="app-filter-bar__filter-wrap">
				<?php echo $state; ?> 
			</div>
		</div>
		<?php } ?>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left">
			<div class="app-filter-bar__filter-wrap">
				<?php echo $component; ?>
			</div>
		</div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left"></div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left app-filter-bar__cell--last t-text--center">
			<div class="app-filter-bar__filter-wrap">
				<?php echo $this->html('filter.limit' , $limit); ?>
			</div>
		</div>
	</div>

	<div class="panel-table">
		<table class="app-table table" data-comments-list>
			<thead>
				<tr>
					<th width="1%"><input type="checkbox" name="toggle" data-kt-table-checkall/></th>

					<th><?php echo JText::_('COM_KOMENTO_COLUMN_COMMENT'); ?></th>

					<th widht="10%" class="center">
					<?php if (!$search) { ?>
						<?php echo JText::_('COM_KOMENTO_COLUMN_COMMENT_CHILD'); ?>
					<?php } else { ?>
						<?php echo JText::_('COM_KOMENTO_COLUMN_COMMENT_PARENT'); ?>
					<?php } ?>
					</th>

					<th width="5%" class="center">
						<?php echo JText::_('COM_KOMENTO_COLUMN_STATUS');?>
					</th>

					<?php if ($layout == 'reports') { ?>
						<th width="5%">
							<?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_COLUMN_REPORT_COUNT'), 'reports', $orderDirection, $order); ?>
						</th>
					<?php } ?>

					<?php if (!in_array($layout, array('reports', 'pending'))) { ?>
						<th width="5%" class="center">
							<?php echo JText::_('COM_KOMENTO_COLUMN_FEATURED'); ?>
						</th>
					<?php } ?>

					<th width="25%" class="center"><?php echo JText::_('COM_KOMENTO_COLUMN_EXTENSION'); ?></th>

					<th width="10%" class="center">
						<?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_COLUMN_DATE'), 'created', $orderDirection, $order); ?>
					</th>

					<th width="10%" class="center">
						<?php echo JText::_('COM_KOMENTO_COLUMN_AUTHOR'); ?>
					</th>

					<th width="1%" class="center"><?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_COLUMN_ID'), 'id', $orderDirection, $order); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($comments) { ?>

					<?php $i = 0; ?>
					<?php foreach( $comments as $comment ){ ?>
						<?php echo $this->output('admin/comments/item', array('comment' => $comment, 'layout' => $layout, 'i' => $i, 'search' => $search)); ?>

						<?php $i++; ?>
					<?php } ?>

				<?php } else { ?>
				<tr class="is-empty">
					<td colspan="13" class="empty">

						<?php if ($layout == 'reports') { ?>
							<?php echo JText::_('COM_KOMENTO_COMMENTS_NO_REPORTED_COMMENTS'); ?>
						<?php } else if ($layout == 'pending') { ?>
							<?php echo JText::_('COM_KOMENTO_COMMENTS_NO_PENDING_COMMENTS'); ?>
						<?php } else { ?>
							<?php echo JText::_('COM_KOMENTO_COMMENTS_NO_COMMENT'); ?>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<td colspan="13">
						<div class="footer-pagination">
							<?php echo $pagination->getListFooter(); ?>
						</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="filter_order" value="<?php echo $order;?>" data-kt-table-ordering />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDirection;?>" data-kt-table-direction />
	<input type="hidden" name="boxchecked" value="0" data-kt-table-boxchecked />
	<input type="hidden" name="task" value="" data-kt-table-task />
	<input type="hidden" name="option" value="com_komento" />
	<input type="hidden" name="view" value="comments" />
	<input type="hidden" name="layout" value="<?php echo $layout; ?>" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<input type="hidden" name="controller" value="comments" />
	<input type="hidden" name="parentid" value="<?php echo $this->escape($parentid); ?>" />
</form>

