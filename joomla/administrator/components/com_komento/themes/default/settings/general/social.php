<?php
/**
* @package		Komento
* @copyright	Copyright (C) 2010 - 2019 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="row">
	<div class="col-md-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_BOOKMARK_PROVIDERS'); ?>
			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'enable_share', 'COM_KOMENTO_SETTINGS_SHARE_ENABLE'); ?>

				<?php echo $this->html('settings.toggle', 'share_facebook', 'COM_KOMENTO_SETTINGS_SHARE_FACEBOOK'); ?>

				<?php echo $this->html('settings.toggle', 'share_twitter', 'COM_KOMENTO_SETTINGS_SHARE_TWITTER'); ?>

				<?php echo $this->html('settings.toggle', 'share_linkedin', 'COM_KOMENTO_SETTINGS_SHARE_LINKEDIN'); ?>

				<?php echo $this->html('settings.toggle', 'share_tumblr', 'COM_KOMENTO_SETTINGS_SHARE_TUMBLR'); ?>

				<?php echo $this->html('settings.toggle', 'share_reddit', 'COM_KOMENTO_SETTINGS_SHARE_REDDIT'); ?>

				<?php echo $this->html('settings.toggle', 'share_stumbleupon', 'COM_KOMENTO_SETTINGS_SHARE_STUMBLEUPON'); ?>
			</div>
		</div>
	</div>

	<div class="col-md-6">
	</div>
</div>

