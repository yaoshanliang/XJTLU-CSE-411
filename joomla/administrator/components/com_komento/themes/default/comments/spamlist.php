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
<form action="index.php?option=com_komento&view=comments" name="adminForm" id="adminForm" method="post" data-kt-table>
	<div class="app-filter-bar">
		<div class="app-filter-bar__cell">
			<?php echo $this->html('table.search' , $search); ?>
		</div>

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
					<th width="1%"><input type="checkbox" id="toggle" name="toggle" value="" onClick="Joomla.checkAll(this);" /></th>

					<th><?php echo JText::_('COM_KOMENTO_COLUMN_COMMENT'); ?></th>

					<?php if ($this->config->get('antispam_akismet_key')) { ?>
					<th width="10%" class="center"><?php echo JText::_('COM_KOMENTO_SUBMIT_AKISMET'); ?></th>
					<?php } ?>
					
					<th width="25%" class="center"><?php echo JText::_('COM_KOMENTO_COLUMN_EXTENSION'); ?></th>

					<th width="10%" class="center"><?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_COLUMN_DATE'), 'created', $orderDirection, $order); ?></th>

					<th width="10%" class="center"><?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_COLUMN_AUTHOR'), 'created_by', $orderDirection, $order); ?></th>

					<th width="1%" class="center"><?php echo JHTML::_('grid.sort', JText::_('COM_KOMENTO_COLUMN_ID'), 'id', $orderDirection, $order); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ($comments) { ?>

					<?php $i = 0; ?>
					<?php foreach ($comments as $comment) { ?>

						<tr id="<?php echo 'kmt-' . $comment->id; ?>" class="kmt-row" depth="<?php echo $comment->depth; ?>" parentid="<?php echo $comment->parent_id; ?>">
							<td class="center">
								<?php echo $this->html('grid.id', $i, $comment->id); ?>
							</td>

							<td class="comment-cell">
								<div class="row-table cell-top">
									<div class="col-cell">
										<a href="<?php echo JRoute::_('index.php?option=com_komento&view=comments&amp;layout=form&amp;id=' . $comment->id); ?>">
											<?php echo JString::substr(strip_tags($comment->getContent()), 0, 80); ?>...
										</a>
									</div>
								</div>
							</td>

							<?php if ($this->config->get('antispam_akismet_key')) { ?>
							<td class="center">
								<a href="javascript:void(0);" class="btn btn-sm btn-kt-danger-o" data-akismet-button data-type="spam" data-id="<?php echo $comment->id; ?>">
									<?php echo JText::_('COM_KOMENTO_AKISMET_SPAM'); ?>
								</a>

								<a href="javascript:void(0);" class="btn btn-sm btn-kt-primary-o" data-akismet-button data-type="ham" data-id="<?php echo $comment->id; ?>">
									<?php echo JText::_('COM_KOMENTO_AKISMET_HAM'); ?>
								</a>
							</td>
							<?php } ?>
							
							<td class="center">
								<?php if ($comment->extension) { ?>
									<a href="<?php echo $comment->getPermalink(); ?>" target=_blank><?php echo $comment->contenttitle; ?></a>
								<?php } else { ?>
									<span class="error"><?php echo $comment->contenttitle; ?></span>
								<?php } ?>
								(<?php echo $comment->componenttitle; ?>)
							</td>

							<td class="center">
								<?php echo $comment->getCreatedDate()->toFormat(JText::_('DATE_FORMAT_LC2')); ?>
							</td>

							<!-- Author -->
							<td class="center">
								<?php echo $comment->name; ?> (<?php echo $comment->ip;?>)
							</td>

							<td class="center">
								<?php echo $comment->id; ?>
							</td>
						</tr>
						<?php $i++; ?>
					<?php } ?>

				<?php } else { ?>
				<tr class="is-empty">
					<td colspan="13" class="empty">
						<?php echo JText::_('COM_KOMENTO_COMMENTS_NO_SPAM_COMMENTS'); ?>
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
	<input type="hidden" name="filter_order" value="<?php echo $order;?>" data-table-grid-ordering />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDirection;?>" data-table-grid-direction />
	<input type="hidden" name="boxchecked" value="0" data-table-grid-box-checked />
	<input type="hidden" name="task" value="" data-table-grid-task />
	<input type="hidden" name="action" value="" data-table-grid-action />
	<input type="hidden" name="option" value="com_komento" />
	<input type="hidden" name="view" value="comments" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<input type="hidden" name="layout" value="spamlist" />
	<input type="hidden" name="controller" value="comments" />
</form>

<?php if ($this->config->get('antispam_akismet_key')) { ?>
<div id="toolbar-akismet" class="btn-wrapper t-hidden" data-akismet-dropdown>
	<div class="dropdown">
		<button type="button" class="btn btn-small dropdown-toggle" data-toggle="dropdown">
			<span class="icon-wand"></span> <?php echo JText::_('COM_KOMENTO_AKISMET');?> &nbsp;<span class="caret"></span>
		</button>

		<ul class="dropdown-menu">
			<li>
				<a href="javascript:void(0);" data-akismet-submit-spam>
					<?php echo JText::_('COM_KOMENTO_TRAIN_AKISMET_SPAM'); ?>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" data-akismet-submit-ham>
					<?php echo JText::_('COM_KOMENTO_TRAIN_AKISMET_HAM'); ?>
				</a>
			</li>
		</ul>
	</div>
</div>
<?php } ?>

