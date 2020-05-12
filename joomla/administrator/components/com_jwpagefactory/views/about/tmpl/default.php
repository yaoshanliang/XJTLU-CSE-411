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
defined('_JEXEC') or die ('restricted access');
JHtml::_('jquery.framework');
$doc = JFactory::getDocument();

if (!class_exists('JwpagefactoryHelper')) {
	require_once dirname(__DIR__) . '/helpers/jwpagefactory.php';
}

$doc->addScriptdeclaration('var pagefactory_base="' . JURI::root() . 'administrator/";');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/pbfont.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
$doc->addScript(JURI::base(true) . '/components/com_jwpagefactory/assets/js/notice.js');
?>

<div class="jw-pagefactory-admin-top"></div>
<div class="jw-pagefactory-admin-notice"></div>

<div class="jw-pagefactory-admin clearfix" style="position: relative;">
	<div id="j-sidebar-container" class="span2">
		<?php echo JLayoutHelper::render('brand'); ?>
		<?php echo $this->sidebar; ?>
	</div>

	<div id="j-main-container" class="span10">
		<div class="jw-pagefactory-main-container-inner">
			<div class="jw-pagefactory-pages-toolbar clearfix"></div>
			<?php echo $this->loadTemplate('about'); ?>
		</div>
	</div>
</div>
