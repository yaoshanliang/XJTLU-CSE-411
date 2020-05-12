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

require_once JPATH_ROOT . '/components/com_jwpagefactory/parser/addon-parser.php';
$doc = JFactory::getDocument();
$input = JFactory::getApplication()->input;
$jwpf_param = JComponentHelper::getParams('com_jwpagefactory');
if ($jwpf_param->get('fontawesome', 1)) {
	$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
	$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
}
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/animate.min.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/jwpagefactory.css');
$doc->addScript(JUri::base(true) . '/components/com_jwpagefactory/assets/js/jquery.parallax.js');
$doc->addScript(JUri::base(true) . '/components/com_jwpagefactory/assets/js/jwpagefactory.js');
?>
<div class="mod-jwpagefactory <?php echo $moduleclass_sfx ?> jw-page-factory" data-module_id="<?php echo $module->id; ?>">
	<div class="page-content">
		<?php echo JwpfAddonParser::viewAddons(json_decode($data), true, 'module'); ?>
	</div>
</div>
