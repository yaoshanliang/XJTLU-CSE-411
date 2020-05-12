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

JHtml::_('jquery.framework');
JHtml::_('jquery.ui', array('core', 'sortable'));
JHtml::_('formbehavior.chosen', 'select');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/builder/classes/base.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/builder/classes/config.php';

$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_jwpagefactory');

$doc->addStylesheet(JURI::base(true) . '/administrator/components/com_jwpagefactory/assets/css/pbfont.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/animate.min.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/medium-editor.min.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/medium-editor-beagle.min.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/edit-iframe.css');
if ($params->get('addcontainer', 1)) {
	$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/jwpagecontainer.css');
}

$doc->addScriptdeclaration('var pagefactory_base="' . JURI::root() . '";');
$doc->addScript(JUri::base(true) . '/components/com_jwpagefactory/assets/js/medium-editor.min.js');
$doc->addScript(JURI::base(true) . '/administrator/components/com_jwpagefactory/assets/js/script.js');
$doc->addScript(JUri::base(true) . '/components/com_jwpagefactory/assets/js/actions.js');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/jquery.parallax.js');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/jwpagefactory.js');

$menus = $app->getMenu();
$menu = $menus->getActive();
$menuClassPrefix = '';
$showPageHeading = 0;

// check active menu item
if ($menu) {
	$menuClassPrefix = $menu->params->get('pageclass_sfx');
	$showPageHeading = $menu->params->get('show_page_heading');
	$menuheading = $menu->params->get('page_heading');
}

require_once JPATH_COMPONENT_ADMINISTRATOR . '/builder/classes/addon.php';
$this->item->text = JwPageFactoryAddonHelper::__($this->item->text, true);
//$this->item->text = JwPageFactoryAddonHelper::getFontendEditingPage($this->item->text);

JwPageFactoryBase::loadAddons();
$addons_list = JwAddonsConfig::$addons;

foreach ($addons_list as &$addon) {
	$addon['visibility'] = true;
	unset($addon['attr']);
}
JwPageFactoryBase::loadAssets($addons_list);
$addon_cats = JwPageFactoryBase::getAddonCategories($addons_list);
$doc->addScriptdeclaration('var addonsJSON=' . json_encode($addons_list) . ';');
$doc->addScriptdeclaration('var addonCats=' . json_encode($addon_cats) . ';');

if (!$this->item->text) {
	$doc->addScriptdeclaration('var initialState=[];');
} else {
	$doc->addScriptdeclaration('var initialState=' . $this->item->text . ';');
}
?>

<div id="jw-page-factory" class="jw-pagefactory <?php echo $menuClassPrefix; ?> page-<?php echo $this->item->id; ?>">
	<div id="jw-pagefactory-container">
		<div class="jw-pagefactory-loading-wrapper">
			<div class="jw-pagefactory-loading">
				<i class="pbfont pbfont-pagefactory"></i>
			</div>
		</div>
	</div>
</div>
<style id="jw-pagefactory-css" type="text/css">
	<?php echo $this->item->css; ?>
</style>
<script type="text/javascript">
    jQuery(document).on('click', 'a', function (e) {
        e.preventDefault();
    })

    jQuery(document).on('click', '.jw-editable-content', function (e) {
        e.preventDefault();
        var ids = jQuery(this).attr('id')

        var editor = new MediumEditor('#' + ids, {
            toolbar: {
                allowMultiParagraphSelection: true,
                buttons: [
                    'bold', 'italic', 'underline',
                    {
                        name: 'anchor',
                        contentDefault: '<i class="fa fa-link"></i>',
                        anchorPreview: false,
                        formSaveLabel: '<i class="fa fa-check"></i>',
                        formCloseLabel: '<i class="fa fa-times"></i>'
                    }, 'h2', 'h3',
                    {
                        name: 'unorderedlist',
                        contentDefault: '<i class="fa fa-list-ul"></i>'
                    },
                    {
                        name: 'orderedlist',
                        contentDefault: '<i class="fa fa-list-ol"></i>'
                    }
                ],
                diffLeft: 0,
                diffTop: -10,
                firstButtonClass: 'medium-editor-button-first',
                lastButtonClass: 'medium-editor-button-last',
                relativeContainer: null,
                standardizeSelectionStart: false,
                static: false,
                align: 'center',
                sticky: false,
                updateOnEmptySelection: false,
            }
        });
    })

</script>
