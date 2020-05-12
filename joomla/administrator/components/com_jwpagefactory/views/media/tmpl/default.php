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

JHtml::_('formbehavior.chosen', '.jw-pagefactory-media-toolbar select');

$doc = JFactory::getDocument();
$doc->addScriptdeclaration('var pagefactory_base="' . JURI::root() . 'administrator/";');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/pbfont.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/media.js');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/utilities.js');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/notice.js');

JText::script('COM_JWPAGEFACTORY_MEDIA_MANAGER_CONFIRM_DELETE');
JText::script('COM_JWPAGEFACTORY_MEDIA_MANAGER_ENTER_DIRECTORY_NAME');
?>

<div class="jw-pagefactory-admin-top"></div>
<div class="jw-pagefactory-admin-notice"></div>

<div id="jw-pagefactory-media-manager" class="jw-pagefactory-admin<?php echo (count((array)$this->items)) ? '' : ' jw-pagefactory-media-manager-empty'; ?> clearfix" style="position: relative;">
	<div id="j-sidebar-container" class="span2">
		<?php echo JLayoutHelper::render('brand'); ?>
		<?php echo $this->sidebar; ?>

		<ul id="jw-pagefactory-media-types">
			<?php echo JLayoutHelper::render('media.categories', array('categories' => $this->categories)); ?>
		</ul>
	</div>

	<div id="j-main-container" class="span10">
		<div class="jw-pagefactory-main-container-inner">

			<div class="jw-pagefactory-media-toolbar clearfix">

				<div id="jw-pagefactory-media-tools" class="pull-left clearfix">
					<div>
						<input type="file" id="jw-pagefactory-media-input-file" multiple="multiple" style="display:none">
						<a href="#" id="jw-pagefactory-upload-media" class="jw-pagefactory-btn jw-pagefactory-btn-success"><i class="fa fa-upload"></i><span class="hidden-phone hidden-xs"> <?php echo JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_UPLOAD_FILES'); ?></span></a>
					</div>

					<div style="display: none;">
						<a href="#" id="jw-pagefactory-cancel-media" class="jw-pagefactory-btn jw-pagefactory-btn-default"><i class="fa fa-times"></i> <?php echo JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_CANCEL'); ?></a>
					</div>

					<div style="display: none;">
						<a href="#" id="jw-pagefactory-media-create-folder" class="jw-pagefactory-btn jw-pagefactory-btn-primary"><i class="fa fa-plus"></i> <?php echo JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_CREATE_FOLDER'); ?></a>
					</div>

					<div>
						<div class="jw-pagefactory-media-search">
							<i class="fa fa-search"></i>
							<input type="text" class="jw-pagefactory-form-control" id="jw-pagefactory-media-search-input" placeholder="<?php echo JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_SEARCH'); ?>">
							<a href="#" class="jw-pagefactory-clear-search" style="display: none;"><i class="fa fa-times-circle"></i></a>
						</div>
					</div>
				</div>

				<div class="pull-right hidden-phone">
					<div>
						<select id="jw-pagefactory-media-filter" data-type="browse">
							<option value=""><?php echo JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_MEDIA_ALL'); ?></option>
							<?php foreach ($this->filters as $key => $this->filter) { ?>
								<option value="<?php echo $this->filter->year . '-' . $this->filter->month; ?>"><?php echo JHtml::_('date', $this->filter->year . '-' . $this->filter->month, 'F Y'); ?></option>
							<?php } ?>
						</select>
					</div>

					<div style="display: none;">
						<a href="#" id="jw-pagefactory-delete-media" class="jw-pagefactory-btn jw-pagefactory-btn-danger hidden-phone hidden-xs"><i class="fa fa-minus-circle"></i> <?php echo JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_DELETE'); ?></a>
					</div>
				</div>
			</div><!--/.page-factory-pages-toolbar-->

			<div class="jw-pagefactory-media-list clearfix">
				<div class="jw-pagefactory-media-empty">
					<div>
						<i class="fa fa-upload"></i>
						<h3 class="jw-pagefactory-media-empty-title">
							<?php echo JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_DRAG_DROP_UPLOAD'); ?>
						</h3>
						<div>
							<a href="#" id="jw-pagefactory-upload-media-empty" class="jw-pagefactory-btn jw-pagefactory-btn-primary jw-pagefactory-btn-lg"><?php echo JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_OR_SELECT'); ?></a>
						</div>
					</div>
				</div>
				<div class="jw-pagefactory-media-wrapper">
					<ul class="jw-pagefactory-media clearfix">
						<?php
						foreach ($this->items as $key => $this->item) {
							echo JLayoutHelper::render('media.format', array('media' => $this->item, 'support' => 'all'));
						}
						?>
					</ul>
					<?php if ($this->total > ($this->limit + $this->start)) { ?>
						<div class="jw-pagefactory-media-loadmore clearfix">
							<a id="jw-pagefactory-media-loadmore" class="jw-pagefactory-btn jw-pagefactory-btn-primary jw-pagefactory-btn-lg" href="#"><i class="fa fa-refresh"></i> <?php echo JText::_('COM_JWPAGEFACTORY_MEDIA_MANAGER_LOAD_MORE'); ?></a>
						</div>
					<?php } ?>
				</div>
			</div>

			<div class="clearfix"></div>
			<?php echo JLayoutHelper::render('footer'); ?>

		</div>
	</div>
</div>
