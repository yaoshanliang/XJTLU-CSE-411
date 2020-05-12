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
jimport('joomla.application.component.helper');
require_once JPATH_COMPONENT . '/builder/classes/base.php';
require_once JPATH_COMPONENT . '/builder/classes/config.php';

JHTML::_('behavior.keepalive');
JHtml::_('jquery.ui', array('core', 'sortable'));
JHtml::_('formbehavior.chosen', 'select');

$doc = JFactory::getDocument();
$doc->addScriptdeclaration('var pagefactory_base="' . JURI::root() . '";');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/pbfont.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/react-select.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');

//js
$doc->addScript(JURI::root(true) . '/media/editors/tinymce/tinymce.min.js');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/script.js');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/actions.js');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/csslint.js');

require_once JPATH_ROOT . '/administrator/components/com_jwpagefactory/helpers/language.php';
$app = JFactory::getApplication();

global $pageId;
global $language;

$pageId = $this->item->id;
$language = $this->item->language;

// Addon List Initialize
JwPageFactoryBase::loadAddons();
$fa_icon_list = JwPageFactoryBase::getIconList(); // Icon List
$animateNames = JwPageFactoryBase::getAnimationsList(); // Animation Names
$accessLevels = JwPageFactoryBase::getAccessLevelList(); // Access Levels
$article_cats = JwPageFactoryBase::getArticleCategories(); // Article Categories
$moduleAttr = JwPageFactoryBase::getModuleAttributes(); // Module Postions and Module Lits
$rowSettings = JwPageFactoryBase::getRowGlobalSettings(); // Row Settings Attributes
$columnSettings = JwPageFactoryBase::getColumnGlobalSettings(); // Column Settings Attributes
$global_attributes = JwPageFactoryBase::addonOptions();

// Addon List
$addons_list = JwAddonsConfig::$addons;
$globalDefault = JwPageFactoryBase::getSettingsDefaultValue($global_attributes);

JPluginHelper::importPlugin('system');
$dispatcher = JEventDispatcher::getInstance();

foreach ($addons_list as $key => &$addon) {
	$new_default_value = JwPageFactoryBase::getSettingsDefaultValue($addon['attr']);
	$addon['default'] = array_merge($new_default_value['default'], $globalDefault['default']);

	$results = $dispatcher->trigger('onBeforeAddonConfigure', array($key, &$addon));
}

$row_default_value = JwPageFactoryBase::getSettingsDefaultValue($rowSettings['attr']);
$rowSettings['default'] = $row_default_value;

$column_default_value = JwPageFactoryBase::getSettingsDefaultValue($columnSettings['attr']);
$columnSettings['default'] = $column_default_value;

$doc->addScriptdeclaration('var addonsJSON=' . json_encode($addons_list) . ';');

// Addon Categories
$addon_cats = JwPageFactoryBase::getAddonCategories($addons_list);
$doc->addScriptdeclaration('var addonCats=' . json_encode($addon_cats) . ';');

// Global Attributes
$doc->addScriptdeclaration('var globalAttr=' . json_encode($global_attributes) . ';');
$doc->addScriptdeclaration('var faIconList=' . json_encode($fa_icon_list) . ';');
$doc->addScriptdeclaration('var animateNames=' . json_encode($animateNames) . ';');
$doc->addScriptdeclaration('var accessLevels=' . json_encode($accessLevels) . ';');
$doc->addScriptdeclaration('var articleCats=' . json_encode($article_cats) . ';');
$doc->addScriptdeclaration('var moduleAttr=' . json_encode($moduleAttr) . ';');
$doc->addScriptdeclaration('var rowSettings=' . json_encode($rowSettings) . ';');
$doc->addScriptdeclaration('var colSettings=' . json_encode($columnSettings) . ';');
// Media
$mediaParams = JComponentHelper::getParams('com_media');
$doc->addScriptdeclaration('var jwpfMediaPath=\'/' . $mediaParams->get('file_path', 'images') . '\';');

if (!$this->item->text) {
	$doc->addScriptdeclaration('var initialState=[];');
} else {
	require_once JPATH_COMPONENT . '/builder/classes/addon.php';
	$this->item->text = JwPageFactoryAddonHelper::__($this->item->text);
	$doc->addScriptdeclaration('var initialState=' . $this->item->text . ';');
}

$conf = JFactory::getConfig();
$editor = $conf->get('editor');
if ($editor == 'jce') {
	require_once(JPATH_ADMINISTRATOR . '/components/com_jce/includes/base.php');
	wfimport('admin.models.editor');
	$editor = new WFModelEditor();

	$settings = $editor->getEditorSettings();

	$app->triggerEvent('onBeforeWfEditorRender', array(&$settings));
	echo $editor->render($settings);
}

?>

<div class="jw-pagefactory-admin">

	<form action="#" method="post" name="adminForm" id="adminForm" class="form-validate">

		<div class="clearfix jw-page-header">
			<div class="pull-left">
				<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
			</div>
			<div class="pull-right">
				<div class="text-right">
					<div class="jw-pagefactory-btn-group">
						<a href="#" id="btn-save-page" class="jw-pagefactory-btn jw-pagefactory-btn-success"><i class="fa fa-save"></i> <?php echo JText::_('COM_JWPAGEFACTORY_SAVE'); ?></a>
						<a href="#" class="jw-pagefactory-btn jw-pagefactory-btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-chevron-down"></i></a>
						<ul class="dropdown-menu">
							<li><a id="btn-save-close" href="#"><i class="fa fa-check"></i> <?php echo JText::_('COM_JWPAGEFACTORY_SAVE_CLOSE'); ?></a></li>
							<li><a id="btn-save-new" href="#"><i class="fa fa-plus"></i> <?php echo JText::_('COM_JWPAGEFACTORY_SAVE_NEW'); ?></a></li>
							<li><a id="btn-save-copy" href="#"><i class="fa fa-clone"></i> <?php echo JText::_('COM_JWPAGEFACTORY_SAVE_COPY'); ?></a></li>
						</ul>
					</div>

					<?php if ($this->item->id) { ?>
						<div class="jw-pagefactory-btn-group">
							<a id="btn-page-frontend-editor" target="_blank" href="<?php echo $this->item->frontend_edit; ?>" class="jw-pagefactory-btn jw-pagefactory-btn-info"><i class="fa fa-edit"></i> <?php echo JText::_('COM_JWPAGEFACTORY_FRONTEND_EDITOR'); ?></a>
						</div>

						<div class="jw-pagefactory-btn-group">
							<a id="btn-page-preview" target="_blank" href="<?php echo $this->item->preview; ?>" class="jw-pagefactory-btn jw-pagefactory-btn-inverse"><i class="fa fa-eye"></i> <?php echo JText::_('COM_JWPAGEFACTORY_PREVIEW'); ?></a>
						</div>
					<?php } ?>

					<div class="jw-pagefactory-btn-group">
						<a href="#" id="btn-page-options" class="jw-pagefactory-btn jw-pagefactory-btn-inverse"><i class="fa fa-gear"></i> <?php echo JText::_('COM_JWPAGEFACTORY_OPTIONS'); ?></a>
					</div>

					<div class="jw-pagefactory-btn-group">
						<a href="#" class="jw-pagefactory-btn jw-pagefactory-btn-inverse" onclick="Joomla.submitbutton('page.cancel')"><i class="fa fa-times"></i> <?php echo JText::_('COM_JWPAGEFACTORY_CLOSE'); ?></a>
					</div>
				</div>
			</div>
		</div>

		<div id="jw-pagefactory-page-tools" class="clearfix jw-pagefactory-page-tools"></div>

		<div class="jw-pagefactory-sidebar-and-builder">

			<div id="jw-pagefactory-section-lib" class="clearfix jw-pagefactory-section-lib"></div>

			<div class="form-horizontal">
				<div class="row-fluid">
					<div class="span12">
						<?php
						$fields = $this->form->getFieldset('basic');
						foreach ($fields as $key => $field) {
							if (($field->name == 'jform[text]') || ($field->name == 'jform[id]')) {
								?>
								<div class="control-group hidden">
									<div class="control-label"><?php echo $field->label; ?></div>
									<div class="controls"><?php echo $field->input; ?></div>
								</div>
								<?php
							}
						}
						?>
						<div id="container"></div>
					</div>
				</div>
			</div>

		</div>

		<div class="jw-pagefactory-modal-alt">
			<div id="page-options" class="jw-pagefactory-modal-overlay" style="position:fixed;top:0;left:0;right:0;bottom:0;">
				<div class="jw-pagefactory-modal-content" style="position:fixed;top:0px;left:0px;right:0px;bottom:0px;">
					<div class="jw-pagefactory-modal-small">
						<h2 class="jw-pagefactory-modal-title"><?php echo JText::_('COM_JWPAGEFACTORY_PAGE_OPTIONS'); ?></h2>
						<div>
							<div class="page-options-content">

								<?php
								$fieldsets = $this->form->getFieldsets();
								?>

								<ul class="jw-pagefactory-nav jw-pagefactory-nav-tabs" id="pageTabs">
									<li class="active"><a href="#seosettings" data-toggle="tab"><i class="fa fa-bullseye"></i> <?php echo JText::_($fieldsets['seosettings']->label, true); ?></a></li>
									<li><a href="#pagecss" data-toggle="tab"><i class="fa fa-css3"></i> <?php echo JText::_($fieldsets['pagecss']->label, true); ?></a></li>
									<li><a href="#publishing" data-toggle="tab"><i class="fa fa-calendar-check-o"></i> <?php echo JText::_($fieldsets['publishing']->label, true); ?></a></li>
									<li><a href="#permissions" data-toggle="tab"><i class="fa fa-globe"></i> <?php echo JText::_($fieldsets['permissions']->label, true); ?></a></li>
								</ul>

								<div class="tab-content" id="pageContent">

									<div id="seosettings" class="tab-pane active">
										<?php foreach ($this->form->getFieldset('seosettings') as $key => $field) { ?>
											<div class="jw-pagefactory-form-group">
												<?php echo $field->label; ?>
												<?php echo str_replace(array('<input', '<textarea'), array('<input class="jw-pagefactory-form-control"', '<textarea class="jw-pagefactory-form-control"'), $field->input); ?>
											</div>
										<?php } ?>
									</div>

									<div id="pagecss" class="tab-pane">
										<?php foreach ($this->form->getFieldset('pagecss') as $key => $field) { ?>
											<div class="jw-pagefactory-form-group">
												<?php echo $field->label; ?>
												<?php echo str_replace(array('<textarea'), array('<textarea class="jw-pagefactory-form-control"'), $field->input); ?>
											</div>
										<?php } ?>
									</div>

									<div id="publishing" class="tab-pane">
										<?php foreach ($this->form->getFieldset('publishing') as $key => $field) { ?>
											<div class="jw-pagefactory-form-group">
												<?php echo $field->label; ?>
												<?php echo str_replace(array('<input', '<textarea'), array('<input class="jw-pagefactory-form-control"', '<textarea class="jw-pagefactory-form-control"'), $field->input); ?>
											</div>
										<?php } ?>
									</div>

									<div id="permissions" class="tab-pane">
										<?php foreach ($this->form->getFieldset('permissions') as $key => $field) { ?>
											<div class="jw-pagefactory-form-group">
												<?php echo str_replace(array('<input', '<textarea'), array('<input class="jw-pagefactory-form-control"', '<textarea class="jw-pagefactory-form-control"'), $field->input); ?>
											</div>
										<?php } ?>
									</div>
								</div>

							</div>

							<a id="btn-apply-page-options" class="jw-pagefactory-btn jw-pagefactory-btn-success" href="#"><i class="fa fa-check-square-o"></i> <?php echo JText::_('COM_JWPAGEFACTORY_APPLY'); ?></a>
							<a id="btn-cancel-page-options" class="jw-pagefactory-btn jw-pagefactory-btn-default" href="#"><i class="fa fa-times-circle-o"></i> <?php echo JText::_('COM_JWPAGEFACTORY_CANCEL'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>

<?php echo JLayoutHelper::render('footer'); ?>

<input type="hidden" id="form_task" name="task" value="page.apply"/>
<?php echo JHtml::_('form.token'); ?>
</form>
</div>
<div class="jw-pagefactory-notifications"></div>
<div class="jw-pagefactory-media-modal-overlay" style="display:none">
	<div id="jw-pagefactory-media-modal">
	</div>
</div>
<script type="text/javascript" src="<?php echo JURI::base(true) . '/components/com_jwpagefactory/assets/js/engine.js'; ?>"></script>
