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
if(!class_exists('JwpagefactoryHelper')) {
	require_once dirname(__DIR__) . '/helpers/jwpagefactory.php';
}
?>
<div class="jw-pagefactory-brand">
	<a href="index.php?option=com_jwpagefactory">
		<img src="<?php echo JURI::root(true) . '/administrator/components/com_jwpagefactory/assets/img/logo-white.svg'; ?>" alt="JW Page Factory">
		<span>PRO <?php echo JwpagefactoryHelper::getVersion(); ?></span>
	</a>
</div>
