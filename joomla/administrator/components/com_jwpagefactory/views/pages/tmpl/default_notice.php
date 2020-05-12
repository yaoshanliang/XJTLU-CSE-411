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

?>

<?php if (!$this->params->get('joomworker_license_key') || !$this->params->get('gmap_api')) { ?>
	<div class="jw-pagefactory-pages top-notice-bar">
		<div class="row-fluid">
			<div class="span12">
				<?php if (!$this->params->get('joomworker_license_key')) { ?>
					<div class="jwpf-active-notice">
						<p class="pull-left"><?php echo JText::sprintf('COM_JWPAGEFACTORY_NOTICE_NO_LICENSE', JRoute::_('index.php?option=com_config&view=component&component=com_jwpagefactory', false) . '#licenseupdate', API_SITE . '/index.php?option=com_pagefactoryservice&view=help&layout=activate'); ?></p>
						<!--<a href="#" class="jw-pagefactory-close"><span class="fa fa-times"></span></a>-->
						<div class="clearfix"></div>
					</div>
				<?php } ?>
				<?php if (!$this->params->get('gmap_api')) { ?>
					<div class="jwpf-active-notice">
						<p class="pull-left"><?php echo JText::sprintf('COM_JWPAGEFACTORY_NOTICE_NO_GOOGLE_MAP_API', JRoute::_('index.php?option=com_config&view=component&component=com_jwpagefactory&path=&return=' . urlencode(base64_encode(JUri::getInstance())), false), 'https://developers.google.com/maps/documentation/javascript/get-api-key'); ?></p>
						<div class="clearfix"></div>
					</div>
				<?php } ?>
				<?php if (!$this->params->get('amap_api')) { ?>
					<div class="jwpf-active-notice">
						<p class="pull-left"><?php echo JText::sprintf('COM_JWPAGEFACTORY_NOTICE_NO_AUTO_NAVI_MAP_API', JRoute::_('index.php?option=com_config&view=component&component=com_jwpagefactory&path=&return=' . urlencode(base64_encode(JUri::getInstance())), false), 'https://lbs.amap.com/api/javascript-api/guide/abc/prepare'); ?></p>
						<div class="clearfix"></div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
<?php } ?>
