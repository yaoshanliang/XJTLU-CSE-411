<?php
/**
* @package      Komento
* @copyright    Copyright (C) 2010 - 2016 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* Komento is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');
$cronlink = rtrim(JURI::root(), '/') . '/index.php?option=com_komento&task=clearCaptcha'; ?>
<script type="text/javascript">
Komento.require().script('admin.database').done(function($) {
	$('[data-populate-depth]').implement('Komento.Controller.Database.DepthMaintenance');
	$('[data-fix-structure]').implement('Komento.Controller.Database.FixStructure');
});
</script>

<div class="row">
	<div class="col-lg-6">
		<div class="panel">
			<div class="panel-head"><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_INFO'); ?></div>
			<div class="panel-body">
				<?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_CRON_LINK'); ?>: <a href="<?php echo $cronlink; ?>" target="_blank"><?php echo $cronlink; ?></a>
			</div>
			<div class="panel-foot"><a href="http://stackideas.com/docs/komento/administrators/cronjobs/setting-up-cronjobs-in-cpanel" class="btn btn-success"><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_SETUP_CRONJOB') ;?></a></div>
		</div>

		<div class="panel">
			<div class="panel-head"><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_MAINTENANCE'); ?></div>
			<div class="panel-body">
				<div class="form-group">
					<?php echo $this->html('panel.label', 'COM_KOMENTO_SETTINGS_DATABASE_CLEAR_CAPTCHA_ON_PAGE_LOAD'); ?>

					<div class="col-md-7">
						<?php echo $this->html('form.toggler', 'database_clearcaptchaonpageload', $this->config->get('database_clearcaptchaonpageload')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel">
			<div class="panel-head"><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_UPDATE_POPULATE_DEPTH'); ?></div>
			<div class="panel-body" data-populate-depth>
				<p class="alert alert-danger"><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_UPDATE_POPULATE_DEPTH_WARNING'); ?></p>
				<div data-status-wrapper style="display: none;">
					<span><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_FIX_STRUCTURE_STATUS'); ?></span>
					<span class="fixStatus"></span>
				</div>

				<button type="button" class="btn btn-primary pull-right start"><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_START'); ?></button>
				<div data-status-wrapper style="display: none;">
					<span><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_STATUS'); ?></span>
					<span class="status"></span>
				</div>

				<div data-total-wrapper style="display: none;">
					<span><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_TOTAL_ARTICLES'); ?></span>
					<span class="total"></span>
				</div>

				<div data-count-wrapper style="display: none;">
					<span><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_COUNT_ARTICLES'); ?></span>
					<span class="count"></span>
				</div>
			</div>
		</div>

		<div class="panel">
			<div class="panel-head"><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_UPDATE_FIX_STRUCTURE'); ?></div>
			<div class="panel-body" data-fix-structure>
				<p><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_UPDATE_FIX_STRUCTURE_WARNING'); ?></p>
				
				<div class="form-group">
					<label class="col-md-5 control-label">
						<span ><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_FIX_STRUCTURE_SELECT_COMPONENT'); ?></span>
					</label>
					<div class="col-md-7">
						<?php echo $this->getComponentSelection(); ?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-5 control-label">
						<span ><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_FIX_STRUCTURE_SELECT_ARTICLE'); ?></span>
					</label>
					<div class="col-md-7">
						<?php echo $this->getArticleSelection(); ?>
					</div>
				</div>

				<button type="button" class="btn btn-primary pull-right start"><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_FIX_STRUCTURE_START'); ?></button>

				<div data-status-wrapper style="display: none;">
					<span><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_FIX_STRUCTURE_STATUS'); ?></span>
					<span class="fixStatus"></span>
				</div>

				<div data-total-wrapper style="display: none;">
					<span><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_FIX_STRUCTURE_TOTAL_ARTICLES'); ?></span>
					<span class="total"></span>
				</div>

				<div data-count-wrapper style="display: none;">
					<span><?php echo JText::_('COM_KOMENTO_SETTINGS_DATABASE_FIX_STRUCTURE_COUNT_ARTICLES'); ?></span>
					<span class="count"></span>
				</div>
			</div>
		</div>
	</div>
</div>

