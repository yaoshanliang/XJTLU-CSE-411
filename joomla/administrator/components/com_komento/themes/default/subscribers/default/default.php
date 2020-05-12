<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2017 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<form action="index.php?option=com_komento&view=subscribers" method="post" name="adminForm" id="adminForm" data-kt-table>
	<div class="app-filter-bar">
		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left">
			<div class="app-filter-bar__filter-wrap">
				<?php echo $component; ?>
			</div>
		</div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left"></div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left app-filter-bar__cell--last t-text--center">
			<div class="app-filter-bar__filter-wrap">
		
			</div>
		</div>
	</div>

	<div class="panel-table">
		<table class="app-table table" cellspacing="1">
			<thead>
				<tr>
					<th width="1%"><input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);" /></th>
					<th>
						<?php echo JText::_('COM_KOMENTO_COLUMN_USER'); ?>
					</th>
					<th width="35%" class="center">
						<?php echo JText::_('COM_KOMENTO_COLUMN_ITEM_EXTENSION'); ?>
					</th>
					<th width="20%" class="center">
						<?php echo JText::_('COM_KOMENTO_COLUMN_SUBSCRIPTION_TYPE'); ?>
					</th>
					<th width="15%" class="center">
						<?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_COLUMN_DATE'), 'created', $orderDirection, $order); ?>
					</th>
					<th width="5%" class="center">
						<?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_COLUMN_ID') , 'id', $orderDirection, $order); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($subscribers) { ?>
					<?php $i = 0; ?>
					<?php foreach ($subscribers as $subscriber) { ?>
					<tr>
						<td class="center">
							<?php echo $this->html('grid.id', $i, $subscriber->id); ?>
						</td>
						<td>
							<a href="index.php?option=com_komento&view=subscribers&layout=form&id=<?php echo $subscriber->id;?>"><?php echo $subscriber->fullname; ?> (<?php echo $subscriber->email;?>)</a>
						</td>
						
						<td class="center">
							<?php echo $subscriber->contenttitle; ?> (<?php echo $subscriber->componenttitle; ?>)
						</td>

						<td class="center">
							<?php echo JText::_('COM_KOMENTO_SUBSCRIPTION_' . strtoupper($subscriber->type));?>
						</td>

						<td class="center">
							<?php echo $subscriber->created;?>
						</td>

						<td class="center">
							<?php echo $subscriber->id; ?>
						</td>
					</tr>
					<?php $i++;?>
					<?php } ?>
				<?php } else { ?>
				<tr>
					<td colspan="6" class="center">
						<?php echo JText::_('COM_KOMENTO_SUBSCRIBERS_NO_SUBSCRIBERS'); ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6">
						<?php echo $pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="filter_order" value="<?php echo $this->escape($order);?>" data-kt-table-ordering />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDirection;?>" data-kt-table-direction />
	<input type="hidden" name="boxchecked" value="0" data-kt-table-boxchecked />
	<input type="hidden" name="task" value="" data-kt-table-task />
	<input type="hidden" name="option" value="com_komento" />
	<input type="hidden" name="controller" value="subscribers" />
	<?php echo $this->html('form.token'); ?>
</form>
