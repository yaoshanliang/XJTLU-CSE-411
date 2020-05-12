<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2018 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div id="kt">
	<div class="kt-dashboard" data-kt-dashboard>
		<?php echo $this->output('site/dashboard/toolbar', array('layout' => 'download')); ?>

		<div class="kt-dashbard-content">
			<p>
				<strong><?php echo JText::_('COM_KT_GDPR_DOWNLOAD_INFORMATION');?></strong>
			</p>

			<?php if (!$download->id) { ?>
				<p><?php echo JText::_('COM_KT_GDPR_DOWNLOAD_INFORMATION_DESC');?></p>

				<a href="javascript:void(0);" class="btn btn-kt-primary" data-kt-gdpr-request>
					<?php echo JText::_('COM_KT_GDPR_REQUEST_DATA_BUTTON');?>
				</a>
			<?php } ?>
			
			<?php if ($download->id && ($download->isProcessing() || $download->isNew())) { ?>
				<p><?php echo JText::_('COM_KT_GDPR_DOWNLOAD_INFORMATION_PROCESSING');?></p>
			<?php } ?>

			<?php if ($download->id && $download->isReady()) { ?>
				<p>
					<?php echo JText::sprintf('COM_KT_GDPR_REQUEST_IS_READY_DESC', $download->getExpireDays());?>
				</p>
				<a href="<?php echo $download->getDownloadLink();?>" target="_blank" class="btn btn-kt-primary">
					<?php echo JText::_('COM_KT_GDPR_DOWNLOAD_MY_DATA');?>
				</a>
			<?php } ?>
			
		</div>
			
		<div class="o-alert o-alert--success t-hidden" data-kt-dashboard-notice></div>
	</div>
</div>

