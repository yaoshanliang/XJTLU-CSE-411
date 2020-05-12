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
defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php?option=com_komento&view=maintenance" name="adminForm" id="adminForm" method="post" data-table-grid>
	<div class="app-filter-bar">
		<div class="app-filter-bar__cell app-filter-bar__cell--divider-left">
            <?php echo $this->html('table.filter', 'filter_version', $version, $versions); ?>
		</div>

		<div class="app-filter-bar__cell t-text--right t-lg-pr--lg">
			<div class="o-form-inline">
				<?php echo $pagination->getLimitBox(); ?>
			</div>
		</div>
	</div>

	<div class="panel-table">
		<table class="app-table table" data-comments-list>
			<thead>
				<tr>
					<th width="1%"><input type="checkbox" id="toggle" name="toggle" value="" onClick="Joomla.checkAll(this);" /></th>

                    <th class="title" nowrap="nowrap" style="text-align:left;">
                        <?php echo JText::_('COM_KOMENTO_MAINTENANCE_COLUMN_TITLE'); ?>
                    </th>

                    <th width="15%" class="center">
                        <?php echo JText::_('COM_KOMENTO_MAINTENANCE_COLUMN_VERSION'); ?>
                    </th>
				</tr>
			</thead>

			<tbody>
	            <?php if ($scripts) { ?>
	                <?php $i = 0; ?>
	                <?php foreach ($scripts as $script) { ?>
	                <tr>
	                    <td class="center">
	                        <?php echo JHTML::_('grid.id', $i++, $script->key); ?>
	                    </td>

	                    <td>
	                        <div><b><?php echo $script->title; ?></b></div>
	                        <div><?php echo $script->description; ?></div>
	                    </td>
	                    <td class="center"><?php echo $script->version; ?></td>
	                </tr>
	                <?php } ?>
	            <?php } else { ?>
	                <tr>
	                    <td colspan="3" align="center" class="center">
	                        <?php echo JText::_('COM_KOMENTO_MAINTENANCE_SCRIPT_NOT_FOUND');?>
	                    </td>
	                </tr>
	            <?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<td colspan="3">
						<div class="footer-pagination">
						<?php echo $pagination->getListFooter(); ?>
						</div>
					</td>
				</tr>
			</tfoot>

		</table>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="ordering" value="<?php echo $order;?>" data-table-grid-ordering />
	<input type="hidden" name="direction" value="<?php echo $orderDirection;?>" data-table-grid-direction />
	<input type="hidden" name="boxchecked" value="0" data-table-grid-box-checked />
	<input type="hidden" name="task" value="" data-table-grid-task />
	<input type="hidden" name="option" value="com_komento" />
    <input type="hidden" name="view" value="maintenance" />
    <input type="hidden" name="layout" />
</form>

