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

if (!class_exists('JwpagefactoryHelper')) {
	JLoader::register('JwpagefactoryHelper', JPATH_ADMINISTRATOR . '/components/com_jwpagefactory/helpers/jwpagefactory.php');
}
?>

<div class="jw-pagefactory-pages jw-pagefactory-about-view">
	<div class="jwpf-about-view-wrap row-fluid">
		<div class="span4 jwpf-about-view-left span2">
			<img src="<?php echo JURI::root(); ?>administrator/components/com_jwpagefactory/assets/img/icon.svg" alt="JW Page Factory"/>
		</div>
		<div class="jwpf-about-view-right span8">
			<div class="jwpf-about-view-texts">
				<h2>页面工厂中文专业版 <span>版本号: <?php echo JwpagefactoryHelper::getVersion(); ?></span></h2>
				<p>页面工厂是一个非常强大的页面设计构建系统。<br>支持灵活的布局和拖拽功能，无论你是新手还是专业人士都能很方便的完成你的网站设计。</p>
				<p>使用页面工厂，您无需编写任何代码就可以构建一个独特的、出色的和功能强大的站点。<br>使用这个工具，任何人都可以在几分钟内建立一个专业的高质量的网站。</p>
			</div>
			<div class="jwpf-about-view-footer">
				<div class="pagefactory-links">
					<a class="btn btn-jwpf-custom" href="index.php?option=com_jwpagefactory&amp;task=page.add" target="_blank"><span class="fa fa-plus-circle"></span> 创建新页面</a>
				</div>
			</div>
		</div>
	</div>
</div>