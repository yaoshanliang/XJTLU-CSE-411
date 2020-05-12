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
defined ('_JEXEC') or die ('Restricted access');

JHtml::_('jquery.framework');
jimport('joomla.application.component.helper');

require_once ( JPATH_COMPONENT .'/parser/addon-parser.php' );
$doc = JFactory::getDocument();
$user = JFactory::getUser();
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_jwpagefactory');

if ($params->get('fontawesome',1)) {
	$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
	$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
}
if (!$params->get('disableanimatecss',0)) {
	$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/animate.min.css');
}
if (!$params->get('disablecss',0)) {
	$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
}
if ($params->get('addcontainer', 1)) {
	$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/jwpagecontainer.css');
}

$doc->addScript(JUri::base(true) . '/components/com_jwpagefactory/assets/js/jquery.parallax.js');

$doc->addScript(JUri::base(true) . '/components/com_jwpagefactory/assets/js/jwpagefactory.js');

$menus = $app->getMenu();
$menu = $menus->getActive();
$menuClassPrefix = '';
$showPageHeading = 0;

// check active menu item
if ($menu) {
	$menuClassPrefix 	= $menu->params->get('pageclass_sfx');
	$showPageHeading 	= $menu->params->get('show_page_heading');
	$menuheading 		= $menu->params->get('page_heading');
}

$page = $this->item;

require_once JPATH_COMPONENT_ADMINISTRATOR . '/builder/classes/addon.php';
$page->text = JwPageFactoryAddonHelper::__($page->text);
$content = json_decode($page->text);

// Add page css
if(isset($page->css) && $page->css) {
	$doc->addStyledeclaration($page->css);
}

$app = JFactory::getApplication();
$input = $app->input;
$Itemid = $input->get('Itemid', 0, 'INT');

$url = JRoute::_('index.php?option=com_jwpagefactory&view=form&layout=edit&tmpl=component&id=' . $page->id . '&Itemid=' . $Itemid, false);
$root = JURI::base();
$root = new JURI($root);
$link = $root->getScheme() . '://' . $root->getHost() . (!in_array($root->getPort(),array(80,443)) ? ':'.$root->getPort() : ''). $url;

?>

<div id="jw-page-factory" class="jw-page-factory <?php echo $menuClassPrefix; ?> page-<?php echo $page->id; ?>">

	<?php
	if($showPageHeading) {
		?>
		<div class="page-header">
			<h1 itemprop="name">
				<?php
				if($menuheading) {
					echo $menuheading;
				} else {
					echo $page->title;
				}
				?>
			</h1>
		</div>
		<?php
	}
	?>

	<div class="page-content">
		<?php $pageName = 'page-' . $page->id; ?>
		<?php echo JwpfAddonParser::viewAddons( $content, 0, $pageName ); ?>
		<?php
		$authorised = $user->authorise('core.edit', 'com_jwpagefactory') || $user->authorise('core.edit', 'com_jwpagefactory.page.' . $page->id) || ($user->authorise('core.edit.own', 'com_jwpagefactory.page.' . $page->id) && $page->created_by == $user->id);
		if ($authorised) {
			echo '<a class="jw-pagefactory-page-edit" href="'. $link . '"><i class="fa fa-edit"></i> ' . JText::_('COM_JWPAGEFACTORY_PAGE_EDIT') . '</a>';
		}
		?>
	</div>
</div>
