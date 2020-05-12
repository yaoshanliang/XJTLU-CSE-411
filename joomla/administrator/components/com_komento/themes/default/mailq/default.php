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
<form name="adminForm" id="adminForm" class="esForm" action="index.php" method="post" data-kt-table>
	<div class="app-filter-bar">
		<div class="app-filter-bar__cell">
			<?php echo $this->html('table.search' , $search); ?>
		</div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left">
			<div class="app-filter-bar__filter-wrap">
				<select class="o-form-control" name="filter_publish" id="filter_publish" onchange="submitform();" data-kt-table-filter data-table-grid-filter>
					<option value="all"<?php echo $published == 'all' ? ' selected="selected"' : '';?>><?php echo JText::_('COM_KOMENTO_FILTER_SELECT_STATUS'); ?></option>
					<option value="1"<?php echo $published === 1 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_KOMENTO_MAILER_SENT'); ?></option>
					<option value="0"<?php echo $published === 0 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_KOMENTO_MAILER_PENDING'); ?></option>
					<option value="2"<?php echo $published === 2 ? ' selected="selected"' : '';?>><?php echo JText::_('COM_KOMENTO_MAILER_SENDING'); ?></option>
				</select>
			</div>
		</div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left"></div>

		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left app-filter-bar__cell--last t-text--center">
			<div class="app-filter-bar__filter-wrap">
				<?php echo $this->html('filter.limit' , $limit); ?>
			</div>
		</div>
	</div>

	<div class="app-filter-bar">
		<div class="t-lg-p--md">
			<?php echo JText::_('COM_KOMENTO_MAILER_DESCRIPTION'); ?> 
			<a href="https://stackideas.com/docs/komento/administrators/cronjobs" target="_blank" class="btn btn-kt-primary btn-sm t-lg-ml--xl"><?php echo JText::_('COM_KOMENTO_LEARN_MORE'); ?></a>
		</div>
	</div>

	<div class="panel-table">
		<table class="app-table table">
			<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="toggle" class="checkAll" data-kt-table-checkall />
					</th>
					<th>
						<?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_MAILER_EMAIL_TITLE'), 'subject', $direction, $ordering); ?>
					</th>
					<th width="20%" class="center">
						<?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_TABLE_COLUMN_RECIPIENT'), 'recipient', $direction, $ordering); ?>
					</th>
					<th width="5%" class="center">
						<?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_TABLE_COLUMN_STATE'), 'status', $direction, $ordering); ?>
					</th>
					<th width="10%" class="center">
						<?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_TABLE_COLUMN_CREATED'), 'created', $direction, $ordering); ?>
					</th>
					<th width="5%" class="center">
						<?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_TABLE_COLUMN_ID'), 'id', $direction, $ordering); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($emails) { ?>

					<?php $i = 0; ?>
					<?php foreach ($emails as $email) { ?>
					<tr id="<?php echo 'kmt-' . $email->id; ?>" class="kmt-row">
						<td class="center">
							<?php echo $this->html('grid.id' , $i , $email->id); ?>
						</td>
						<td>
							<a href="javascript:void(0);" data-mailer-item-preview data-id="<?php echo $email->id;?>"><?php echo $email->subject; ?></a>
						</td>
						<td class="center">
							<a href="mailto:<?php echo $email->recipient;?>" target="_blank"><?php echo $email->recipient;?></a>
						</td>
						<td class="center">
							<?php echo $this->html('grid.published' , $email , 'mailer' , 'status'); ?>
						</td>
						<td class="center">
							<?php echo $email->created; ?>
						</td>
						<td class="center">
							<?php echo $email->id; ?>
						</td>
					</tr>
					<?php $i++; ?>
					<?php } ?>

				<?php } else { ?>
				<tr class="is-empty">
					<td colspan="8" class="empty">
						<?php echo JText::_('COM_KOMENTO_MAILER_NO_EMAILS_YET'); ?>
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
<input type="hidden" name="filter_order" value="<?php echo $ordering;?>" data-kt-table-ordering />
<input type="hidden" name="filter_order_Dir" value="<?php echo $direction;?>" data-kt-table-direction />
<input type="hidden" name="boxchecked" value="0" data-kt-table-boxchecked />
<input type="hidden" name="task" value="" data-kt-table-task />
<input type="hidden" name="option" value="com_komento" />
<input type="hidden" name="view" value="mailq" />
<input type="hidden" name="controller" value="mailq" />
</form>
