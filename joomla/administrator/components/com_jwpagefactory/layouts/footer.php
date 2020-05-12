<?php
/**
 * @author       JoomWorker
 * @email        info@joomla.work
 * @url          http://www.joomla.work
 * @copyright    Copyright (c) 2010 - 2019 JoomWorker
 * @license      GNU General Public License version 2 or later
 * @date         2019/01/01 09:30
 */
defined('_JEXEC') or die();

$doc = JFactory::getDocument();
$params = JComponentHelper::getParams('com_jwpagefactory');
$input = JFactory::getApplication()->input;

$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-5.min.css');
$doc->addStyleSheet(JUri::base(true) . '/components/com_jwpagefactory/assets/css/font-awesome-v4-shims.css');
$doc->addStylesheet(JURI::base(true) . '/components/com_jwpagefactory/assets/css/common.css');
?>
<div class="pagefactory-footer clearfix">
	<div class="jw-pagefactory-row">
		<div class="col-md-5">
			<div class="copyright-info">

			</div>
		</div>

		<div class="col-md-7">
			<div class="pagefactory-links">
				<ul>
					<li>
						<a target="_blank" href="http://www.joomla.work">JoomWorker</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
