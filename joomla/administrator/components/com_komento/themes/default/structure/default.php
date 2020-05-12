<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div id="kt" class="kt-backend">
	<div class="app-alert o-alert o-alert--danger t-hidden" style="margin-bottom: 0;padding: 15px 24px;font-size: 12px;" data-outdated-banner>
		<div class="row-table">
			<div class="col-cell cell-tight">
				<i class="app-alert__icon fa fa-bolt"></i>
			</div>
			<div class="col-cell alert-message">
				Running on an <b>outdated version of Komento</b> which may potentially be <b>exploited by attackers</b>. Please <b><u>upgrade</u></b> to the latest version the soonest possible.
			</div>
			<div class="col-cell cell-tight">
				<a href="<?php echo JRoute::_('index.php?option=com_komento&launchInstaller=1');?>" class="btn btn-danger">
					<b><i class="fa fa-bolt"></i>&nbsp; Update Now!</b>
				</a>
			</div>
		</div>
	</div>

	<div class="app">
		<?php echo $sidebar ?>

		<div class="app-content">
			<?php echo KT::info()->html(true); ?>

			<?php if ($heading || $description) { ?>
				<div class="app-head">
					<h2><?php echo $heading; ?></h2>
					<p><?php echo $description; ?></p>
				</div>
			<?php } ?>

			<div class="app-body">
				<?php echo $output; ?>
			</div>
		</div>
	</div>
</div>
