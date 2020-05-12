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
<div class="row">
	<div class="col-lg-6">
		<div class="panel">
			<?php echo $this->html('panel.heading', 'COM_KOMENTO_SETTINGS_EASYDISCUSS'); ?>
			<div class="panel-body">
				<p class="clearfix">
					<img src="<?php echo JURI::root(); ?>media/com_komento/images/integrations/easydiscuss.png" align="left" width="64" class="t-lg-mr--xl" />
					<?php echo JText::_('COM_KOMENTO_WHAT_IS_EASYDISCUSS'); ?>
					<br /><br />
					<a href="https://stackideas.com/easydiscuss" class="btn btn-kt-primary btn-sm" target="_blank"><?php echo JText::_('COM_KOMENTO_GET_EASYDISCUSS'); ?></a>
				</p>

				<?php echo $this->html('settings.toggle', 'enable_discuss_points', 'COM_KOMENTO_SETTINGS_ACTIVITIES_ENABLE_DISCUSS_POINTS'); ?>
				<?php echo $this->html('settings.toggle', 'enable_discuss_log', 'COM_KOMENTO_SETTINGS_ACTIVITIES_ENABLE_DISCUSS_BADGES'); ?>
			</div>
		</div>
	</div>
</div>
