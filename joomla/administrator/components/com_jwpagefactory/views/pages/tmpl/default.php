<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
//no direct accees
defined('_JEXEC') or die ('Restricted access');

JHtml::_('bootstrap.tooltip');
JHtml::_('formbehavior.chosen', '.filter-select select, .jw-pagefactory-pages-toolbar select');

$doc = JFactory::getDocument();
$doc->addScriptdeclaration('var pagefactory_base="' . JURI::root() . 'administrator/";');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/pbfont.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/utilities.js');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/notice.js');

$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option=com_jwpagefactory&task=pages.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'pageList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}

$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>

<div class="jw-pagefactory-admin-top"></div>
<div class="jw-pagefactory-admin-notice"></div>

<div class="jw-pagefactory-admin clearfix" style="position: relative;">
	<form action="<?php echo JRoute::_('index.php?option=com_jwpagefactory&view=pages', false); ?>" method="post" name="adminForm" id="adminForm" class="clearfix">
		<div id="j-sidebar-container" class="span2">
			<?php echo JLayoutHelper::render('brand'); ?>
			<?php echo $this->sidebar; ?>
		</div>

		<div id="j-main-container" class="span10">
			<div class="jw-pagefactory-main-container-inner">

				<div class="jw-pagefactory-pages-toolbar clearfix">
					<div class="filter-search pull-left">
						<div class="jw-pagefactory-input-group">
							<input type="text" class="jw-pagefactory-form-control" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
							<span class="jw-pagefactory-input-group-btn">
								<button class="jw-pagefactory-btn jw-pagefactory-btn-success" type="submit" class="hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="fa fa-search"></i></button>
							</span>
						</div>
					</div>

					<div class="pull-right hidden-phone">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>

					<div class="pull-right hidden-phone">
						<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
							<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
							<option value="asc"<?php echo ($listDirn == 'asc') ? 'selected="selected"' : ''; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
							<option value="desc"<?php echo ($listDirn == 'desc') ? 'selected="selected"' : ''; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
						</select>
					</div>

					<div class="pull-right">
						<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
							<option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
							<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
						</select>
					</div>
				</div>

				<?php echo $this->loadTemplate('notice'); ?>

				<?php
				$app = JFactory::getApplication();
				$messages = $app->getMessageQueue();
				if (empty($this->items)) {
					$messages = array(array('type' => 'warning', 'message' => JText::_('COM_JWPAGEFACTORY_NO_PAGE_MATCHING_RESULTS')));
				}
				?>

				<?php if (count((array)$messages)) { ?>
					<div class="jw-pagefactory-message-container">
						<?php foreach ($messages as $key => $message) { ?>
							<div class="alert alert-<?php echo str_replace(array('message', 'error', 'notice'), array('success', 'danger', 'info'), $message['type']); ?>">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								<h4 class="alert-heading"><?php echo ucfirst($message['type']); ?></h4>
								<div class="alert-message"><?php echo $message['message']; ?></div>
							</div>
							<?php
						} ?>
					</div>
				<?php } ?>

				<?php
				if (count((array)$this->items)) {
					?>
					<div class="jw-pagefactory-pages">
						<table class="table table-striped" id="pageList">
							<thead>
							<tr>
								<th width="1%" class="nowrap center hidden-phone">
									<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder); ?>
								</th>
								<th width="1%" class="hidden-phone">
									<?php echo JHtml::_('grid.checkall'); ?>
								</th>
								<th>
									<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
								</th>
								<th width="10%" class="nowrap hidden-phone">
									<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
								</th>
								<th width="5%" class="nowrap hidden-phone">
									<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
								</th>
								<th width="1%" class="nowrap hidden-phone">
									<?php echo JHtml::_('grid.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
								</th>
								<th width="1%" class="nowrap center">
									<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
								</th>
								<th width="1%" class="nowrap hidden-phone">
									<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
								</th>
							</tr>
							</thead>

							<tfoot>
							<tr>
								<td colspan="15">
									<?php echo $this->pagination->getListFooter(); ?>
								</td>
							</tr>
							</tfoot>

							<tbody>
							<?php
							if (is_array($this->items) && count($this->items)) {
								foreach ($this->items as $i => $item) {
									?>
									<?php
									$item->max_ordering = 0;
									$ordering = ($listOrder == 'a.ordering');
									$canEdit = $user->authorise('core.edit', 'com_jwpagefactory.page.' . $item->id) || ($user->authorise('core.edit.own', 'com_jwpagefactory.page.' . $item->id) && $item->created_by == $userId);
									$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
									$canChange = $user->authorise('core.edit.state', 'com_jwpagefactory.page.' . $item->id) && $canCheckin;
									?>
									<tr>
										<td class="order nowrap center hidden-phone">
											<?php
											$iconClass = '';
											if (!$canChange) {
												$iconClass = ' inactive';
											} elseif (!$saveOrder) {
												$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
											}
											?>
											<span class="sortable-handler<?php echo $iconClass ?>">
													<span class="icon-menu"></span>
												</span>
											<?php if ($canChange && $saveOrder) : ?>
												<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
											<?php endif; ?>
										</td>
										<td class="center hidden-phone">
											<?php echo JHtml::_('grid.id', $i, $item->id); ?>
										</td>
										<td>
											<?php if ($item->checked_out) : ?>
												<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'pages.', $canCheckin); ?>
											<?php endif; ?>

											<?php if ($canEdit) : ?>
												<a class="jw-pagefactory-page-title" href="<?php echo JRoute::_('index.php?option=com_jwpagefactory&task=page.edit&id=' . $item->id, false); ?>">
													<?php echo $this->escape($item->title); ?>
												</a>
											<?php else : ?>
												<?php echo $this->escape($item->title); ?>
											<?php endif; ?>

											<a class="jw-pagefactory-btn jw-pagefactory-btn-default jw-pagefactory-btn-xs jw-pagefactory-btn-preview-page" target="_blank" href="<?php echo $item->preview; ?>" style="color: #fff; margin: 5px;"><?php echo JText::_('COM_JWPAGEFACTORY_PREVIEW'); ?></a>

											<?php if ($canEdit) : ?>
												<a class="jw-pagefactory-btn jw-pagefactory-btn-success jw-pagefactory-btn-xs jw-pagefactory-btn-frontend-editor" target="_blank" href="<?php echo $item->frontend_edit; ?>" style="color: #fff; margin: 5px 0;"><?php echo JText::_('COM_JWPAGEFACTORY_FRONTEND_EDITOR'); ?></a>
											<?php endif; ?>

											<?php
											if (isset($item->created_by) && $item->created_by) {
												$author = JFactory::getUser((int)$item->created_by);
												if ($author->id) {
													?>
													<div class="small">
														<?php echo JText::_('JAUTHOR') . ": "; ?>
														<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . $author->id, false); ?>" title="<?php echo JText::_('JAUTHOR'); ?>"><?php echo $this->escape($author->name); ?></a>
													</div>
													<?php
												}
											}
											?>

											<?php if (isset($item->category_title) && $item->category_title): ?>
												<div class="small">
													<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
												</div>
											<?php endif; ?>

										</td>
										<td class="small hidden-phone">
											<?php echo $this->escape($item->access_title); ?>
										</td>
										<td class="small nowrap hidden-phone">
											<?php if ($item->language == '*') : ?>
												<?php echo JText::alt('JALL', 'language'); ?>
											<?php else: ?>
												<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
											<?php endif; ?>
										</td>

										<td class="center hidden-phone">
											<?php echo (int)$item->hits; ?>
										</td>

										<td class="center">
											<?php echo JHtml::_('jgrid.published', $item->published, $i, 'pages.', $canChange); ?>
										</td>

										<td class="center hidden-phone">
											<?php echo (int)$item->id; ?>
										</td>

									</tr>
									<?php
								}
							}
							?>
							</tbody>
						</table>
					</div>
					<?php
				}
				?>

			</div>

			<div class="clearfix"></div>
			<?php echo JLayoutHelper::render('footer'); ?>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>

		</div>
	</form>
</div>
